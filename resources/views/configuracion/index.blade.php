@extends('layouts.app')

@section('title', 'Configuración | BarberCore')
@section('page-title', 'Configuración')

@section('content')

<div class="config-grid">
    <div class="content-card">
        <h3 class="config-section-title">Información de la barbería</h3>
        <p class="config-section-description">
            Actualiza los datos generales que identifican a la barbería dentro del sistema.
        </p>

        <form method="POST" action="{{ route('configuracion.barberia') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="form-group">
                <label>Logo actual</label>

                @if ($barberia && $barberia->logo)
                    <img src="{{ asset('storage/' . $barberia->logo) }}" alt="Logo barbería" class="logo-preview">
                @else
                    <div class="logo-placeholder">
                        ✂
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="logo">Cambiar logo</label>
                <input type="file" name="logo" id="logo" accept="image/*">
            </div>

            <div class="form-group">
                <label for="nombre">Nombre de la barbería *</label>
                <input 
                    type="text" 
                    name="nombre" 
                    id="nombre" 
                    value="{{ old('nombre', $barberia->nombre ?? '') }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input 
                    type="text" 
                    name="telefono" 
                    id="telefono" 
                    value="{{ old('telefono', $barberia->telefono ?? '') }}"
                >
            </div>

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <textarea 
                    name="direccion" 
                    id="direccion"
                >{{ old('direccion', $barberia->direccion ?? '') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Guardar barbería
                </button>
            </div>
        </form>
    </div>

    <div>
        <div class="content-card">
            <h3 class="config-section-title">Información del usuario</h3>
            <p class="config-section-description">
                Modifica los datos del usuario que inició sesión.
            </p>

            <form method="POST" action="{{ route('configuracion.usuario') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="usuario_nombre">Nombre *</label>
                    <input 
                        type="text" 
                        name="nombre" 
                        id="usuario_nombre" 
                        value="{{ old('nombre', $usuario->nombre) }}" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="correo">Correo electrónico *</label>
                    <input 
                        type="email" 
                        name="correo" 
                        id="correo" 
                        value="{{ old('correo', $usuario->correo) }}" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    <input 
                        type="text" 
                        value="{{ ucfirst($usuario->rol) }}" 
                        disabled
                    >
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        Guardar usuario
                    </button>
                </div>
            </form>
        </div>

        <div class="content-card" style="margin-top: 22px;">
            <h3 class="config-section-title">Cambiar contraseña</h3>
            <p class="config-section-description">
                Actualiza tu contraseña de acceso al panel administrativo.
            </p>

            <form method="POST" action="{{ route('configuracion.password') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="password_actual">Contraseña actual *</label>
                    <input 
                        type="password" 
                        name="password_actual" 
                        id="password_actual" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Nueva contraseña *</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar nueva contraseña *</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required
                    >
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        Actualizar contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection