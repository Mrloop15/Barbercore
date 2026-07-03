@extends('layouts.app')

@section('title', 'Editar usuario | BarberCore')
@section('page-title', 'Editar usuario')

@section('content')

<div class="content-card">
    <form method="POST" action="{{ route('usuarios.update', $usuario->id_usuario) }}">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="form-grid">
            <div class="form-group">
                <label for="nombre">Nombre completo *</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
            </div>

            <div class="form-group">
                <label for="correo">Correo electrónico *</label>
                <input type="email" name="correo" id="correo" value="{{ old('correo', $usuario->correo) }}" required>
            </div>

            <div class="form-group">
                <label for="rol">Rol *</label>
                <select name="rol" id="rol" required>
                    <option value="admin" {{ old('rol', $usuario->rol) === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="barbero" {{ old('rol', $usuario->rol) === 'barbero' ? 'selected' : '' }}>Barbero</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Nueva contraseña</label>
                <input type="password" name="password" id="password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar usuario</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection