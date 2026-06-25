@extends('layouts.app')

@section('title', 'Editar cliente | BarberCore')
@section('page-title', 'Editar cliente')

@section('content')

<div class="content-card">
    <form method="POST" action="{{ route('clientes.update', $cliente->id_cliente) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                    value="{{ old('nombre', $cliente->nombre) }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input 
                    type="text" 
                    name="apellido" 
                    id="apellido" 
                    value="{{ old('apellido', $cliente->apellido) }}"
                >
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input 
                    type="text" 
                    name="telefono" 
                    id="telefono" 
                    value="{{ old('telefono', $cliente->telefono) }}"
                >
            </div>

            <div class="form-group">
                <label for="cumpleanos">Fecha de cumpleaños</label>
                <input 
                    type="date" 
                    name="cumpleanos" 
                    id="cumpleanos" 
                    value="{{ old('cumpleanos', $cliente->cumpleanos) }}"
                >
            </div>

            <div class="form-group">
                <label for="puntos">Puntos</label>
                <input 
                    type="number" 
                    name="puntos" 
                    id="puntos" 
                    value="{{ old('puntos', $cliente->puntos) }}" 
                    min="0"
                >
            </div>

            <div class="form-group">
                <label for="ultima_visita">Última visita</label>
                <input 
                    type="date" 
                    name="ultima_visita" 
                    id="ultima_visita" 
                    value="{{ old('ultima_visita', $cliente->ultima_visita) }}"
                >
            </div>

            <div class="form-group full">
                <label>Foto actual</label>

                @if ($cliente->foto)
                    <img src="{{ asset('storage/' . $cliente->foto) }}" alt="Foto cliente" class="client-photo">
                @else
                    <p style="color: var(--gris); margin: 0;">Sin foto registrada.</p>
                @endif
            </div>

            <div class="form-group full">
                <label for="foto">Cambiar foto</label>
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
                >{{ old('observaciones', $cliente->observaciones) }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Actualizar cliente
            </button>

            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection