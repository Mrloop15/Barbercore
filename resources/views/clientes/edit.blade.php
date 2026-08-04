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

            @if(auth()->user()->rol === 'admin')
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
            @endif

            <div class="form-group">
                <label>Última visita registrada</label>
                <div class="system-data-field" aria-label="Última visita calculada por el sistema">
                    <span class="system-data-icon"><x-icon name="calendar" size="18" /></span>
                    <span>
                        <strong>{{ $ultimaVisita ? \Carbon\Carbon::parse($ultimaVisita)->format('d/m/Y') : 'Sin visitas completadas' }}</strong>
                        <small>Se actualiza automáticamente al completar una cita.</small>
                    </span>
                </div>
            </div>

            <div class="form-group full">
                <label>Foto actual</label>

                @if ($cliente->foto)
                    <img src="{{ asset('storage/' . $cliente->foto) }}" alt="Foto de {{ $cliente->nombreCompleto() }}" class="client-edit-photo">
                @else
                    <div class="client-edit-photo-placeholder"><x-icon name="image" size="34" /><span>Sin foto registrada</span></div>
                @endif
            </div>

            <div class="form-group full client-photo-upload">
                <label for="foto">Cambiar foto</label>
                <input 
                    type="file" 
                    name="foto" 
                    id="foto" 
                    accept="image/jpeg,image/png,image/webp"
                >
                <small>La vista previa aparecerá en tamaño ampliado. Máximo 2 MB.</small>
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
