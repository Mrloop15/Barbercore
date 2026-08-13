@extends('layouts.app')

@section('title', 'Detalle de venta | BarberCore')
@section('page-title', 'Detalle de venta')

@section('content')

<div class="content-card">
    <div class="page-actions">
        <x-section-heading :title="'Venta #' . $venta->id_venta">
            <x-slot:subtitle>
                Registrada el {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}
            </x-slot:subtitle>
        </x-section-heading>

        <a href="{{ route('ventas-productos.index') }}" class="btn btn-secondary">
            Volver
        </a>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <span>Cliente</span>
            <strong>
                @if ($venta->cliente)
                    {{ $venta->cliente->nombre }} {{ $venta->cliente->apellido }}
                @else
                    Cliente general
                @endif
            </strong>
        </div>

        <div class="detail-item">
            <span>Total</span>
            <strong>${{ number_format($venta->total, 2) }}</strong>
        </div>

        <div class="detail-item">
            <span>Productos vendidos</span>
            <strong>{{ $venta->detalles->sum('cantidad') }}</strong>
        </div>

        <div class="detail-item">
            <span>Fecha de venta</span>
            <strong>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}</strong>
        </div>

        <div class="detail-item">
            <span>Registrada por</span>
            <strong>{{ $venta->vendedor_nombre }}</strong>
        </div>
    </div>
</div>

<div class="content-card content-card-spaced">
    <h3 class="card-title-flush">Productos de la venta</h3>

    <div class="table-responsive" tabindex="0" role="region" aria-label="Productos de la venta">
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($venta->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre ?? 'Producto eliminado' }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

@endsection
