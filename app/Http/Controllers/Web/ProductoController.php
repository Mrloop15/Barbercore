<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $buscar = $request->input('buscar');
        $filtro = $request->input('filtro', 'todos');

        $productos = Producto::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->when($buscar, function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'LIKE', "%{$buscar}%")
                        ->orWhere('descripcion', 'LIKE', "%{$buscar}%");
                });
            })
            ->when($filtro === 'bajo_stock', function ($query) {
                $query->whereColumn('stock', '<=', 'stock_minimo');
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        $totalProductos = Producto::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->count();

        $productosBajoStock = Producto::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->count();

        return view('productos.index', compact(
            'productos',
            'buscar',
            'filtro',
            'totalProductos',
            'productosBajoStock'
        ));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $request->validate([
            'nombre' => 'required|string|max:120',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'precio_compra.required' => 'El precio de compra es obligatorio.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'stock.required' => 'El stock es obligatorio.',
            'stock_minimo.required' => 'El stock mínimo es obligatorio.',
        ]);

        Producto::create([
            'id_barberia' => $idBarberia,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_compra' => $request->precio_compra,
            'precio_venta' => $request->precio_venta,
            'stock' => $request->stock,
            'stock_minimo' => $request->stock_minimo,
            'activo' => 1,
        ]);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    public function edit(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $producto = Producto::where('id_barberia', $idBarberia)
            ->where('id_producto', $id)
            ->firstOrFail();

        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $producto = Producto::where('id_barberia', $idBarberia)
            ->where('id_producto', $id)
            ->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:120',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'precio_compra.required' => 'El precio de compra es obligatorio.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'stock.required' => 'El stock es obligatorio.',
            'stock_minimo.required' => 'El stock mínimo es obligatorio.',
        ]);

        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_compra' => $request->precio_compra,
            'precio_venta' => $request->precio_venta,
            'stock' => $request->stock,
            'stock_minimo' => $request->stock_minimo,
        ]);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $producto = Producto::where('id_barberia', $idBarberia)
            ->where('id_producto', $id)
            ->firstOrFail();

        $producto->update([
            'activo' => 0,
        ]);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}