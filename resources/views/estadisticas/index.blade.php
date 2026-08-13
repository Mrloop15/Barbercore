@extends('layouts.app')

@section('title', 'Reportes | BarberCore')
@section('page-title', 'Reportes')

@section('content')
<div class="report-hero">
    <div><span class="agenda-eyebrow">Centro de documentos</span><h3>Genera reportes de operación</h3><p>Selecciona un periodo y descarga la información consolidada en PDF.</p></div>
</div>

<div class="content-card report-builder">
    <div class="filter-panel-head">
        <div class="filter-panel-heading"><span class="filter-panel-icon"><x-icon name="filter" /></span><div><strong class="filter-panel-title">Configurar reporte</strong><span class="filter-panel-subtitle">Define el periodo y el estado antes de exportar.</span></div></div>
        <span class="filter-result-count"><strong>{{ $totalCitas + $totalVentas }}</strong> movimientos</span>
    </div>
    <form method="GET" action="{{ route('estadisticas.index') }}" class="report-filter">
        <div class="form-group"><label for="desde">Desde</label><input type="date" id="desde" name="desde" value="{{ $desde->toDateString() }}"></div>
        <div class="form-group"><label for="hasta">Hasta</label><input type="date" id="hasta" name="hasta" value="{{ $hasta->toDateString() }}"></div>
        <div class="form-group"><label for="estado">Estado de citas</label><select id="estado" name="estado"><option value="todos">Todos</option><option value="pendiente" @selected($estado === 'pendiente')>Pendientes</option><option value="completada" @selected($estado === 'completada')>Completadas</option><option value="cancelada" @selected($estado === 'cancelada')>Canceladas</option></select></div>
        <div class="report-filter-actions"><button class="btn btn-secondary" type="submit"><x-icon name="search" /> Vista previa</button><a class="btn btn-primary" href="{{ route('estadisticas.pdf', ['desde' => $desde->toDateString(), 'hasta' => $hasta->toDateString(), 'estado' => $estado]) }}"><x-icon name="download" /> PDF</a><a class="btn btn-success" href="{{ route('estadisticas.excel', ['desde' => $desde->toDateString(), 'hasta' => $hasta->toDateString(), 'estado' => $estado]) }}"><x-icon name="download" /> Excel</a>@if ($estado !== 'todos' || !$desde->isSameDay(now()->startOfMonth()) || !$hasta->isSameDay(now()->endOfMonth()))<a class="btn filter-clear" href="{{ route('estadisticas.index') }}" title="Restablecer filtros"><x-icon name="close" /><span class="sr-only">Restablecer filtros</span></a>@endif</div>
    </form>
</div>

<div class="content-card report-overview" aria-label="Resumen del reporte">
    <section class="report-overview-item">
        <span class="report-overview-label">Actividad del periodo</span>
        <strong>{{ $totalCitas + $totalVentas }}</strong>
        <div class="report-overview-detail"><span>{{ $totalCitas }} citas</span><span>{{ $totalVentas }} ventas</span></div>
    </section>
    <section class="report-overview-item report-status-overview">
        <span class="report-overview-label">Estado de las citas</span>
        <div class="report-status-list">
            <span><strong>{{ $completadas }}</strong> Completadas</span>
            <span><strong>{{ $pendientes }}</strong> Pendientes</span>
            <span><strong>{{ $canceladas }}</strong> Canceladas</span>
        </div>
    </section>
    <section class="report-overview-item report-income-overview">
        <span class="report-overview-label">Ingresos totales</span>
        <strong>${{ number_format($ingresos, 2) }}</strong>
        <div class="report-overview-detail"><span>Servicios ${{ number_format($ingresosServicios, 2) }}</span><span>Productos ${{ number_format($ingresosProductos, 2) }}</span></div>
    </section>
</div>

<div class="report-sections">
<section class="content-card report-preview">
    <div class="page-actions"><div><h3 class="preview-heading">Vista previa</h3><p class="preview-subtitle">{{ $desde->format('d/m/Y') }} – {{ $hasta->format('d/m/Y') }}</p></div><span class="badge badge-pendiente">{{ $totalCitas }} registros</span></div>
    <div class="table-responsive" tabindex="0" role="region" aria-label="Vista previa del reporte">
    <table><thead><tr><th>Fecha</th><th>Horario</th><th>Cliente</th><th>Servicio</th><th>Barbero</th><th>Estado</th><th>Importe</th></tr></thead><tbody>
        @forelse ($citas as $cita)
            <tr><td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td><td>{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }}</td><td>{{ $cita->cliente->nombre ?? 'Sin cliente' }} {{ $cita->cliente->apellido ?? '' }}</td><td>{{ $cita->servicio->nombre ?? 'Sin servicio' }}</td><td>{{ $cita->barbero->nombre ?? 'Sin asignar' }}</td><td><span class="badge badge-{{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></td><td>${{ number_format($cita->precio, 2) }}</td></tr>
        @empty <tr><td colspan="7">No hay datos para los filtros seleccionados.</td></tr> @endforelse
    </tbody></table>
    </div>
</section>
<section class="content-card report-preview">
    <div class="page-actions"><div><h3 class="preview-heading">Ventas de productos</h3><p class="preview-subtitle">Las ventas se incluyen en los ingresos sin depender del filtro de estado de citas.</p></div><span class="badge badge-completada">{{ $totalVentas }} ventas</span></div>
    <div class="table-responsive" tabindex="0" role="region" aria-label="Ventas de productos del reporte">
    <table><thead><tr><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Registrada por</th><th>Productos</th><th>Importe</th></tr></thead><tbody>
        @forelse ($ventas as $venta)
            <tr><td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td><td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('H:i') }}</td><td>{{ $venta->cliente->nombre ?? 'Cliente general' }} {{ $venta->cliente->apellido ?? '' }}</td><td>{{ $venta->vendedor_nombre }}</td><td>{{ $venta->detalles->map(fn ($detalle) => ($detalle->producto->nombre ?? 'Producto eliminado') . ' x' . $detalle->cantidad)->implode(', ') }}</td><td>${{ number_format($venta->total, 2) }}</td></tr>
        @empty <tr><td colspan="6">No hay ventas de productos para el periodo seleccionado.</td></tr> @endforelse
    </tbody></table>
    </div>
</section>
</div>
@endsection
