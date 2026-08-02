@extends('layouts.app')

@section('title', 'Ventas de productos | BarberCore')
@section('page-title', 'Ventas de productos')

@section('content')

<div class="agenda-summary">
    <div class="agenda-summary-card">
        <span>Ventas del día</span>
        <strong>{{ $cantidadVentasDia }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Total vendido</span>
        <strong>${{ number_format($totalVentasDia, 2) }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Fecha consultada</span>
        <strong>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</strong>
    </div>
</div>

<div class="content-card">
    <div class="module-tools">
        <div class="filter-panel">
            <div class="filter-panel-head">
                <div class="filter-panel-heading"><span class="filter-panel-icon"><x-icon name="calendar" /></span><div><strong class="filter-panel-title">Consultar ventas</strong><span class="filter-panel-subtitle">Selecciona el día que deseas revisar.</span></div></div>
                <div class="filter-panel-meta">
                    <span class="filter-result-count"><strong>{{ $ventas->total() }}</strong> resultados</span>
                    <div class="module-primary-actions"><a href="{{ route('ventas-productos.create') }}" class="btn module-action-btn"><x-icon name="plus" /> <span>Registrar venta</span></a></div>
                </div>
            </div>
            <form method="GET" action="{{ route('ventas-productos.index') }}" class="filter-form">
                <label class="filter-field filter-field-date"><span class="filter-label">Fecha de venta</span><input type="date" name="fecha" value="{{ $fecha }}"></label>
                <div class="filter-actions"><button type="submit" class="btn btn-secondary"><x-icon name="search" /> Consultar</button>@if ($fecha !== \Carbon\Carbon::today()->toDateString())<a href="{{ route('ventas-productos.index') }}" class="btn filter-clear">Ver hoy</a>@endif</div>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Productos</th>
                <th>Total</th>
                <th>Detalle</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($ventas as $venta)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}</td>

                    <td>
                        @if ($venta->cliente)
                            {{ $venta->cliente->nombre }} {{ $venta->cliente->apellido }}
                        @else
                            Cliente general
                        @endif
                    </td>

                    <td>
                        {{ $venta->detalles->sum('cantidad') }} producto(s)
                    </td>

                    <td>
                        <strong>${{ number_format($venta->total, 2) }}</strong>
                    </td>

                    <td>
                        <a href="{{ route('ventas-productos.show', $venta->id_venta) }}" class="btn btn-secondary btn-icon" title="Ver detalle de venta" aria-label="Ver detalle de venta">
                            <x-icon name="eye" /><span class="sr-only">Ver detalle de venta</span>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay ventas registradas para esta fecha.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $ventas->links() }}
    </div>
</div>

@endsection
