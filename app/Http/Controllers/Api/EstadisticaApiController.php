<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\HistorialServicio;
use App\Models\Producto;
use App\Models\VentaProducto;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticaApiController extends Controller
{
    private function rangoFechas(Request $request): array
    {
        $inicio = $request->query('inicio')
            ? Carbon::parse($request->query('inicio'))->toDateString()
            : now()->startOfMonth()->toDateString();

        $fin = $request->query('fin')
            ? Carbon::parse($request->query('fin'))->toDateString()
            : now()->endOfMonth()->toDateString();

        return [$inicio, $fin];
    }

    public function ingresos(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        [$inicio, $fin] = $this->rangoFechas($request);

        $hoy = now()->toDateString();
        $inicioSemana = now()->startOfWeek()->toDateString();
        $finSemana = now()->endOfWeek()->toDateString();
        $inicioMes = now()->startOfMonth()->toDateString();
        $finMes = now()->endOfMonth()->toDateString();

        $ingresosServiciosDia = HistorialServicio::where('id_barberia', $idBarberia)
            ->whereDate('fecha_servicio', $hoy)
            ->sum('precio');

        $ingresosProductosDia = VentaProducto::where('id_barberia', $idBarberia)
            ->whereDate('fecha_venta', $hoy)
            ->sum('total');

        $ingresosServiciosSemana = HistorialServicio::where('id_barberia', $idBarberia)
            ->whereBetween('fecha_servicio', [$inicioSemana, $finSemana])
            ->sum('precio');

        $ingresosProductosSemana = VentaProducto::where('id_barberia', $idBarberia)
            ->whereDate('fecha_venta', '>=', $inicioSemana)
            ->whereDate('fecha_venta', '<=', $finSemana)
            ->sum('total');

        $ingresosServiciosMes = HistorialServicio::where('id_barberia', $idBarberia)
            ->whereBetween('fecha_servicio', [$inicioMes, $finMes])
            ->sum('precio');

        $ingresosProductosMes = VentaProducto::where('id_barberia', $idBarberia)
            ->whereDate('fecha_venta', '>=', $inicioMes)
            ->whereDate('fecha_venta', '<=', $finMes)
            ->sum('total');

        $serviciosPorDia = HistorialServicio::selectRaw('DATE(fecha_servicio) as fecha, SUM(precio) as total')
            ->where('id_barberia', $idBarberia)
            ->whereBetween('fecha_servicio', [$inicio, $fin])
            ->groupBy(DB::raw('DATE(fecha_servicio)'))
            ->pluck('total', 'fecha');

        $productosPorDia = VentaProducto::selectRaw('DATE(fecha_venta) as fecha, SUM(total) as total')
            ->where('id_barberia', $idBarberia)
            ->whereDate('fecha_venta', '>=', $inicio)
            ->whereDate('fecha_venta', '<=', $fin)
            ->groupBy(DB::raw('DATE(fecha_venta)'))
            ->pluck('total', 'fecha');

        $grafica = [];

        foreach (CarbonPeriod::create($inicio, $fin) as $fecha) {
            $dia = $fecha->toDateString();

            $servicios = (float) ($serviciosPorDia[$dia] ?? 0);
            $productos = (float) ($productosPorDia[$dia] ?? 0);

            $grafica[] = [
                'fecha' => $dia,
                'servicios' => $servicios,
                'productos' => $productos,
                'total' => $servicios + $productos,
            ];
        }

        return response()->json([
            'ok' => true,
            'rango' => [
                'inicio' => $inicio,
                'fin' => $fin,
            ],
            'resumen' => [
                'ingresos_dia' => (float) ($ingresosServiciosDia + $ingresosProductosDia),
                'ingresos_semana' => (float) ($ingresosServiciosSemana + $ingresosProductosSemana),
                'ingresos_mes' => (float) ($ingresosServiciosMes + $ingresosProductosMes),
                'servicios_mes' => (float) $ingresosServiciosMes,
                'productos_mes' => (float) $ingresosProductosMes,
            ],
            'grafica' => $grafica,
        ]);
    }

    public function servicios(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        [$inicio, $fin] = $this->rangoFechas($request);

        $servicios = HistorialServicio::query()
            ->join('servicios', 'historial_servicios.id_servicio', '=', 'servicios.id_servicio')
            ->where('historial_servicios.id_barberia', $idBarberia)
            ->whereBetween('historial_servicios.fecha_servicio', [$inicio, $fin])
            ->select(
                'servicios.id_servicio',
                'servicios.nombre'
            )
            ->selectRaw('COUNT(*) as total_servicios')
            ->selectRaw('SUM(historial_servicios.precio) as ingresos_generados')
            ->groupBy('servicios.id_servicio', 'servicios.nombre')
            ->orderByDesc('total_servicios')
            ->limit(10)
            ->get();

        return response()->json([
            'ok' => true,
            'rango' => [
                'inicio' => $inicio,
                'fin' => $fin,
            ],
            'data' => $servicios,
        ]);
    }

    public function clientes(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        [$inicio, $fin] = $this->rangoFechas($request);

        $clientesFrecuentes = HistorialServicio::query()
            ->join('clientes', 'historial_servicios.id_cliente', '=', 'clientes.id_cliente')
            ->where('historial_servicios.id_barberia', $idBarberia)
            ->whereBetween('historial_servicios.fecha_servicio', [$inicio, $fin])
            ->select(
                'clientes.id_cliente',
                'clientes.nombre',
                'clientes.apellido',
                'clientes.telefono'
            )
            ->selectRaw('COUNT(*) as total_visitas')
            ->selectRaw('SUM(historial_servicios.precio) as total_gastado_servicios')
            ->groupBy(
                'clientes.id_cliente',
                'clientes.nombre',
                'clientes.apellido',
                'clientes.telefono'
            )
            ->orderByDesc('total_visitas')
            ->limit(10)
            ->get();

        $clientesInactivos = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->whereNotNull('ultima_visita')
            ->whereDate('ultima_visita', '<=', now()->subDays(20)->toDateString())
            ->orderBy('ultima_visita')
            ->limit(10)
            ->get([
                'id_cliente',
                'nombre',
                'apellido',
                'telefono',
                'puntos',
                'ultima_visita',
            ]);

        $citasPorEstado = Cita::where('id_barberia', $idBarberia)
            ->whereBetween('fecha', [$inicio, $fin])
            ->select('estado')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        return response()->json([
            'ok' => true,
            'rango' => [
                'inicio' => $inicio,
                'fin' => $fin,
            ],
            'clientes_frecuentes' => $clientesFrecuentes,
            'clientes_inactivos' => $clientesInactivos,
            'citas' => [
                'pendientes' => (int) ($citasPorEstado['pendiente'] ?? 0),
                'completadas' => (int) ($citasPorEstado['completada'] ?? 0),
                'canceladas' => (int) ($citasPorEstado['cancelada'] ?? 0),
            ],
        ]);
    }

    public function productos(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        [$inicio, $fin] = $this->rangoFechas($request);

        $productosVendidos = DB::table('detalle_venta_productos')
            ->join('ventas_productos', 'detalle_venta_productos.id_venta', '=', 'ventas_productos.id_venta')
            ->join('productos', 'detalle_venta_productos.id_producto', '=', 'productos.id_producto')
            ->where('ventas_productos.id_barberia', $idBarberia)
            ->whereDate('ventas_productos.fecha_venta', '>=', $inicio)
            ->whereDate('ventas_productos.fecha_venta', '<=', $fin)
            ->select(
                'productos.id_producto',
                'productos.nombre'
            )
            ->selectRaw('SUM(detalle_venta_productos.cantidad) as total_vendido')
            ->selectRaw('SUM(detalle_venta_productos.subtotal) as ingresos_generados')
            ->groupBy('productos.id_producto', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        $productosBajoStock = Producto::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->orderBy('stock')
            ->get([
                'id_producto',
                'nombre',
                'stock',
                'stock_minimo',
                'precio_venta',
            ]);

        return response()->json([
            'ok' => true,
            'rango' => [
                'inicio' => $inicio,
                'fin' => $fin,
            ],
            'productos_vendidos' => $productosVendidos,
            'productos_bajo_stock' => $productosBajoStock,
        ]);
    }
}