<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\DetalleVentaProducto;
use App\Models\MovimientoPunto;
use App\Models\Producto;
use App\Models\VentaProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaProductoApiController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $ventas = VentaProducto::with(['cliente', 'detalles.producto'])
            ->where('id_barberia', $idBarberia)
            ->orderByDesc('fecha_venta')
            ->paginate(10);

        return response()->json([
            'ok' => true,
            'data' => $ventas,
        ]);
    }

    public function show(Request $request, string $id)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $venta = VentaProducto::with(['cliente', 'detalles.producto'])
            ->where('id_barberia', $idBarberia)
            ->where('id_venta', $id)
            ->firstOrFail();

        return response()->json([
            'ok' => true,
            'data' => $venta,
        ]);
    }

    public function store(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $datos = $request->validate([
            'id_cliente' => 'nullable|integer|exists:clientes,id_cliente',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|integer|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        $venta = DB::transaction(function () use ($datos, $idBarberia) {
            $total = 0;
            $productosProcesados = [];

            foreach ($datos['productos'] as $item) {
                $producto = Producto::where('id_barberia', $idBarberia)
                    ->where('id_producto', $item['id_producto'])
                    ->where('activo', 1)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($producto->stock < $item['cantidad']) {
                    abort(response()->json([
                        'ok' => false,
                        'message' => 'Stock insuficiente para el producto: ' . $producto->nombre,
                    ], 422));
                }

                $subtotal = $producto->precio_venta * $item['cantidad'];
                $total += $subtotal;

                $productosProcesados[] = [
                    'producto' => $producto,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio_venta,
                    'subtotal' => $subtotal,
                ];
            }

            $venta = VentaProducto::create([
                'id_barberia' => $idBarberia,
                'id_cliente' => $datos['id_cliente'] ?? null,
                'total' => $total,
                'fecha_venta' => now(),
            ]);

            foreach ($productosProcesados as $item) {
                DetalleVentaProducto::create([
                    'id_venta' => $venta->id_venta,
                    'id_producto' => $item['producto']->id_producto,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $item['subtotal'],
                ]);

                $item['producto']->decrement('stock', $item['cantidad']);
            }

            if (!empty($datos['id_cliente'])) {
                $puntosGanados = (int) floor($total / 10);

                if ($puntosGanados > 0) {
                    Cliente::where('id_barberia', $idBarberia)
                        ->where('id_cliente', $datos['id_cliente'])
                        ->increment('puntos', $puntosGanados);

                    MovimientoPunto::create([
                        'id_barberia' => $idBarberia,
                        'id_cliente' => $datos['id_cliente'],
                        'tipo' => 'suma',
                        'puntos' => $puntosGanados,
                        'motivo' => 'Puntos generados por compra de productos',
                        'referencia' => 'venta:' . $venta->id_venta,
                        'created_at' => now(),
                    ]);
                }
            }

            return $venta->load(['cliente', 'detalles.producto']);
        });

        return response()->json([
            'ok' => true,
            'message' => 'Venta registrada correctamente.',
            'data' => $venta,
        ], 201);
    }
}