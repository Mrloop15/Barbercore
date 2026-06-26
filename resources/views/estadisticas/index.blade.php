@extends('layouts.app')

@section('title', 'Estadísticas | BarberCore')
@section('page-title', 'Estadísticas')

@section('content')

<div class="content-card">
    <div class="page-actions">
        <div>
            <h3 style="margin: 0;">Resumen estadístico</h3>
            <p style="margin: 6px 0 0; color: var(--gris);">
                Información del {{ $inicioMes->format('d/m/Y') }} al {{ $finMes->format('d/m/Y') }}.
            </p>
        </div>

        <form method="GET" action="{{ route('estadisticas.index') }}" class="month-filter">
            <input 
                type="month" 
                name="mes" 
                value="{{ $mesSeleccionado }}"
            >

            <button type="submit" class="btn btn-secondary">
                Consultar
            </button>
        </form>
    </div>
</div>

<div class="agenda-summary stats-section">
    <div class="agenda-summary-card">
        <span>Ingresos del mes</span>
        <strong>${{ number_format($ingresosMes, 2) }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Ingresos de hoy</span>
        <strong>${{ number_format($ingresosHoy, 2) }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Citas de hoy</span>
        <strong>{{ $citasHoy }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Citas completadas</span>
        <strong>{{ $citasCompletadas }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Citas pendientes</span>
        <strong>{{ $citasPendientes }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Citas canceladas</span>
        <strong>{{ $citasCanceladas }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Clientes registrados</span>
        <strong>{{ $totalClientes }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Clientes inactivos</span>
        <strong>{{ $clientesInactivos }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Productos bajo stock</span>
        <strong>{{ $productosBajoStock }}</strong>
    </div>
</div>

<div class="content-card stats-section">
    <h3 style="margin-top: 0;">Ingresos de los últimos 7 días</h3>

    <div class="chart-list">
        @foreach ($ingresosPorDia as $dia)
            @php
                $porcentaje = ($dia['total'] / $maxIngresosDia) * 100;
            @endphp

            <div class="chart-item">
                <div class="chart-label">{{ $dia['label'] }}</div>

                <div class="chart-bar-bg">
                    <div class="chart-bar" style="width: {{ $porcentaje }}%;"></div>
                </div>

                <div class="chart-value">
                    ${{ number_format($dia['total'], 2) }}
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="stats-two-columns stats-section">
    <div class="content-card">
        <h3 style="margin-top: 0;">Servicios más vendidos</h3>

        <div class="chart-list">
            @forelse ($serviciosMasVendidos as $servicio)
                @php
                    $porcentaje = ($servicio->total / $maxServicios) * 100;
                @endphp

                <div class="chart-item">
                    <div class="chart-label">{{ $servicio->nombre }}</div>

                    <div class="chart-bar-bg">
                        <div class="chart-bar" style="width: {{ $porcentaje }}%;"></div>
                    </div>

                    <div class="chart-value">
                        {{ $servicio->total }}
                    </div>
                </div>
            @empty
                <p style="color: var(--gris); margin: 0;">
                    No hay servicios completados en este periodo.
                </p>
            @endforelse
        </div>
    </div>

    <div class="content-card">
        <h3 style="margin-top: 0;">Clientes más frecuentes</h3>

        <div class="chart-list">
            @forelse ($clientesFrecuentes as $cliente)
                @php
                    $porcentaje = ($cliente->total / $maxClientes) * 100;
                @endphp

                <div class="chart-item">
                    <div class="chart-label">
                        {{ $cliente->nombre }} {{ $cliente->apellido }}
                    </div>

                    <div class="chart-bar-bg">
                        <div class="chart-bar" style="width: {{ $porcentaje }}%;"></div>
                    </div>

                    <div class="chart-value">
                        {{ $cliente->total }}
                    </div>
                </div>
            @empty
                <p style="color: var(--gris); margin: 0;">
                    No hay clientes frecuentes en este periodo.
                </p>
            @endforelse
        </div>
    </div>
</div>

<div class="content-card stats-section">
    <h3 style="margin-top: 0;">Productos más vendidos</h3>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad vendida</th>
                <th>Ingresos generados</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($productosVendidos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->total_vendido }}</td>
                    <td>${{ number_format($producto->ingresos, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Aún no hay ventas de productos registradas en este periodo.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection