<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Producto;
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

        $dailyIncomeQuery = Cita::selectRaw('fecha, SUM(precio) as total')->where('id_barberia', $idBarberia)->where('estado', 'completada')
            ->whereBetween('fecha', [$today->copy()->subDays(6)->toDateString(), $today->toDateString()])->groupBy('fecha')->pluck('total', 'fecha');
        $dailyIncome = [];
        for ($day = 6; $day >= 0; $day--) {
            $date = $today->copy()->subDays($day);
            $dailyIncome[] = ['label' => $date->format('d/m'), 'total' => (float) ($dailyIncomeQuery[$date->toDateString()] ?? 0)];
        }

        $topServices = DB::table('citas')->join('servicios', 'citas.id_servicio', '=', 'servicios.id_servicio')
            ->select('servicios.nombre', DB::raw('COUNT(citas.id_cita) as total'), DB::raw('SUM(citas.precio) as ingresos'))
            ->where('citas.id_barberia', $idBarberia)->where('citas.estado', 'completada')->whereBetween('citas.fecha', [$start->toDateString(), $end->toDateString()])
            ->groupBy('servicios.nombre')->orderByDesc('total')->limit(5)->get();
        $topClients = DB::table('citas')->join('clientes', 'citas.id_cliente', '=', 'clientes.id_cliente')
            ->select('clientes.nombre', 'clientes.apellido', DB::raw('COUNT(citas.id_cita) as total'), DB::raw('SUM(citas.precio) as gasto_total'))
            ->where('citas.id_barberia', $idBarberia)->where('citas.estado', 'completada')->whereBetween('citas.fecha', [$start->toDateString(), $end->toDateString()])
            ->groupBy('clientes.id_cliente', 'clientes.nombre', 'clientes.apellido')->orderByDesc('total')->limit(5)->get();
        $topProducts = DB::table('detalle_venta_productos as detalle')->join('ventas_productos as ventas', 'detalle.id_venta', '=', 'ventas.id_venta')->join('productos', 'detalle.id_producto', '=', 'productos.id_producto')
            ->select('productos.nombre', DB::raw('SUM(detalle.cantidad) as total_vendido'), DB::raw('SUM(detalle.subtotal) as ingresos'))
            ->where('ventas.id_barberia', $idBarberia)->whereBetween('ventas.fecha_venta', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->groupBy('productos.nombre')->orderByDesc('total_vendido')->limit(5)->get();

        return [
            'mesSeleccionado' => $month, 'inicioMes' => $start, 'finMes' => $end,
            'totalClientes' => Cliente::where('id_barberia', $idBarberia)->where('activo', 1)->count(),
            'clientesInactivos' => Cliente::where('id_barberia', $idBarberia)->where('activo', 1)->whereNotNull('ultima_visita')->whereDate('ultima_visita', '<=', $today->copy()->subDays(20))->count(),
            'citasHoy' => Cita::where('id_barberia', $idBarberia)->whereDate('fecha', $today)->count(),
            'ingresosHoy' => Cita::where('id_barberia', $idBarberia)->where('estado', 'completada')->whereDate('fecha', $today)->sum('precio'),
            'ingresosMes' => (clone $appointments)->where('estado', 'completada')->sum('precio'),
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
