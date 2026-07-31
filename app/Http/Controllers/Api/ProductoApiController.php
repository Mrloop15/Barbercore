<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoApiController extends Controller
{
    private function formatearProducto(Producto $producto): array
    {
        return [
            'id_producto' => $producto->id_producto,
            'id_barberia' => $producto->id_barberia,
            'nombre' => $producto->nombre,
            'descripcion' => $producto->descripcion,
            'imagen' => $producto->imagen,
            'imagen_url' => $producto->imagen ? url('storage/' . $producto->imagen) : null,
            'precio_compra' => (float) $producto->precio_compra,
            'precio_venta' => (float) $producto->precio_venta,
            'stock' => (int) $producto->stock,
            'stock_minimo' => (int) $producto->stock_minimo,
            'activo' => (int) $producto->activo,
            'created_at' => $producto->created_at,
            'updated_at' => $producto->updated_at,
        ];
    }

    public function index(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $productos = Producto::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get()
            ->map(fn ($producto) => $this->formatearProducto($producto));

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
            ->get()
            ->map(fn ($producto) => $this->formatearProducto($producto));

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
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'activo' => 'nullable|boolean',
        ]);

        $datos['id_barberia'] = $idBarberia;
        $datos['activo'] = $datos['activo'] ?? 1;

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($datos);

        return response()->json([
            'ok' => true,
            'message' => 'Producto registrado correctamente.',
            'data' => $this->formatearProducto($producto),
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
            'data' => $this->formatearProducto($producto),
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
            'descripcion' => 'sometimes|nullable|string',
            'imagen' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'precio_compra' => 'sometimes|required|numeric|min:0',
            'precio_venta' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'stock_minimo' => 'sometimes|required|integer|min:0',
            'activo' => 'sometimes|required|boolean',
        ]);

        if ($request->hasFile('imagen')) {
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($datos);
        $producto->refresh();

        return response()->json([
            'ok' => true,
            'message' => 'Producto actualizado correctamente.',
            'data' => $this->formatearProducto($producto),
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

        $producto->refresh();

        return response()->json([
            'ok' => true,
            'message' => 'Stock actualizado correctamente.',
            'data' => $this->formatearProducto($producto),
        ]);
    }
}