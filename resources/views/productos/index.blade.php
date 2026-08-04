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
    <div class="module-tools">
        <div class="filter-panel">
            <div class="filter-panel-head">
                <div class="filter-panel-heading"><span class="filter-panel-icon"><x-icon name="filter" /></span><div><strong class="filter-panel-title">Filtrar inventario</strong><span class="filter-panel-subtitle">Busca productos y detecta existencias bajas.</span></div></div>
                <div class="filter-panel-meta">
                    <span class="filter-result-count"><strong>{{ $productos->total() }}</strong> resultados</span>
                    <div class="module-primary-actions"><a href="{{ route('productos.create') }}" class="btn module-action-btn"><x-icon name="plus" /> <span>Nuevo producto</span></a></div>
                </div>
            </div>
            <form method="GET" action="{{ route('productos.index') }}" class="filter-form">
                <label class="filter-field filter-field-grow"><span class="filter-label">Producto</span><span class="filter-search-control"><x-icon name="search" /><input type="search" name="buscar" value="{{ $buscar }}" placeholder="Nombre o descripción" autocomplete="off"></span></label>
                <label class="filter-field"><span class="filter-label">Existencias</span><select name="filtro"><option value="todos" {{ $filtro === 'todos' ? 'selected' : '' }}>Todos los productos</option><option value="bajo_stock" {{ $filtro === 'bajo_stock' ? 'selected' : '' }}>Sólo bajo stock</option></select></label>
                <div class="filter-actions"><button type="submit" class="btn btn-secondary"><x-icon name="filter" /> Aplicar</button>@if (filled($buscar) || $filtro !== 'todos')<a href="{{ route('productos.index') }}" class="btn filter-clear" title="Limpiar filtros"><x-icon name="close" /><span class="sr-only">Limpiar filtros</span></a>@endif</div>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Imagen</th>
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
                        @if ($producto->imagen)
                            <img 
                                src="{{ asset('storage/' . $producto->imagen) }}" 
                                alt="{{ $producto->nombre }}"
                                class="client-photo"
                            >
                        @else
                            <span class="table-image-placeholder" role="img" aria-label="Imagen de producto no disponible" title="Imagen no disponible">
                                <x-icon name="image" />
                            </span>
                        @endif
                    </td>

                    <td>
                        <strong>{{ $producto->nombre }}</strong>
                        <br>
                        <span class="muted-value-sm">
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
                            <a href="{{ route('productos.edit', $producto->id_producto) }}" class="btn btn-primary btn-icon" title="Editar producto" aria-label="Editar producto">
                                <x-icon name="edit" /><span class="sr-only">Editar producto</span>
                            </a>

                            <form method="POST" action="{{ route('productos.destroy', $producto->id_producto) }}" data-confirm-title="Eliminar producto" data-confirm="El producto {{ $producto->nombre }} dejará de estar disponible en el inventario." data-confirm-text="Sí, eliminar" data-confirm-tone="danger">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-icon" title="Eliminar producto" aria-label="Eliminar producto">
                                    <x-icon name="trash" /><span class="sr-only">Eliminar producto</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No hay productos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $productos->links() }}
    </div>
</div>

@endsection
