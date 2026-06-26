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
    <div class="page-actions">
        <form method="GET" action="{{ route('ventas-productos.index') }}" class="search-form">
            <input 
                type="date" 
                name="fecha" 
                value="{{ $fecha }}"
            >

            <button type="submit" class="btn btn-secondary">
                Consultar
            </button>
        </form>

        <a href="{{ route('ventas-productos.create') }}" class="btn btn-primary">
            Nueva venta
        </a>
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
                        <a href="{{ route('ventas-productos.show', $venta->id_venta) }}" class="btn btn-secondary btn-sm">
                            Ver detalle
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