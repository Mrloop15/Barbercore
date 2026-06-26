@extends('layouts.app')

@section('title', 'Detalle de venta | BarberCore')
@section('page-title', 'Detalle de venta')

@section('content')

<div class="content-card">
    <div class="page-actions">
        <div>
            <h3 style="margin: 0;">Venta #{{ $venta->id_venta }}</h3>
            <p style="margin: 6px 0 0; color: var(--gris);">
                Registrada el {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}
            </p>
        </div>

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
    </div>
</div>

<div class="content-card" style="margin-top: 22px;">
    <h3 style="margin-top: 0;">Productos de la venta</h3>

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

@endsection