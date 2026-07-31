@extends('layouts.app')

@section('title', 'Servicios | BarberCore')
@section('page-title', 'Servicios')

@section('content')

@include('servicios._landing_styles')

<div class="content-card">
    <div class="page-actions">
        <form method="GET" action="{{ route('servicios.index') }}" class="search-form">
            <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar servicio por nombre o descripción">
            <button type="submit" class="btn btn-secondary">Buscar</button>
        </form>

        <a href="{{ route('servicios.create') }}" class="btn btn-primary">Agregar servicio</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Servicio</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Duración</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($servicios as $servicio)
                <tr>
                    <td>
                        @if ($servicio->imagen)
                            <img class="client-photo" src="{{ asset('storage/' . $servicio->imagen) }}" alt="{{ $servicio->nombre }}">
                        @else
                            <span class="service-image-empty" style="width: 54px; height: 54px;">Sin imagen</span>
                        @endif
                    </td>
                    <td><strong>{{ $servicio->nombre }}</strong></td>
                    <td>{{ $servicio->descripcion ?? 'Sin descripción' }}</td>
                    <td>${{ number_format($servicio->precio, 2) }}</td>
                    <td>{{ $servicio->duracion_minutos }} min</td>
                    <td><span class="badge badge-completada">Activo</span></td>
                    <td>
                        <div class="actions">
                            <form method="POST" action="{{ route('servicios.landing', $servicio->id_servicio) }}">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="landing-switch-button {{ $servicio->mostrar_landing ? 'is-active' : '' }}"
                                    role="switch"
                                    aria-checked="{{ $servicio->mostrar_landing ? 'true' : 'false' }}"
                                    title="{{ $servicio->mostrar_landing ? 'Ocultar de la landing' : 'Mostrar en la landing' }}"
                                >
                                    <span class="switch-track" aria-hidden="true"></span>
                                    <span class="sr-only">{{ $servicio->mostrar_landing ? 'Ocultar de la landing' : 'Mostrar en la landing' }}</span>
                                </button>
                            </form>

                            <a href="{{ route('servicios.edit', $servicio->id_servicio) }}" class="btn btn-primary btn-icon" title="Editar servicio" aria-label="Editar servicio">
                                <x-icon name="edit" /><span class="sr-only">Editar servicio</span>
                            </a>

                            <form method="POST" action="{{ route('servicios.destroy', $servicio->id_servicio) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este servicio?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon" title="Eliminar servicio" aria-label="Eliminar servicio">
                                    <x-icon name="trash" /><span class="sr-only">Eliminar servicio</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No hay servicios registrados.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">{{ $servicios->links() }}</div>
</div>

@endsection
