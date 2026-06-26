@extends('layouts.app')

@section('title', 'Editar recompensa | BarberCore')
@section('page-title', 'Editar recompensa')

@section('content')

<div class="content-card">
    <form method="POST" action="{{ route('recompensas.update', $recompensa->id_recompensa) }}">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="form-grid">
            <div class="form-group">
                <label for="nombre">Nombre de la recompensa *</label>
                <input 
                    type="text" 
                    name="nombre" 
                    id="nombre" 
                    value="{{ old('nombre', $recompensa->nombre) }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="puntos_requeridos">Puntos requeridos *</label>
                <input 
                    type="number" 
                    min="1" 
                    name="puntos_requeridos" 
                    id="puntos_requeridos" 
                    value="{{ old('puntos_requeridos', $recompensa->puntos_requeridos) }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="tipo">Tipo *</label>
                <select name="tipo" id="tipo" required>
                    <option value="descuento" {{ old('tipo', $recompensa->tipo) === 'descuento' ? 'selected' : '' }}>Descuento</option>
                    <option value="servicio" {{ old('tipo', $recompensa->tipo) === 'servicio' ? 'selected' : '' }}>Servicio</option>
                    <option value="producto" {{ old('tipo', $recompensa->tipo) === 'producto' ? 'selected' : '' }}>Producto</option>
                    <option value="premium" {{ old('tipo', $recompensa->tipo) === 'premium' ? 'selected' : '' }}>Premium</option>
                </select>
            </div>

            <div class="form-group">
                <label for="valor">Valor *</label>
                <input 
                    type="number" 
                    step="0.01" 
                    min="0" 
                    name="valor" 
                    id="valor" 
                    value="{{ old('valor', $recompensa->valor) }}" 
                    required
                >
            </div>

            <div class="form-group full">
                <label for="descripcion">Descripción</label>
                <textarea 
                    name="descripcion" 
                    id="descripcion"
                >{{ old('descripcion', $recompensa->descripcion) }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Actualizar recompensa
            </button>

            <a href="{{ route('recompensas.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection