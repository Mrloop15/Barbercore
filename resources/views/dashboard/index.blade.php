@extends('layouts.app')

@section('title', 'Dashboard | BarberCore')
@section('page-title', 'Dashboard principal')

@section('content')

<div class="dashboard-intro">
    <div>
        <span>Resumen del negocio</span>
        <h3>Todo lo importante, en un vistazo</h3>
    </div>
    <a href="{{ route('citas.create') }}" class="btn btn-primary">Nueva cita</a>
</div>

<div class="stats-grid dashboard-stats">
    <div class="stat-card">
        <span>Clientes registrados</span>
        <h3>{{ $totalClientes }}</h3>
    </div>

    <div class="stat-card">
        <span>Citas de hoy</span>
        <h3>{{ $citasHoy }}</h3>
    </div>

    <div class="stat-card">
        <span>Ingresos del mes</span>
        <h3>${{ number_format($ingresosMes, 2) }}</h3>
    </div>

    <div class="stat-card">
        <span>Productos bajo stock</span>
        <h3>{{ $productosBajoStock }}</h3>
    </div>

</div>

@php
    $totalEstados = max(1, $citasCompletadas + $citasPendientes + $citasCanceladas);
    $porcentajeCompletadas = ($citasCompletadas / $totalEstados) * 100;
    $porcentajePendientes = ($citasPendientes / $totalEstados) * 100;
    $tasaFinalizacion = round(($citasCompletadas / $totalEstados) * 100);
@endphp

<div class="dashboard-charts stats-section">
    <div class="content-card status-chart-card">
        <div><span class="agenda-eyebrow">Rendimiento mensual</span><h3>Estado de las citas</h3></div>
        <div class="donut-layout">
            <div class="status-donut" style="--completed: {{ $porcentajeCompletadas }}%; --pending: {{ $porcentajeCompletadas + $porcentajePendientes }}%;"><div><strong>{{ $tasaFinalizacion }}%</strong><span>finalizadas</span></div></div>
            <div class="chart-legend-list"><span><i class="completed"></i>Completadas <strong>{{ $citasCompletadas }}</strong></span><span><i class="pending"></i>Pendientes <strong>{{ $citasPendientes }}</strong></span><span><i class="cancelled"></i>Canceladas <strong>{{ $citasCanceladas }}</strong></span></div>
        </div>
    </div>
    <div class="content-card income-chart-card">
        <div><span class="agenda-eyebrow">Flujo reciente</span><h3>Ingresos de los últimos 7 días</h3></div>
        <div class="vertical-chart">@foreach ($ingresosPorDia as $dia)<div class="vertical-bar-item"><div class="vertical-bar-track"><div class="vertical-bar" style="height: {{ max(3, ($dia['total'] / $maxIngresosDia) * 100) }}%;" title="${{ number_format($dia['total'],2) }}"></div></div><span>{{ $dia['label'] }}</span></div>@endforeach</div>
    </div>
</div>

<div class="content-card">
    <h3 style="margin-top: 0;">Próximas citas</h3>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Precio</th>
                <th>Estado</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($proximasCitas as $cita)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $cita->hora_inicio }}</td>
                    <td>{{ $cita->cliente->nombre ?? 'Sin cliente' }} {{ $cita->cliente->apellido ?? '' }}</td>
                    <td>{{ $cita->servicio->nombre ?? 'Sin servicio' }}</td>
                    <td>${{ number_format($cita->precio, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $cita->estado }}">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay próximas citas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="stats-two-columns stats-section">
    <div class="content-card"><h3 style="margin-top:0;">Servicios más vendidos</h3><div class="chart-list">
        @forelse ($serviciosMasVendidos as $servicio)
            <div class="chart-item"><div class="chart-label">{{ $servicio->nombre }}</div><div class="chart-bar-bg"><div class="chart-bar" style="width: {{ ($servicio->total / $maxServicios) * 100 }}%;"></div></div><div class="chart-value">{{ $servicio->total }}</div></div>
        @empty <p class="no-action">Sin servicios completados en este periodo.</p> @endforelse
    </div></div>
    <div class="content-card"><h3 style="margin-top:0;">Clientes más frecuentes</h3><div class="chart-list">
        @forelse ($clientesFrecuentes as $cliente)
            <div class="chart-item"><div class="chart-label">{{ $cliente->nombre }} {{ $cliente->apellido }}</div><div class="chart-bar-bg"><div class="chart-bar" style="width: {{ ($cliente->total / $maxClientes) * 100 }}%;"></div></div><div class="chart-value">{{ $cliente->total }}</div></div>
        @empty <p class="no-action">Sin clientes frecuentes en este periodo.</p> @endforelse
    </div></div>
</div>

<div class="content-card stats-section">
    <h3 style="margin-top:0;">Productos más vendidos</h3>
    <div class="chart-list">
        @forelse ($productosVendidos as $producto)
            <div class="chart-item"><div class="chart-label">{{ $producto->nombre }}</div><div class="chart-bar-bg"><div class="chart-bar" style="width: {{ ($producto->total_vendido / $maxProductos) * 100 }}%;"></div></div><div class="chart-value">{{ $producto->total_vendido }}</div></div>
        @empty <p class="no-action">Sin ventas de productos en este periodo.</p> @endforelse
    </div>
</div>

@endsection
