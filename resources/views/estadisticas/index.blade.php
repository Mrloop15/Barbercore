@extends('layouts.app')

@section('title', 'Reportes | BarberCore')
@section('page-title', 'Reportes')

@section('content')
<div class="report-hero">
    <div><span class="agenda-eyebrow">Centro de documentos</span><h3>Genera reportes de operación</h3><p>Selecciona un periodo y descarga la información consolidada en PDF.</p></div>
</div>

<div class="content-card report-builder">
    <form method="GET" action="{{ route('estadisticas.index') }}" class="report-filter">
        <div class="form-group"><label for="desde">Desde</label><input type="date" id="desde" name="desde" value="{{ $desde->toDateString() }}"></div>
        <div class="form-group"><label for="hasta">Hasta</label><input type="date" id="hasta" name="hasta" value="{{ $hasta->toDateString() }}"></div>
        <div class="form-group"><label for="estado">Estado de citas</label><select id="estado" name="estado"><option value="todos">Todos</option><option value="pendiente" @selected($estado === 'pendiente')>Pendientes</option><option value="completada" @selected($estado === 'completada')>Completadas</option><option value="cancelada" @selected($estado === 'cancelada')>Canceladas</option></select></div>
        <div class="report-filter-actions"><button class="btn btn-secondary" type="submit">Vista previa</button><a class="btn btn-primary" href="{{ route('estadisticas.pdf', ['desde' => $desde->toDateString(), 'hasta' => $hasta->toDateString(), 'estado' => $estado]) }}">Descargar PDF</a><a class="btn btn-success" href="{{ route('estadisticas.excel', ['desde' => $desde->toDateString(), 'hasta' => $hasta->toDateString(), 'estado' => $estado]) }}">Descargar Excel</a></div>
    </form>
</div>

<div class="agenda-summary report-summary">
    <div class="agenda-summary-card"><span>Total de citas</span><strong>{{ $totalCitas }}</strong></div>
    <div class="agenda-summary-card"><span>Completadas</span><strong>{{ $completadas }}</strong></div>
    <div class="agenda-summary-card"><span>Pendientes</span><strong>{{ $pendientes }}</strong></div>
    <div class="agenda-summary-card"><span>Canceladas</span><strong>{{ $canceladas }}</strong></div>
    <div class="agenda-summary-card"><span>Ingresos confirmados</span><strong>${{ number_format($ingresos, 2) }}</strong></div>
</div>

<div class="content-card report-preview">
    <div class="page-actions"><div><h3 style="margin:0;">Vista previa</h3><p style="margin:5px 0 0;color:var(--gris);">{{ $desde->format('d/m/Y') }} – {{ $hasta->format('d/m/Y') }}</p></div><span class="badge badge-pendiente">{{ $totalCitas }} registros</span></div>
    <table><thead><tr><th>Fecha</th><th>Horario</th><th>Cliente</th><th>Servicio</th><th>Barbero</th><th>Estado</th><th>Importe</th></tr></thead><tbody>
        @forelse ($citas as $cita)
            <tr><td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td><td>{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }}</td><td>{{ $cita->cliente->nombre ?? 'Sin cliente' }} {{ $cita->cliente->apellido ?? '' }}</td><td>{{ $cita->servicio->nombre ?? 'Sin servicio' }}</td><td>{{ $cita->barbero->nombre ?? 'Sin asignar' }}</td><td><span class="badge badge-{{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></td><td>${{ number_format($cita->precio, 2) }}</td></tr>
        @empty <tr><td colspan="7">No hay datos para los filtros seleccionados.</td></tr> @endforelse
    </tbody></table>
</div>
@endsection
