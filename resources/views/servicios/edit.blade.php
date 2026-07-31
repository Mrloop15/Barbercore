@extends('layouts.app')

@section('title', 'Editar servicio | BarberCore')
@section('page-title', 'Editar servicio')

@section('content')

@include('servicios._landing_styles')

<div class="content-card">
    <form method="POST" action="{{ route('servicios.update', $servicio->id_servicio) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <div class="service-form-layout">
            <div class="form-grid service-fields">
                <div class="form-group full">
                    <label for="nombre">Nombre del servicio *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $servicio->nombre) }}" required>
                </div>

                <div class="form-group">
                    <label for="precio">Precio *</label>
                    <input type="number" step="0.01" min="0" name="precio" id="precio" value="{{ old('precio', $servicio->precio) }}" required>
                </div>

                <div class="form-group">
                    <label for="duracion_minutos">Duración en minutos *</label>
                    <input type="number" min="1" name="duracion_minutos" id="duracion_minutos" value="{{ old('duracion_minutos', $servicio->duracion_minutos) }}" required>
                </div>

                <div class="form-group full">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" id="descripcion">{{ old('descripcion', $servicio->descripcion) }}</textarea>
                </div>

                <div class="form-group full">
                    <label class="service-landing-option" for="mostrar_landing">
                        <span>
                            <strong>Mostrar en la landing page</strong>
                            <small>Activa o desactiva la publicación de este servicio en la página principal.</small>
                        </span>
                        <input type="checkbox" name="mostrar_landing" id="mostrar_landing" value="1" @checked(old('mostrar_landing', $servicio->mostrar_landing))>
                        <span class="switch-track" aria-hidden="true"></span>
                    </label>
                </div>
            </div>

            <aside class="service-image-panel">
                <div class="service-image-panel-header">
                    <label for="imagen">Imagen del servicio</label>
                    <span>Vista actual de la tarjeta en la landing.</span>
                </div>
                @if ($servicio->imagen)
                    <img class="service-image-preview" data-service-image-preview src="{{ asset('storage/' . $servicio->imagen) }}" alt="Imagen actual de {{ $servicio->nombre }}">
                @else
                    <div class="service-image-empty" data-service-image-empty>
                        <x-icon name="eye" />
                        <span>Este servicio no tiene imagen</span>
                    </div>
                    <img class="service-image-preview" data-service-image-preview src="" alt="" hidden>
                @endif
                <input type="file" name="imagen" id="imagen" accept="image/jpeg,image/png,image/webp" data-service-image-input>
                <small>Selecciona un archivo solo si deseas reemplazar la imagen. Máximo 4 MB.</small>
            </aside>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar servicio</button>
            <a href="{{ route('servicios.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection
