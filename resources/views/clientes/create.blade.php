@extends('layouts.app')

@section('title', 'Agregar cliente | BarberCore')
@section('page-title', 'Agregar cliente')

@section('content')

<div class="content-card">
    <form method="POST" action="{{ route('clientes.store') }}" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="form-grid">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input 
                    type="text" 
                    name="nombre" 
                    id="nombre" 
                    value="{{ old('nombre') }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input 
                    type="text" 
                    name="apellido" 
                    id="apellido" 
                    value="{{ old('apellido') }}"
                >
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input 
                    type="text" 
                    name="telefono" 
                    id="telefono" 
                    value="{{ old('telefono') }}"
                >
            </div>

            <div class="form-group">
                <label for="cumpleanos">Fecha de cumpleaños</label>
                <input 
                    type="date" 
                    name="cumpleanos" 
                    id="cumpleanos" 
                    value="{{ old('cumpleanos') }}"
                >
            </div>

            <div class="form-group">
                <label for="puntos">Puntos iniciales</label>
                <input 
                    type="number" 
                    name="puntos" 
                    id="puntos" 
                    value="{{ old('puntos', 0) }}" 
                    min="0"
                >
            </div>

            <div class="form-group">
                <label for="ultima_visita">Última visita</label>
                <input 
                    type="date" 
                    name="ultima_visita" 
                    id="ultima_visita" 
                    value="{{ old('ultima_visita') }}"
                >
            </div>

            <div class="form-group full">
                <label for="foto">Foto</label>
                <input 
                    type="file" 
                    name="foto" 
                    id="foto" 
                    accept="image/*"
                >
            </div>

            <div class="form-group full">
                <label for="observaciones">Observaciones</label>
                <textarea 
                    name="observaciones" 
                    id="observaciones"
                    placeholder="Ejemplo: tipo de corte favorito, alergias, preferencias o notas importantes."
                >{{ old('observaciones') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Guardar cliente
            </button>

            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection