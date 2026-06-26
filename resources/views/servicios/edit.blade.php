@extends('layouts.app')

@section('title', 'Editar servicio | BarberCore')
@section('page-title', 'Editar servicio')

@section('content')

<div class="content-card">
    <form method="POST" action="{{ route('servicios.update', $servicio->id_servicio) }}">
        @csrf
        @method('PUT')

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
                    value="{{ old('nombre', $servicio->nombre) }}" 
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
                    value="{{ old('precio', $servicio->precio) }}" 
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
                    value="{{ old('duracion_minutos', $servicio->duracion_minutos) }}" 
                    required
                >
            </div>

            <div class="form-group full">
                <label for="descripcion">Descripción</label>
                <textarea 
                    name="descripcion" 
                    id="descripcion"
                >{{ old('descripcion', $servicio->descripcion) }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Actualizar servicio
            </button>

            <a href="{{ route('servicios.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection