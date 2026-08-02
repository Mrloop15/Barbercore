@extends('layouts.app')

@section('title', 'Citas | BarberCore')
@section('page-title', 'Citas')

@section('content')

<div class="content-card appointment-list-card">
    <div class="module-tools">
        <div class="filter-panel">
            <div class="filter-panel-head">
                <div class="filter-panel-heading"><span class="filter-panel-icon"><x-icon name="calendar" /></span><div><strong class="filter-panel-title">Filtrar citas</strong><span class="filter-panel-subtitle">Consulta un periodo y limita los resultados por estado.</span></div></div>
                <div class="filter-panel-meta">
                    <span class="filter-result-count"><strong>{{ $citas->total() }}</strong> resultados</span>
                    <div class="module-primary-actions"><a href="{{ route('citas.create') }}" class="btn module-action-btn"><x-icon name="plus" /> <span>Nueva cita</span></a></div>
                </div>
            </div>
            <form method="GET" action="{{ route('citas.index') }}" class="filter-form appointment-list-filter">
                <input type="hidden" name="periodo" value="rango">
                <label class="filter-field filter-field-date"><span class="filter-label">Desde</span><input type="date" name="desde" value="{{ $desde->toDateString() }}"></label>
                <label class="filter-field filter-field-date"><span class="filter-label">Hasta</span><input type="date" name="hasta" value="{{ $hasta->toDateString() }}"></label>
                <label class="filter-field"><span class="filter-label">Estado</span><select name="estado"><option value="">Todos los estados</option><option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendientes</option><option value="completada" {{ $estado === 'completada' ? 'selected' : '' }}>Completadas</option><option value="cancelada" {{ $estado === 'cancelada' ? 'selected' : '' }}>Canceladas</option></select></label>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-secondary"><x-icon name="filter" /> Aplicar</button>
                    <a href="{{ route('citas.index', ['periodo' => 'hoy', 'estado' => $estado]) }}" class="btn {{ $periodo === 'hoy' ? 'btn-primary' : 'filter-clear' }}">Hoy</a>
                    @if (filled($estado))<a href="{{ route('citas.index', ['periodo' => $periodo, 'desde' => $desde->toDateString(), 'hasta' => $hasta->toDateString()]) }}" class="btn filter-clear" title="Quitar estado"><x-icon name="close" /><span class="sr-only">Quitar filtro de estado</span></a>@endif
                </div>
            </form>
        </div>
    </div>

    <div class="range-indicator">
        <span>{{ $periodo === 'hoy' ? 'Citas de hoy' : 'Periodo visible' }}</span>
        <strong>{{ $desde->format('d/m/Y') }}{{ $periodo === 'rango' ? ' – ' . $hasta->format('d/m/Y') : '' }}</strong>
    </div>

    <div class="appointment-table-scroll">
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Horario</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Barbero</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($citas as $cita)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }}
                        -
                        {{ \Carbon\Carbon::parse($cita->hora_fin)->format('H:i') }}
                    </td>

                    <td>
                        <strong>
                            {{ $cita->cliente->nombre ?? 'Sin cliente' }}
                            {{ $cita->cliente->apellido ?? '' }}
                        </strong>
                    </td>

                    <td>{{ $cita->servicio->nombre ?? 'Sin servicio' }}</td>

                    <td>{{ $cita->barbero->nombre ?? 'Sin asignar' }}</td>

                    <td>${{ number_format($cita->precio, 2) }}</td>

                    <td>
                        <span class="badge badge-{{ $cita->estado }}">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </td>

                    <td>
                        <div class="actions">
                            @if ($cita->estado === 'pendiente')
                                <a href="{{ route('citas.edit', $cita->id_cita) }}" class="btn btn-primary btn-icon" title="Editar cita" aria-label="Editar cita">
                                    <x-icon name="edit" /><span class="sr-only">Editar cita</span>
                                </a>

                                <form method="POST" action="{{ route('citas.completar', $cita->id_cita) }}" onsubmit="return confirm('¿Deseas finalizar esta cita?');">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit" class="btn btn-success btn-icon" title="Completar cita" aria-label="Completar cita">
                                        <x-icon name="check" /><span class="sr-only">Completar cita</span>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('citas.cancelar', $cita->id_cita) }}" onsubmit="return confirm('¿Seguro que deseas cancelar esta cita?');">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit" class="btn btn-danger btn-icon" title="Cancelar cita" aria-label="Cancelar cita">
                                        <x-icon name="close" /><span class="sr-only">Cancelar cita</span>
                                    </button>
                                </form>
                            @else
                                <span style="color: var(--gris); font-size: 13px;">
                                    Sin acciones
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No hay citas registradas con los filtros seleccionados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="pagination">
        {{ $citas->links() }}
    </div>
</div>

@endsection
