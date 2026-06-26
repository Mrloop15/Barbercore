@extends('layouts.app')

@section('title', 'Agregar recompensa | BarberCore')
@section('page-title', 'Agregar recompensa')

@section('content')

<div class="content-card">
    <form method="POST" action="{{ route('recompensas.store') }}">
        @csrf

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
                    value="{{ old('nombre') }}" 
                    placeholder="Ejemplo: Descuento básico"
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
                    value="{{ old('puntos_requeridos') }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="tipo">Tipo *</label>
                <select name="tipo" id="tipo" required>
                    <option value="">Selecciona un tipo</option>
                    <option value="descuento" {{ old('tipo') === 'descuento' ? 'selected' : '' }}>Descuento</option>
                    <option value="servicio" {{ old('tipo') === 'servicio' ? 'selected' : '' }}>Servicio</option>
                    <option value="producto" {{ old('tipo') === 'producto' ? 'selected' : '' }}>Producto</option>
                    <option value="premium" {{ old('tipo') === 'premium' ? 'selected' : '' }}>Premium</option>
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
                    value="{{ old('valor', 0) }}" 
                    required
                >
            </div>

            <div class="form-group full">
                <label for="descripcion">Descripción</label>
                <textarea 
                    name="descripcion" 
                    id="descripcion"
                    placeholder="Describe qué obtiene el cliente al canjear esta recompensa."
                >{{ old('descripcion') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Guardar recompensa
            </button>

            <a href="{{ route('recompensas.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection