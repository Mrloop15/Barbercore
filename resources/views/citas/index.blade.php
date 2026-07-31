@extends('layouts.app')

@section('title', 'Citas | BarberCore')
@section('page-title', 'Citas')

@section('content')

<div class="content-card">
    <div class="page-actions">
        <form method="GET" action="{{ route('citas.index') }}" class="search-form appointment-list-filter">
            <a href="{{ route('citas.index', ['fecha' => \Carbon\Carbon::parse($fecha)->subDay()->toDateString(), 'estado' => $estado]) }}" class="btn btn-secondary btn-icon" title="Día anterior" aria-label="Día anterior"><x-icon name="chevron-left" /><span class="sr-only">Día anterior</span></a>
            <input 
                type="date" 
                name="fecha" 
                value="{{ $fecha }}"
                aria-label="Fecha de las citas"
            >

            <a href="{{ route('citas.index', ['fecha' => \Carbon\Carbon::parse($fecha)->addDay()->toDateString(), 'estado' => $estado]) }}" class="btn btn-secondary btn-icon" title="Día siguiente" aria-label="Día siguiente"><x-icon name="chevron-right" /><span class="sr-only">Día siguiente</span></a>

            <select name="estado">
                <option value="">Todos los estados</option>
                <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                <option value="completada" {{ $estado === 'completada' ? 'selected' : '' }}>Completadas</option>
                <option value="cancelada" {{ $estado === 'cancelada' ? 'selected' : '' }}>Canceladas</option>
            </select>

            <button type="submit" class="btn btn-secondary">
                Filtrar
            </button>
            <a href="{{ route('citas.index', ['fecha' => now()->toDateString(), 'estado' => $estado]) }}" class="btn btn-secondary">Hoy</a>
        </form>

        <a href="{{ route('citas.create') }}" class="btn btn-primary">
            Nueva cita
        </a>
    </div>

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

    <div class="pagination">
        {{ $citas->links() }}
    </div>
</div>

@endsection
