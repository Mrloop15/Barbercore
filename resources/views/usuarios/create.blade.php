@extends('layouts.app')

@section('title', 'Agregar usuario | BarberCore')
@section('page-title', 'Agregar usuario')

@section('content')

<div class="content-card">
    <form method="POST" action="{{ route('usuarios.store') }}">
        @csrf

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="form-grid">
            <div class="form-group">
                <label for="nombre">Nombre completo *</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
            </div>

            <div class="form-group">
                <label for="correo">Correo electrónico *</label>
                <input type="email" name="correo" id="correo" value="{{ old('correo') }}" required>
            </div>

            <div class="form-group">
                <label for="rol">Rol *</label>
                <select name="rol" id="rol" required>
                    <option value="">Selecciona una opción</option>
                    <option value="admin" {{ old('rol') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="barbero" {{ old('rol') === 'barbero' ? 'selected' : '' }}>Barbero</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Contraseña *</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar contraseña *</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar usuario</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection