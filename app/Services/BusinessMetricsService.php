<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\VentaProducto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BusinessMetricsService
{
    public function monthly(int $idBarberia, string $month): array
    {
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $today = Carbon::today();
        $appointments = Cita::where('id_barberia', $idBarberia)->whereBetween('fecha', [$start->toDateString(), $end->toDateString()]);

        $dailyServiceIncome = Cita::selectRaw('fecha, SUM(precio) as total')->where('id_barberia', $idBarberia)->where('estado', 'completada')
            ->whereBetween('fecha', [$today->copy()->subDays(6)->toDateString(), $today->toDateString()])->groupBy('fecha')->pluck('total', 'fecha');
        $dailyProductIncome = VentaProducto::where('id_barberia', $idBarberia)
            ->whereBetween('fecha_venta', [$today->copy()->subDays(6)->startOfDay(), $today->copy()->endOfDay()])
            ->get(['fecha_venta', 'total'])
            ->groupBy(fn (VentaProducto $venta) => Carbon::parse($venta->fecha_venta)->toDateString())
            ->map(fn ($ventas) => (float) $ventas->sum('total'));
        $dailyIncome = [];
        for ($day = 6; $day >= 0; $day--) {
            $date = $today->copy()->subDays($day);
            $dateKey = $date->toDateString();
            $dailyIncome[] = [
                'label' => $date->format('d/m'),
                'total' => (float) ($dailyServiceIncome[$dateKey] ?? 0) + (float) ($dailyProductIncome[$dateKey] ?? 0),
            ];
        }

        $topServices = DB::table('citas')->join('servicios', 'citas.id_servicio', '=', 'servicios.id_servicio')
            ->select('servicios.nombre', DB::raw('COUNT(citas.id_cita) as total'), DB::raw('SUM(citas.precio) as ingresos'))
            ->where('citas.id_barberia', $idBarberia)->where('citas.estado', 'completada')->whereBetween('citas.fecha', [$start->toDateString(), $end->toDateString()])
            ->groupBy('servicios.nombre')->orderByDesc('total')->limit(5)->get();
        $appointmentClients = DB::table('citas')->join('clientes', 'citas.id_cliente', '=', 'clientes.id_cliente')
            ->select('clientes.id_cliente', 'clientes.nombre', 'clientes.apellido', 'clientes.foto', DB::raw('COUNT(citas.id_cita) as total'), DB::raw('SUM(citas.precio) as gasto_total'))
            ->where('citas.id_barberia', $idBarberia)->where('clientes.id_barberia', $idBarberia)->where('citas.estado', 'completada')->whereBetween('citas.fecha', [$start->toDateString(), $end->toDateString()])
            ->groupBy('clientes.id_cliente', 'clientes.nombre', 'clientes.apellido', 'clientes.foto')->get();
        $saleClients = DB::table('ventas_productos')->join('clientes', 'ventas_productos.id_cliente', '=', 'clientes.id_cliente')
            ->select('clientes.id_cliente', 'clientes.nombre', 'clientes.apellido', 'clientes.foto', DB::raw('COUNT(ventas_productos.id_venta) as total'), DB::raw('SUM(ventas_productos.total) as gasto_total'))
            ->where('ventas_productos.id_barberia', $idBarberia)->where('clientes.id_barberia', $idBarberia)->whereBetween('ventas_productos.fecha_venta', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('citas as citas_completadas')
                    ->whereColumn('citas_completadas.id_barberia', 'ventas_productos.id_barberia')
                    ->whereColumn('citas_completadas.id_cliente', 'ventas_productos.id_cliente')
                    ->where('citas_completadas.estado', 'completada')
                    ->whereRaw('citas_completadas.fecha = DATE(ventas_productos.fecha_venta)');
            })
            ->groupBy('clientes.id_cliente', 'clientes.nombre', 'clientes.apellido', 'clientes.foto')->get();
        $topClients = $appointmentClients->concat($saleClients)
            ->groupBy('id_cliente')
            ->map(function ($interactions) {
                $client = $interactions->first();

                return (object) [
                    'id_cliente' => $client->id_cliente,
                    'nombre' => $client->nombre,
                    'apellido' => $client->apellido,
                    'foto' => $client->foto,
                    'total' => $interactions->sum('total'),
                    'gasto_total' => $interactions->sum('gasto_total'),
                ];
            })
            ->sort(function ($first, $second) {
                return ($second->total <=> $first->total)
                    ?: ($second->gasto_total <=> $first->gasto_total)
                    ?: strcmp($first->nombre, $second->nombre);
            })
            ->values()
            ->take(5);
        $topProducts = DB::table('detalle_venta_productos as detalle')->join('ventas_productos as ventas', 'detalle.id_venta', '=', 'ventas.id_venta')->join('productos', 'detalle.id_producto', '=', 'productos.id_producto')
            ->select('productos.nombre', DB::raw('SUM(detalle.cantidad) as total_vendido'), DB::raw('SUM(detalle.subtotal) as ingresos'))
            ->where('ventas.id_barberia', $idBarberia)->whereBetween('ventas.fecha_venta', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->where('productos.id_barberia', $idBarberia)
            ->groupBy('productos.nombre')->orderByDesc('total_vendido')->limit(5)->get();

        return [
            'mesSeleccionado' => $month, 'inicioMes' => $start, 'finMes' => $end,
            'totalClientes' => Cliente::where('id_barberia', $idBarberia)->where('activo', 1)->count(),
            'clientesInactivos' => Cliente::where('id_barberia', $idBarberia)->where('activo', 1)->whereNotNull('ultima_visita')->whereDate('ultima_visita', '<=', $today->copy()->subDays(20))->count(),
            'citasHoy' => Cita::where('id_barberia', $idBarberia)->whereDate('fecha', $today)->count(),
            'ingresosHoy' => Cita::where('id_barberia', $idBarberia)->where('estado', 'completada')->whereDate('fecha', $today)->sum('precio')
                + VentaProducto::where('id_barberia', $idBarberia)->whereDate('fecha_venta', $today)->sum('total'),
            'ingresosMes' => (clone $appointments)->where('estado', 'completada')->sum('precio')
                + VentaProducto::where('id_barberia', $idBarberia)->whereBetween('fecha_venta', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])->sum('total'),
            'citasCompletadas' => (clone $appointments)->where('estado', 'completada')->count(),
            'citasCanceladas' => (clone $appointments)->where('estado', 'cancelada')->count(),
            'citasPendientes' => (clone $appointments)->where('estado', 'pendiente')->count(),
            'productosBajoStock' => Producto::where('id_barberia', $idBarberia)->where('activo', 1)->whereColumn('stock', '<=', 'stock_minimo')->count(),
            'ingresosPorDia' => $dailyIncome, 'maxIngresosDia' => max(1, collect($dailyIncome)->max('total')),
            'serviciosMasVendidos' => $topServices, 'maxServicios' => max(1, $topServices->max('total') ?? 0),
            'clientesFrecuentes' => $topClients, 'maxClientes' => max(1, $topClients->max('total') ?? 0),
            'productosVendidos' => $topProducts, 'maxProductos' => max(1, $topProducts->max('total_vendido') ?? 0),
        ];
    }
}
