@extends('layouts.app')

@section('title', 'Agregar servicio | BarberCore')
@section('page-title', 'Agregar servicio')

@section('content')

<div class="content-card">
    <form method="POST" action="{{ route('servicios.store') }}">
        @csrf

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="form-grid">
            <div class="form-group">
                <label for="nombre">Nombre del servicio *</label>
                <input 
                    type="text" 
                    name="nombre" 
                    id="nombre" 
                    value="{{ old('nombre') }}" 
                    placeholder="Ejemplo: Corte básico"
                    required
                >
            </div>

            <div class="form-group">
                <label for="precio">Precio *</label>
                <input 
                    type="number" 
                    step="0.01" 
                    min="0" 
                    name="precio" 
                    id="precio" 
                    value="{{ old('precio') }}" 
                    placeholder="100.00"
                    required
                >
            </div>

            <div class="form-group">
                <label for="duracion_minutos">Duración aproximada en minutos *</label>
                <input 
                    type="number" 
                    min="1" 
                    name="duracion_minutos" 
                    id="duracion_minutos" 
                    value="{{ old('duracion_minutos', 30) }}" 
                    required
                >
            </div>

            <div class="form-group full">
                <label for="descripcion">Descripción</label>
                <textarea 
                    name="descripcion" 
                    id="descripcion"
                    placeholder="Describe brevemente qué incluye el servicio."
                >{{ old('descripcion') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Guardar servicio
            </button>

            <a href="{{ route('servicios.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection