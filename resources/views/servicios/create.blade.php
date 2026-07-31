@extends('layouts.app')

@section('title', 'Agregar servicio | BarberCore')
@section('page-title', 'Agregar servicio')

@section('content')

@include('servicios._landing_styles')

<div class="content-card">
    <form method="POST" action="{{ route('servicios.store') }}" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <div class="service-form-layout">
            <div class="form-grid service-fields">
                <div class="form-group full">
                    <label for="nombre">Nombre del servicio *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" placeholder="Ejemplo: Corte básico" required>
                </div>

                <div class="form-group">
                    <label for="precio">Precio *</label>
                    <input type="number" step="0.01" min="0" name="precio" id="precio" value="{{ old('precio') }}" placeholder="100.00" required>
                </div>

                <div class="form-group">
                    <label for="duracion_minutos">Duración en minutos *</label>
                    <input type="number" min="1" name="duracion_minutos" id="duracion_minutos" value="{{ old('duracion_minutos', 30) }}" required>
                </div>

                <div class="form-group full">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" id="descripcion" placeholder="Describe brevemente qué incluye el servicio.">{{ old('descripcion') }}</textarea>
                </div>

                <div class="form-group full">
                    <label class="service-landing-option" for="mostrar_landing">
                        <span>
                            <strong>Mostrar en la landing page</strong>
                            <small>El servicio aparecerá en la página principal mientras esté activado.</small>
                        </span>
                        <input type="checkbox" name="mostrar_landing" id="mostrar_landing" value="1" @checked(old('mostrar_landing', true))>
                        <span class="switch-track" aria-hidden="true"></span>
                    </label>
                </div>
            </div>

            <aside class="service-image-panel">
                <div class="service-image-panel-header">
                    <label for="imagen">Imagen del servicio</label>
                    <span>Se utilizará en la tarjeta de la landing.</span>
                </div>
                <div class="service-image-empty" data-service-image-empty>
                    <x-icon name="eye" />
                    <span>Sin imagen seleccionada</span>
                </div>
                <img class="service-image-preview" data-service-image-preview src="" alt="" hidden>
                <input type="file" name="imagen" id="imagen" accept="image/jpeg,image/png,image/webp" data-service-image-input>
                <small>JPG, PNG o WebP. Máximo 4 MB.</small>
            </aside>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar servicio</button>
            <a href="{{ route('servicios.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection
