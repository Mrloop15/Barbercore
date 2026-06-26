<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoApiController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $productos = Producto::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $productos,
        ]);
    }

    public function bajoStock(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $productos = Producto::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $productos,
        ]);
    }

    public function store(Request $request)
{
    $usuario = $request->user();
    $idBarberia = $usuario->id_barberia ?? 1;

    $datos = $request->validate([
        'nombre' => 'required|string|max:100',
        'descripcion' => 'nullable|string',
        'precio_compra' => 'required|numeric|min:0',
        'precio_venta' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'stock_minimo' => 'required|integer|min:0',
        'activo' => 'nullable|boolean',
    ]);

    $datos['id_barberia'] = $idBarberia;
    $datos['activo'] = $datos['activo'] ?? 1;

    $producto = Producto::create($datos);

    return response()->json([
        'ok' => true,
        'message' => 'Producto registrado correctamente.',
        'data' => $producto,
    ], 201);
}

public function show(Request $request, string $id)
{
    $usuario = $request->user();
    $idBarberia = $usuario->id_barberia ?? 1;

    $producto = Producto::where('id_barberia', $idBarberia)
        ->where('id_producto', $id)
        ->firstOrFail();

    return response()->json([
        'ok' => true,
        'data' => $producto,
    ]);
}

public function update(Request $request, string $id)
{
    $usuario = $request->user();
    $idBarberia = $usuario->id_barberia ?? 1;

    $producto = Producto::where('id_barberia', $idBarberia)
        ->where('id_producto', $id)
        ->firstOrFail();

    $datos = $request->validate([
        'nombre' => 'sometimes|required|string|max:100',
        'descripcion' => 'nullable|string',
        'precio_compra' => 'sometimes|required|numeric|min:0',
        'precio_venta' => 'sometimes|required|numeric|min:0',
        'stock' => 'sometimes|required|integer|min:0',
        'stock_minimo' => 'sometimes|required|integer|min:0',
        'activo' => 'sometimes|required|boolean',
    ]);

    $producto->update($datos);

    return response()->json([
        'ok' => true,
        'message' => 'Producto actualizado correctamente.',
        'data' => $producto,
    ]);
}

public function destroy(Request $request, string $id)
{
    $usuario = $request->user();
    $idBarberia = $usuario->id_barberia ?? 1;

    $producto = Producto::where('id_barberia', $idBarberia)
        ->where('id_producto', $id)
        ->firstOrFail();

    $producto->update([
        'activo' => 0,
    ]);

    return response()->json([
        'ok' => true,
        'message' => 'Producto desactivado correctamente.',
    ]);
}

public function actualizarStock(Request $request, string $id)
{
    $usuario = $request->user();
    $idBarberia = $usuario->id_barberia ?? 1;

    $producto = Producto::where('id_barberia', $idBarberia)
        ->where('id_producto', $id)
        ->firstOrFail();

    $datos = $request->validate([
        'stock' => 'required|integer|min:0',
    ]);

    $producto->update([
        'stock' => $datos['stock'],
    ]);

    return response()->json([
        'ok' => true,
        'message' => 'Stock actualizado correctamente.',
        'data' => $producto,
    ]);
}
}