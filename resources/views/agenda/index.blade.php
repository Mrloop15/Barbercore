@extends('layouts.app')

@section('title', 'Agenda | BarberCore')
@section('page-title', 'Agenda')

@section('content')

<div class="content-card">
    <div class="agenda-header">
        <div>
            <h3 style="margin: 0;">{{ $tituloPeriodo }}</h3>
            <p style="margin: 6px 0 0; color: var(--gris);">
                Consulta las citas por día, semana o mes.
            </p>
        </div>

        <a href="{{ route('citas.create') }}" class="btn btn-primary">
            Nueva cita
        </a>
    </div>

    <div class="agenda-header">
        <div class="agenda-tabs">
            <a 
                href="{{ route('agenda.index', ['vista' => 'dia', 'fecha' => $fechaBase->toDateString()]) }}" 
                class="agenda-tab {{ $vista === 'dia' ? 'active' : '' }}"
            >
                Vista diaria
            </a>

            <a 
                href="{{ route('agenda.index', ['vista' => 'semana', 'fecha' => $fechaBase->toDateString()]) }}" 
                class="agenda-tab {{ $vista === 'semana' ? 'active' : '' }}"
            >
                Vista semanal
            </a>

            <a 
                href="{{ route('agenda.index', ['vista' => 'mes', 'fecha' => $fechaBase->toDateString()]) }}" 
                class="agenda-tab {{ $vista === 'mes' ? 'active' : '' }}"
            >
                Vista mensual
            </a>
        </div>

        <form method="GET" action="{{ route('agenda.index') }}" class="agenda-filter">
            <input type="hidden" name="vista" value="{{ $vista }}">

            <input 
                type="date" 
                name="fecha" 
                value="{{ $fechaBase->toDateString() }}"
            >

            <button type="submit" class="btn btn-secondary">
                Consultar
            </button>
        </form>
    </div>

    <div class="agenda-summary">
        <div class="agenda-summary-card">
            <span>Total de citas</span>
            <strong>{{ $totalCitas }}</strong>
        </div>

        <div class="agenda-summary-card">
            <span>Pendientes</span>
            <strong>{{ $pendientes }}</strong>
        </div>

        <div class="agenda-summary-card">
            <span>Completadas</span>
            <strong>{{ $completadas }}</strong>
        </div>

        <div class="agenda-summary-card">
            <span>Canceladas</span>
            <strong>{{ $canceladas }}</strong>
        </div>

        <div class="agenda-summary-card">
            <span>Ingresos estimados</span>
            <strong>${{ number_format($ingresosEstimados, 2) }}</strong>
        </div>

        <div class="agenda-summary-card">
            <span>Ingresos generados</span>
            <strong>${{ number_format($ingresosGenerados, 2) }}</strong>
        </div>
    </div>

    @forelse ($citasAgrupadas as $fecha => $citasDelDia)
        <div class="agenda-day-group">
            <div class="agenda-day-title">
                {{ \Carbon\Carbon::parse($fecha)->translatedFormat('l d/m/Y') }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Horario</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Barbero</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($citasDelDia as $cita)
                        <tr>
                            <td>
                                <span class="agenda-time">
                                    {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($cita->hora_fin)->format('H:i') }}
                                </span>
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
                                @if ($cita->estado === 'pendiente')
                                    <a href="{{ route('citas.edit', $cita->id_cita) }}" class="btn btn-primary btn-sm">
                                        Editar
                                    </a>
                                @else
                                    <span style="color: var(--gris); font-size: 13px;">
                                        Sin acción
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div class="warning-box">
            No hay citas registradas para el periodo seleccionado.
        </div>
    @endforelse
</div>

@endsection