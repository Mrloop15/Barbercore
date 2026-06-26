<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\DetalleVentaProducto;
use App\Models\MovimientoPunto;
use App\Models\Producto;
use App\Models\VentaProducto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaProductoController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $fecha = $request->input('fecha', Carbon::today()->toDateString());

        $ventas = VentaProducto::with(['cliente', 'detalles.producto'])
            ->where('id_barberia', $idBarberia)
            ->whereDate('fecha_venta', $fecha)
            ->orderByDesc('fecha_venta')
            ->paginate(10)
            ->withQueryString();

        $totalVentasDia = VentaProducto::where('id_barberia', $idBarberia)
            ->whereDate('fecha_venta', $fecha)
            ->sum('total');

        $cantidadVentasDia = VentaProducto::where('id_barberia', $idBarberia)
            ->whereDate('fecha_venta', $fecha)
            ->count();

        return view('ventas.index', compact(
            'ventas',
            'fecha',
            'totalVentasDia',
            'cantidadVentasDia'
        ));
    }

    public function create()
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $clientes = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $productos = Producto::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        return view('ventas.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $request->validate([
            'id_cliente' => 'nullable|exists:clientes,id_cliente',
            'productos' => 'required|array|min:1',
            'productos.*' => 'required|exists:productos,id_producto',
            'cantidades' => 'required|array|min:1',
            'cantidades.*' => 'required|integer|min:1',
        ], [
            'productos.required' => 'Debes seleccionar al menos un producto.',
            'cantidades.required' => 'Debes indicar la cantidad de cada producto.',
        ]);

        try {
            DB::transaction(function () use ($request, $idBarberia) {
                $total = 0;
                $detallesPreparados = [];

                foreach ($request->productos as $index => $idProducto) {
                    $cantidad = (int) $request->cantidades[$index];

                    $producto = Producto::where('id_barberia', $idBarberia)
                        ->where('activo', 1)
                        ->where('id_producto', $idProducto)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($producto->stock < $cantidad) {
                        throw new \Exception('Stock insuficiente para el producto: ' . $producto->nombre);
                    }

                    $precioUnitario = $producto->precio_venta;
                    $subtotal = $precioUnitario * $cantidad;

                    $total += $subtotal;

                    $detallesPreparados[] = [
                        'producto' => $producto,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $precioUnitario,
                        'subtotal' => $subtotal,
                    ];
                }

                $venta = VentaProducto::create([
                    'id_barberia' => $idBarberia,
                    'id_cliente' => $request->id_cliente,
                    'total' => $total,
                    'fecha_venta' => now(),
                ]);

                foreach ($detallesPreparados as $detalle) {
                    DetalleVentaProducto::create([
                        'id_venta' => $venta->id_venta,
                        'id_producto' => $detalle['producto']->id_producto,
                        'cantidad' => $detalle['cantidad'],
                        'precio_unitario' => $detalle['precio_unitario'],
                        'subtotal' => $detalle['subtotal'],
                    ]);

                    $detalle['producto']->update([
                        'stock' => DB::raw('stock - ' . $detalle['cantidad']),
                    ]);
                }

                if ($request->id_cliente) {
                    $cliente = Cliente::where('id_barberia', $idBarberia)
                        ->where('activo', 1)
                        ->where('id_cliente', $request->id_cliente)
                        ->first();

                    if ($cliente) {
                        $puntosGanados = floor($total / 50);

                        if ($puntosGanados > 0) {
                            $cliente->update([
                                'puntos' => DB::raw('puntos + ' . $puntosGanados),
                            ]);

                            MovimientoPunto::create([
                                'id_barberia' => $idBarberia,
                                'id_cliente' => $cliente->id_cliente,
                                'tipo' => 'suma',
                                'puntos' => $puntosGanados,
                                'motivo' => 'Puntos generados por compra de producto',
                                'referencia' => 'venta:' . $venta->id_venta,
                                'created_at' => now(),
                            ]);
                        }
                    }
                }
            });

            return redirect()
                ->route('ventas-productos.index')
                ->with('success', 'Venta registrada correctamente. El stock fue actualizado.');

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $venta = VentaProducto::with(['cliente', 'detalles.producto'])
            ->where('id_barberia', $idBarberia)
            ->where('id_venta', $id)
            ->firstOrFail();

        return view('ventas.show', compact('venta'));
    }
}