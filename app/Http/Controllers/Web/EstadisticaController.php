<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstadisticaController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $mesSeleccionado = $request->input('mes', Carbon::today()->format('Y-m'));

        $inicioMes = Carbon::parse($mesSeleccionado . '-01')->startOfMonth();
        $finMes = Carbon::parse($mesSeleccionado . '-01')->endOfMonth();

        $hoy = Carbon::today();

        $totalClientes = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->count();

        $clientesInactivos = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->whereNotNull('ultima_visita')
            ->whereDate('ultima_visita', '<=', $hoy->copy()->subDays(20))
            ->count();

        $citasHoy = Cita::where('id_barberia', $idBarberia)
            ->whereDate('fecha', $hoy)
            ->count();

        $ingresosHoy = Cita::where('id_barberia', $idBarberia)
            ->where('estado', 'completada')
            ->whereDate('fecha', $hoy)
            ->sum('precio');

        $ingresosMes = Cita::where('id_barberia', $idBarberia)
            ->where('estado', 'completada')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->sum('precio');

        $citasCompletadas = Cita::where('id_barberia', $idBarberia)
            ->where('estado', 'completada')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->count();

        $citasCanceladas = Cita::where('id_barberia', $idBarberia)
            ->where('estado', 'cancelada')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->count();

        $citasPendientes = Cita::where('id_barberia', $idBarberia)
            ->where('estado', 'pendiente')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->count();

        $productosBajoStock = Producto::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->count();

        $ingresosConsulta = Cita::selectRaw('fecha, SUM(precio) as total')
            ->where('id_barberia', $idBarberia)
            ->where('estado', 'completada')
            ->whereBetween('fecha', [
                $hoy->copy()->subDays(6)->toDateString(),
                $hoy->toDateString()
            ])
            ->groupBy('fecha')
            ->pluck('total', 'fecha');

        $ingresosPorDia = [];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = $hoy->copy()->subDays($i);
            $fechaTexto = $fecha->toDateString();

            $ingresosPorDia[] = [
                'fecha' => $fechaTexto,
                'label' => $fecha->format('d/m'),
                'total' => (float) ($ingresosConsulta[$fechaTexto] ?? 0),
            ];
        }

        $maxIngresosDia = max(1, collect($ingresosPorDia)->max('total'));

        $serviciosMasVendidos = DB::table('citas')
            ->join('servicios', 'citas.id_servicio', '=', 'servicios.id_servicio')
            ->select(
                'servicios.nombre',
                DB::raw('COUNT(citas.id_cita) as total'),
                DB::raw('SUM(citas.precio) as ingresos')
            )
            ->where('citas.id_barberia', $idBarberia)
            ->where('citas.estado', 'completada')
            ->whereBetween('citas.fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->groupBy('servicios.nombre')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $maxServicios = max(1, $serviciosMasVendidos->max('total') ?? 0);

        $clientesFrecuentes = DB::table('citas')
            ->join('clientes', 'citas.id_cliente', '=', 'clientes.id_cliente')
            ->select(
                'clientes.nombre',
                'clientes.apellido',
                DB::raw('COUNT(citas.id_cita) as total'),
                DB::raw('SUM(citas.precio) as gasto_total')
            )
            ->where('citas.id_barberia', $idBarberia)
            ->where('citas.estado', 'completada')
            ->whereBetween('citas.fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->groupBy('clientes.id_cliente', 'clientes.nombre', 'clientes.apellido')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $maxClientes = max(1, $clientesFrecuentes->max('total') ?? 0);

        $productosVendidos = DB::table('detalle_venta_productos as detalle')
            ->join('ventas_productos as ventas', 'detalle.id_venta', '=', 'ventas.id_venta')
            ->join('productos', 'detalle.id_producto', '=', 'productos.id_producto')
            ->select(
                'productos.nombre',
                DB::raw('SUM(detalle.cantidad) as total_vendido'),
                DB::raw('SUM(detalle.subtotal) as ingresos')
            )
            ->where('ventas.id_barberia', $idBarberia)
            ->whereBetween('ventas.fecha_venta', [
                $inicioMes->startOfDay()->toDateTimeString(),
                $finMes->endOfDay()->toDateTimeString()
            ])
            ->groupBy('productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        $maxProductos = max(1, $productosVendidos->max('total_vendido') ?? 0);

        return view('estadisticas.index', compact(
            'mesSeleccionado',
            'inicioMes',
            'finMes',
            'totalClientes',
            'clientesInactivos',
            'citasHoy',
            'ingresosHoy',
            'ingresosMes',
            'citasCompletadas',
            'citasCanceladas',
            'citasPendientes',
            'productosBajoStock',
            'ingresosPorDia',
            'maxIngresosDia',
            'serviciosMasVendidos',
            'maxServicios',
            'clientesFrecuentes',
            'maxClientes',
            'productosVendidos',
            'maxProductos'
        ));
    }
}