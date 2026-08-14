<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\HistorialServicio;
use App\Models\Producto;
use App\Models\VentaProducto;
use App\Support\BusinessClock;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticaApiController extends Controller
{
    private function rangoFechas(Request $request): array
    {
        $inicio = $request->query('inicio')
            ? BusinessClock::localDate($request->query('inicio'))->toDateString()
            : BusinessClock::now()->startOfMonth()->toDateString();

        $fin = $request->query('fin')
            ? BusinessClock::localDate($request->query('fin'))->toDateString()
            : BusinessClock::now()->endOfMonth()->toDateString();

        return [$inicio, $fin];
    }

    public function ingresos(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        [$inicio, $fin] = $this->rangoFechas($request);

        $ahoraLocal = BusinessClock::now();
        $hoy = $ahoraLocal->toDateString();
        $inicioSemana = $ahoraLocal->startOfWeek()->toDateString();
        $finSemana = $ahoraLocal->endOfWeek()->toDateString();
        $inicioMes = $ahoraLocal->startOfMonth()->toDateString();
        $finMes = $ahoraLocal->endOfMonth()->toDateString();
        [$inicioDiaUtc, $finDiaUtc] = BusinessClock::utcRange($hoy, $hoy);
        [$inicioSemanaUtc, $finSemanaUtc] = BusinessClock::utcRange($inicioSemana, $finSemana);
        [$inicioMesUtc, $finMesUtc] = BusinessClock::utcRange($inicioMes, $finMes);
        [$inicioGraficaUtc, $finGraficaUtc] = BusinessClock::utcRange($inicio, $fin);

        $ingresosServiciosDia = HistorialServicio::where('id_barberia', $idBarberia)
            ->whereDate('fecha_servicio', $hoy)
            ->sum('precio');

        $ingresosProductosDia = VentaProducto::where('id_barberia', $idBarberia)
            ->whereBetween('fecha_venta', [$inicioDiaUtc, $finDiaUtc])
            ->sum('total');

        $ingresosServiciosSemana = HistorialServicio::where('id_barberia', $idBarberia)
            ->whereBetween('fecha_servicio', [$inicioSemana, $finSemana])
            ->sum('precio');

        $ingresosProductosSemana = VentaProducto::where('id_barberia', $idBarberia)
            ->whereBetween('fecha_venta', [$inicioSemanaUtc, $finSemanaUtc])
            ->sum('total');

        $ingresosServiciosMes = HistorialServicio::where('id_barberia', $idBarberia)
            ->whereBetween('fecha_servicio', [$inicioMes, $finMes])
            ->sum('precio');

        $ingresosProductosMes = VentaProducto::where('id_barberia', $idBarberia)
            ->whereBetween('fecha_venta', [$inicioMesUtc, $finMesUtc])
            ->sum('total');

        $serviciosPorDia = HistorialServicio::selectRaw('DATE(fecha_servicio) as fecha, SUM(precio) as total')
            ->where('id_barberia', $idBarberia)
            ->whereBetween('fecha_servicio', [$inicio, $fin])
            ->groupBy(DB::raw('DATE(fecha_servicio)'))
            ->pluck('total', 'fecha');

        $productosPorDia = VentaProducto::where('id_barberia', $idBarberia)
            ->whereBetween('fecha_venta', [$inicioGraficaUtc, $finGraficaUtc])
            ->get(['fecha_venta', 'total'])
            ->groupBy(fn (VentaProducto $venta) => BusinessClock::fromUtc($venta->fecha_venta)->toDateString())
            ->map(fn ($ventas) => (float) $ventas->sum('total'));

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
                'timezone' => BusinessClock::timezone(),
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
                'timezone' => BusinessClock::timezone(),
            ],
            'data' => $servicios,
        ]);
    }

    public function clientes(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        [$inicio, $fin] = $this->rangoFechas($request);

        $interaccionesServicios = HistorialServicio::query()
            ->join('clientes', 'historial_servicios.id_cliente', '=', 'clientes.id_cliente')
            ->where('historial_servicios.id_barberia', $idBarberia)
            ->where('clientes.id_barberia', $idBarberia)
            ->whereBetween('historial_servicios.fecha_servicio', [$inicio, $fin])
            ->select(
                'clientes.id_cliente',
                'clientes.nombre',
                'clientes.apellido',
                'clientes.telefono',
                'clientes.foto'
            )
            ->selectRaw('COUNT(*) as total_interacciones')
            ->selectRaw('SUM(historial_servicios.precio) as total_gastado_servicios')
            ->groupBy(
                'clientes.id_cliente',
                'clientes.nombre',
                'clientes.apellido',
                'clientes.telefono',
                'clientes.foto'
            )
            ->get();

        $fechasConServicio = Cita::where('id_barberia', $idBarberia)
            ->where('estado', 'completada')
            ->whereBetween('fecha', [$inicio, $fin])
            ->get(['id_cliente', 'fecha'])
            ->mapWithKeys(fn ($cita) => [$cita->id_cliente.'|'.$cita->fecha => true]);

        $interaccionesVentas = VentaProducto::query()
            ->join('clientes', 'ventas_productos.id_cliente', '=', 'clientes.id_cliente')
            ->where('ventas_productos.id_barberia', $idBarberia)
            ->where('clientes.id_barberia', $idBarberia)
            ->whereBetween('ventas_productos.fecha_venta', BusinessClock::utcRange($inicio, $fin))
            ->select(
                'clientes.id_cliente',
                'clientes.nombre',
                'clientes.apellido',
                'clientes.telefono',
                'clientes.foto',
                'ventas_productos.fecha_venta',
                'ventas_productos.total'
            )
            ->get()
            ->reject(fn ($venta) => $fechasConServicio->has($venta->id_cliente.'|'.BusinessClock::fromUtc($venta->fecha_venta)->toDateString()))
            ->groupBy('id_cliente')
            ->map(function ($ventas) {
                $cliente = $ventas->first();

                return (object) [
                    'id_cliente' => $cliente->id_cliente,
                    'nombre' => $cliente->nombre,
                    'apellido' => $cliente->apellido,
                    'telefono' => $cliente->telefono,
                    'foto' => $cliente->foto,
                    'total_interacciones' => $ventas->count(),
                    'total_gastado_productos' => $ventas->sum('total'),
                ];
            })->values();

        $clientesFrecuentes = $interaccionesServicios->concat($interaccionesVentas)
            ->groupBy('id_cliente')
            ->map(function ($interacciones) {
                $cliente = $interacciones->first();

                return (object) [
                    'id_cliente' => $cliente->id_cliente,
                    'nombre' => $cliente->nombre,
                    'apellido' => $cliente->apellido,
                    'telefono' => $cliente->telefono,
                    'foto' => $cliente->foto,
                    'total_interacciones' => (int) $interacciones->sum('total_interacciones'),
                    'total_visitas' => (int) $interacciones->sum('total_interacciones'),
                    'total_gastado_servicios' => (float) $interacciones->sum('total_gastado_servicios'),
                    'total_gastado_productos' => (float) $interacciones->sum('total_gastado_productos'),
                ];
            })
            ->sortByDesc('total_interacciones')
            ->take(10)
            ->values();

        $clientesInactivos = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->whereNotNull('ultima_visita')
            ->whereDate('ultima_visita', '<=', BusinessClock::today()->subDays(20)->toDateString())
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
                'timezone' => BusinessClock::timezone(),
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
            ->where('productos.id_barberia', $idBarberia)
            ->whereBetween('ventas_productos.fecha_venta', BusinessClock::utcRange($inicio, $fin))
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
                'timezone' => BusinessClock::timezone(),
            ],
            'productos_vendidos' => $productosVendidos,
            'productos_bajo_stock' => $productosBajoStock,
        ]);
    }
}
