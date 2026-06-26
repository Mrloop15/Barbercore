@extends('layouts.app')

@section('title', 'Productos | BarberCore')
@section('page-title', 'Productos')

@section('content')

<div class="agenda-summary">
    <div class="agenda-summary-card">
        <span>Total de productos</span>
        <strong>{{ $totalProductos }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Productos bajo stock</span>
        <strong>{{ $productosBajoStock }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Filtro actual</span>
        <strong>{{ $filtro === 'bajo_stock' ? 'Bajo stock' : 'Todos' }}</strong>
    </div>
</div>

<div class="content-card">
    <div class="page-actions">
        <form method="GET" action="{{ route('productos.index') }}" class="search-form">
            <input 
                type="text" 
                name="buscar" 
                value="{{ $buscar }}" 
                placeholder="Buscar producto por nombre o descripción"
            >

            <select name="filtro">
                <option value="todos" {{ $filtro === 'todos' ? 'selected' : '' }}>Todos</option>
                <option value="bajo_stock" {{ $filtro === 'bajo_stock' ? 'selected' : '' }}>Bajo stock</option>
            </select>

            <button type="submit" class="btn btn-secondary">
                Filtrar
            </button>
        </form>

        <a href="{{ route('productos.create') }}" class="btn btn-primary">
            Agregar producto
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio compra</th>
                <th>Precio venta</th>
                <th>Ganancia</th>
                <th>Stock</th>
                <th>Stock mínimo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($productos as $producto)
                @php
                    $ganancia = $producto->precio_venta - $producto->precio_compra;
                    $bajoStock = $producto->stock <= $producto->stock_minimo;
                @endphp

                <tr>
                    <td>
                        <strong>{{ $producto->nombre }}</strong>
                        <br>
                        <span style="color: var(--gris); font-size: 13px;">
                            {{ $producto->descripcion ?? 'Sin descripción' }}
                        </span>
                    </td>

                    <td>${{ number_format($producto->precio_compra, 2) }}</td>

                    <td>${{ number_format($producto->precio_venta, 2) }}</td>

                    <td>
                        <span class="product-profit">
                            ${{ number_format($ganancia, 2) }}
                        </span>
                    </td>

                    <td>
                        <span class="{{ $bajoStock ? 'stock-low' : 'stock-ok' }}">
                            {{ $producto->stock }}
                        </span>
                    </td>

                    <td>{{ $producto->stock_minimo }}</td>

                    <td>
                        @if ($bajoStock)
                            <span class="badge badge-cancelada">
                                Bajo stock
                            </span>
                        @else
                            <span class="badge badge-completada">
                                Disponible
                            </span>
                        @endif
                    </td>

                    <td>
                        <div class="actions">
                            <a href="{{ route('productos.edit', $producto->id_producto) }}" class="btn btn-primary btn-sm">
                                Editar
                            </a>

                            <form method="POST" action="{{ route('productos.destroy', $producto->id_producto) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No hay productos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $productos->links() }}
    </div>
</div>

@endsection