@extends('layouts.app')

@section('title', 'Servicios | BarberCore')
@section('page-title', 'Servicios')

@section('content')

@include('servicios._landing_styles')

<div class="content-card">
    <div class="module-tools">
        <div class="filter-panel">
            <div class="filter-panel-head">
                <div class="filter-panel-heading"><span class="filter-panel-icon"><x-icon name="filter" /></span><div><strong class="filter-panel-title">Buscar servicios</strong><span class="filter-panel-subtitle">Localiza rápidamente un servicio del catálogo.</span></div></div>
                <div class="filter-panel-meta">
                    <span class="filter-result-count"><strong>{{ $servicios->total() }}</strong> resultados</span>
                    <div class="module-primary-actions"><a href="{{ route('servicios.create') }}" class="btn module-action-btn"><x-icon name="plus" /> <span>Nuevo servicio</span></a></div>
                </div>
            </div>
            <form method="GET" action="{{ route('servicios.index') }}" class="filter-form">
                <label class="filter-field filter-field-grow"><span class="filter-label">Nombre o descripción</span><span class="filter-search-control"><x-icon name="search" /><input type="search" name="buscar" value="{{ $buscar }}" placeholder="Ej. Corte clásico" autocomplete="off"></span></label>
                <div class="filter-actions"><button type="submit" class="btn btn-secondary"><x-icon name="search" /> Buscar</button>@if (filled($buscar))<a href="{{ route('servicios.index') }}" class="btn filter-clear"><x-icon name="close" /> Limpiar</a>@endif</div>
            </form>
        </div>
    </div>

    <div class="table-responsive" tabindex="0" role="region" aria-label="Listado de servicios">
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
                            <span class="table-image-placeholder" role="img" aria-label="Imagen no disponible" title="Imagen no disponible">
                                <x-icon name="image" />
                            </span>
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

                            <form method="POST" action="{{ route('servicios.destroy', $servicio->id_servicio) }}" data-confirm-title="Eliminar servicio" data-confirm="El servicio {{ $servicio->nombre }} dejará de estar disponible para nuevas citas." data-confirm-text="Sí, eliminar" data-confirm-tone="danger">
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
    </div>

    <div class="pagination">{{ $servicios->links() }}</div>
</div>

@endsection
