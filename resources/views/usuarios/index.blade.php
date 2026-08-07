@extends('layouts.app')

@section('title', 'Usuarios | BarberCore')
@section('page-title', 'Usuarios')

@section('content')

<div class="agenda-summary">
    <div class="agenda-summary-card">
        <span>Total de usuarios</span>
        <strong>{{ $totalUsuarios }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Usuarios activos</span>
        <strong>{{ $usuariosActivos }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Usuarios inactivos</span>
        <strong>{{ $usuariosInactivos }}</strong>
    </div>
</div>

<div class="content-card">
    <div class="module-tools">
        <div class="filter-panel">
            <div class="filter-panel-head">
                <div class="filter-panel-heading"><span class="filter-panel-icon"><x-icon name="filter" /></span><div><strong class="filter-panel-title">Filtrar usuarios</strong><span class="filter-panel-subtitle">Combina la búsqueda con rol y estado.</span></div></div>
                <div class="filter-panel-meta">
                    <span class="filter-result-count"><strong>{{ $usuarios->total() }}</strong> resultados</span>
                    <div class="module-primary-actions"><a href="{{ route('usuarios.create') }}" class="btn module-action-btn"><x-icon name="plus" /> <span>Nuevo usuario</span></a></div>
                </div>
            </div>
            <form method="GET" action="{{ route('usuarios.index') }}" class="filter-form">
                <label class="filter-field filter-field-grow"><span class="filter-label">Nombre o correo</span><span class="filter-search-control"><x-icon name="search" /><input type="search" name="buscar" value="{{ $buscar }}" placeholder="Buscar usuario" autocomplete="off"></span></label>
                <label class="filter-field"><span class="filter-label">Rol</span><select name="rol"><option value="todos" {{ $filtroRol === 'todos' ? 'selected' : '' }}>Todos los roles</option><option value="admin" {{ $filtroRol === 'admin' ? 'selected' : '' }}>Admin</option><option value="barbero" {{ $filtroRol === 'barbero' ? 'selected' : '' }}>Barbero</option></select></label>
                <label class="filter-field"><span class="filter-label">Estado</span><select name="estado"><option value="todos" {{ $filtroEstado === 'todos' ? 'selected' : '' }}>Todos</option><option value="activos" {{ $filtroEstado === 'activos' ? 'selected' : '' }}>Activos</option><option value="inactivos" {{ $filtroEstado === 'inactivos' ? 'selected' : '' }}>Inactivos</option></select></label>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-secondary"><x-icon name="filter" /> Aplicar</button>
                    @if (filled($buscar) || $filtroRol !== 'todos' || $filtroEstado !== 'todos')<a href="{{ route('usuarios.index') }}" class="btn filter-clear" title="Limpiar filtros"><x-icon name="close" /><span class="sr-only">Limpiar filtros</span></a>@endif
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive" tabindex="0" role="region" aria-label="Listado de usuarios">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->correo }}</td>
                    <td>
                        <span class="badge badge-pendiente">
                            {{ ucfirst($usuario->rol) }}
                        </span>
                    </td>
                    <td>
                        @if ($usuario->activo)
                            <span class="badge badge-completada">Activo</span>
                        @else
                            <span class="badge badge-cancelada">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" class="btn btn-primary btn-icon" title="Editar usuario" aria-label="Editar usuario">
                                <x-icon name="edit" /><span class="sr-only">Editar usuario</span>
                            </a>

                            <form method="POST" action="{{ route('usuarios.estado', $usuario->id_usuario) }}">
                                @csrf
                                @method('PUT')

                                <button type="submit" class="btn {{ $usuario->activo ? 'btn-danger' : 'btn-success' }} btn-icon" title="{{ $usuario->activo ? 'Desactivar usuario' : 'Activar usuario' }}" aria-label="{{ $usuario->activo ? 'Desactivar usuario' : 'Activar usuario' }}">
                                    <x-icon name="power" /><span class="sr-only">{{ $usuario->activo ? 'Desactivar usuario' : 'Activar usuario' }}</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay usuarios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="pagination">
        {{ $usuarios->links() }}
    </div>
</div>

@endsection
