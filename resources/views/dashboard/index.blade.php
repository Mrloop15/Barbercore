@extends('layouts.app')

@section('title', 'Dashboard | BarberCore')
@section('page-title', 'Dashboard principal')

@section('content')

<div class="dashboard-intro">
    <div>
        <span>Resumen del negocio</span>
        <h3>Todo lo importante, en un vistazo</h3>
    </div>
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
    $cantidadDiasIngreso = max(1, count($ingresosPorDia));
    $puntosIngreso = collect($ingresosPorDia)->values()->map(function ($dia, $indice) use ($cantidadDiasIngreso, $maxIngresosDia) {
        $x = $cantidadDiasIngreso === 1 ? 50 : 4 + (($indice / ($cantidadDiasIngreso - 1)) * 92);
        $proporcion = min(1, max(0, (float) $dia['total'] / $maxIngresosDia));

        return [
            'label' => $dia['label'],
            'total' => (float) $dia['total'],
            'x' => round($x, 2),
            'y' => round(90 - ($proporcion * 76), 2),
        ];
    });
    $lineaIngresos = $puntosIngreso->map(fn ($punto) => $punto['x'].','.$punto['y'])->implode(' ');
    $areaIngresos = $puntosIngreso->isEmpty()
        ? ''
        : $puntosIngreso->first()['x'].',94 '.$lineaIngresos.' '.$puntosIngreso->last()['x'].',94';
@endphp

<div class="dashboard-charts stats-section">
    <div class="content-card status-chart-card dashboard-chart-reveal" data-chart-reveal>
        <div><span class="agenda-eyebrow">Rendimiento mensual</span><h3>Estado de las citas</h3></div>
        <div class="donut-layout">
            <div class="status-donut" style="--completed: {{ $porcentajeCompletadas }}%; --pending: {{ $porcentajeCompletadas + $porcentajePendientes }}%;"><div><strong>{{ $tasaFinalizacion }}%</strong><span>finalizadas</span></div></div>
            <div class="chart-legend-list"><span><i class="completed"></i>Completadas <strong>{{ $citasCompletadas }}</strong></span><span><i class="pending"></i>Pendientes <strong>{{ $citasPendientes }}</strong></span><span><i class="cancelled"></i>Canceladas <strong>{{ $citasCanceladas }}</strong></span></div>
        </div>
    </div>
    <div class="content-card income-chart-card dashboard-chart-reveal" data-chart-reveal>
        <div><span class="agenda-eyebrow">Flujo reciente</span><h3>Ingresos de los últimos 7 días</h3></div>
        <div class="income-area-chart">
            <div class="income-area-plot" role="img" aria-label="Ingresos de los últimos siete días">
                <svg viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true" focusable="false">
                    <defs>
                        <linearGradient id="incomeAreaGold" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" class="income-area-stop income-area-stop-strong" />
                            <stop offset="100%" class="income-area-stop income-area-stop-soft" />
                        </linearGradient>
                    </defs>
                    <line class="income-area-grid" x1="0" y1="14" x2="100" y2="14" />
                    <line class="income-area-grid" x1="0" y1="39" x2="100" y2="39" />
                    <line class="income-area-grid" x1="0" y1="64" x2="100" y2="64" />
                    <line class="income-area-grid" x1="0" y1="90" x2="100" y2="90" />
                    <polygon class="income-area-fill" points="{{ $areaIngresos }}" />
                    <polyline class="income-area-line" points="{{ $lineaIngresos }}" />
                </svg>

                @foreach ($puntosIngreso as $punto)
                    <span
                        class="income-area-point"
                        style="--point-x: {{ $punto['x'] }}%; --point-y: {{ $punto['y'] }}%; --reveal-delay: {{ 700 + ($loop->index * 280) }}ms;"
                        title="{{ $punto['label'] }}: ${{ number_format($punto['total'], 2) }}"
                        aria-hidden="true"
                    ></span>
                @endforeach
            </div>

            <div class="income-area-axis" aria-hidden="true">
                @foreach ($puntosIngreso as $punto)<span>{{ $punto['label'] }}</span>@endforeach
            </div>

            <ul class="sr-only">
                @foreach ($puntosIngreso as $punto)
                    <li>{{ $punto['label'] }}: ${{ number_format($punto['total'], 2) }}.</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="content-card">
    <h3 class="card-title-flush">Próximas citas</h3>

    <div class="table-responsive" tabindex="0" role="region" aria-label="Citas recientes">
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
</div>

<div class="stats-two-columns stats-section">
    <div class="content-card dashboard-chart-reveal" data-chart-reveal><h3 class="card-title-flush">Servicios más vendidos</h3><div class="chart-list">
        @forelse ($serviciosMasVendidos as $servicio)
            <div class="chart-item"><div class="chart-label">{{ $servicio->nombre }}</div><div class="chart-bar-bg"><div class="chart-bar" style="width: {{ ($servicio->total / $maxServicios) * 100 }}%; --reveal-delay: {{ $loop->index * 170 }}ms;"></div></div><div class="chart-value">{{ $servicio->total }}</div></div>
        @empty <p class="no-action">Sin servicios completados en este periodo.</p> @endforelse
    </div></div>
    <div class="content-card frequent-clients-panel dashboard-chart-reveal" data-chart-reveal>
        <div class="client-cards-heading">
            <h3 class="card-title-flush">Clientes más frecuentes</h3>
            <span>Visitas del mes</span>
        </div>

        @if ($clientesFrecuentes->isNotEmpty())
            <div
                class="client-card-scroll"
                tabindex="0"
                role="region"
                aria-label="Clientes con más visitas este mes"
            >
                <ol class="client-card-track">
                    @foreach ($clientesFrecuentes->take(5) as $cliente)
                        @php
                            $nombreCliente = trim($cliente->nombre.' '.$cliente->apellido);
                            $inicialesCliente = mb_strtoupper(
                                mb_substr(trim($cliente->nombre), 0, 1).
                                mb_substr(trim((string) $cliente->apellido), 0, 1)
                            );
                        @endphp
                        <li class="client-rank-card client-rank-card--{{ $loop->iteration }}" style="--reveal-delay: {{ $loop->index * 170 }}ms;">
                            <span class="client-rank-avatar" aria-hidden="true">
                                @if ($cliente->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($cliente->foto))
                                    <img src="{{ asset('storage/' . $cliente->foto) }}" alt="">
                                @else
                                    {{ $inicialesCliente ?: 'BC' }}
                                @endif
                            </span>
                            <strong class="client-rank-name">{{ $nombreCliente }}</strong>
                            <div class="client-rank-visits">
                                <strong>{{ $cliente->total }}</strong>
                                <span>{{ (int) $cliente->total === 1 ? 'visita' : 'visitas' }}</span>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        @else
            <p class="no-action">Sin clientes frecuentes en este periodo.</p>
        @endif
    </div>
</div>

<div class="content-card stats-section dashboard-chart-reveal" data-chart-reveal>
    <h3 class="card-title-flush">Productos más vendidos</h3>
    <div class="chart-list">
        @forelse ($productosVendidos as $producto)
            <div class="chart-item"><div class="chart-label">{{ $producto->nombre }}</div><div class="chart-bar-bg"><div class="chart-bar product-sales-bar" style="width: {{ ($producto->total_vendido / $maxProductos) * 100 }}%; --reveal-delay: {{ $loop->index * 170 }}ms;"></div></div><div class="chart-value">{{ $producto->total_vendido }}</div></div>
        @empty <p class="no-action">Sin ventas de productos en este periodo.</p> @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/dashboard-charts.js') }}?v=1"></script>
@endpush
