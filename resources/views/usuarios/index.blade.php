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
    <div class="page-actions">
        <form method="GET" action="{{ route('usuarios.index') }}" class="search-form">
            <input
                type="text"
                name="buscar"
                value="{{ $buscar }}"
                placeholder="Buscar por nombre o correo"
            >

            <select name="rol">
                <option value="todos" {{ $filtroRol === 'todos' ? 'selected' : '' }}>Todos los roles</option>
                <option value="admin" {{ $filtroRol === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="barbero" {{ $filtroRol === 'barbero' ? 'selected' : '' }}>Barbero</option>
            </select>

            <select name="estado">
                <option value="todos" {{ $filtroEstado === 'todos' ? 'selected' : '' }}>Todos los estados</option>
                <option value="activos" {{ $filtroEstado === 'activos' ? 'selected' : '' }}>Activos</option>
                <option value="inactivos" {{ $filtroEstado === 'inactivos' ? 'selected' : '' }}>Inactivos</option>
            </select>

            <button type="submit" class="btn btn-secondary">Filtrar</button>
        </form>

        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            Agregar usuario
        </a>
    </div>

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
                            <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" class="btn btn-primary btn-sm">
                                Editar
                            </a>

                            <form method="POST" action="{{ route('usuarios.estado', $usuario->id_usuario) }}">
                                @csrf
                                @method('PUT')

                                <button type="submit" class="btn {{ $usuario->activo ? 'btn-danger' : 'btn-success' }} btn-sm">
                                    {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
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

    <div class="pagination">
        {{ $usuarios->links() }}
    </div>
</div>

@endsection