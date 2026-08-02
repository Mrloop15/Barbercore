@extends('layouts.app')

@section('title', 'Clientes | BarberCore')
@section('page-title', 'Clientes')

@section('content')

<div class="content-card">
    <div class="module-tools">
        <div class="filter-panel">
            <div class="filter-panel-head">
                <div class="filter-panel-heading">
                    <span class="filter-panel-icon"><x-icon name="filter" /></span>
                    <div><strong class="filter-panel-title">Buscar clientes</strong><span class="filter-panel-subtitle">Consulta por nombre, apellido o teléfono.</span></div>
                </div>
                <div class="filter-panel-meta">
                    <span class="filter-result-count"><strong>{{ $clientes->total() }}</strong> resultados</span>
                    <div class="module-primary-actions"><a href="{{ route('clientes.create') }}" class="btn module-action-btn"><x-icon name="plus" /> <span>Nuevo cliente</span></a></div>
                </div>
            </div>
            <form method="GET" action="{{ route('clientes.index') }}" class="filter-form">
                <label class="filter-field filter-field-grow">
                    <span class="filter-label">Buscar</span>
                    <span class="filter-search-control"><x-icon name="search" /><input type="search" name="buscar" value="{{ $buscar }}" placeholder="Ej. Carlos Ramírez" autocomplete="off"></span>
                </label>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-secondary"><x-icon name="search" /> Buscar</button>
                    @if (filled($buscar))
                        <a href="{{ route('clientes.index') }}" class="btn filter-clear"><x-icon name="close" /> Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Puntos</th>
                <th>Última visita</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($clientes as $cliente)
                <tr>
                    <td>
                        @if ($cliente->foto)
                            <img src="{{ asset('storage/' . $cliente->foto) }}" alt="Foto cliente" class="client-photo">
                        @else
                            <span class="table-image-placeholder" role="img" aria-label="Foto de cliente no disponible" title="Foto no disponible">
                                <x-icon name="image" />
                            </span>
                        @endif
                    </td>

                    <td>
                        <strong>{{ $cliente->nombre }} {{ $cliente->apellido }}</strong>
                        <br>
                        <span style="color: var(--gris); font-size: 13px;">
                            Cumpleaños: 
                            {{ $cliente->cumpleanos ? \Carbon\Carbon::parse($cliente->cumpleanos)->format('d/m/Y') : 'No registrado' }}
                        </span>
                    </td>

                    <td>{{ $cliente->telefono ?? 'No registrado' }}</td>

                    <td>{{ $cliente->puntos }}</td>

                    <td>
                        {{ $cliente->ultima_visita ? \Carbon\Carbon::parse($cliente->ultima_visita)->format('d/m/Y') : 'Sin visitas' }}
                    </td>

                    <td>
                        <div class="actions">
                            <a href="{{ route('clientes.show', $cliente->id_cliente) }}" class="btn btn-secondary btn-icon" title="Ver cliente" aria-label="Ver cliente">
                                <x-icon name="eye" /><span class="sr-only">Ver cliente</span>
                            </a>

                            <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="btn btn-primary btn-icon" title="Editar cliente" aria-label="Editar cliente">
                                <x-icon name="edit" /><span class="sr-only">Editar cliente</span>
                            </a>

                            <form method="POST" action="{{ route('clientes.destroy', $cliente->id_cliente) }}" data-confirm-title="Eliminar cliente" data-confirm="Se eliminará a {{ $cliente->nombre }} {{ $cliente->apellido }} y dejará de aparecer en los listados activos." data-confirm-text="Sí, eliminar" data-confirm-tone="danger">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-icon" title="Eliminar cliente" aria-label="Eliminar cliente">
                                    <x-icon name="trash" /><span class="sr-only">Eliminar cliente</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay clientes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $clientes->links() }}
    </div>
</div>

@endsection
