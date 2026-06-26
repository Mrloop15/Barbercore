@extends('layouts.app')

@section('title', 'Nueva cita | BarberCore')
@section('page-title', 'Nueva cita')

@section('content')

<div class="content-card">
    <form method="POST" action="{{ route('citas.store') }}">
        @csrf

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="form-grid">
            <div class="form-group">
                <label for="id_cliente">Cliente *</label>
                <select name="id_cliente" id="id_cliente" required>
                    <option value="">Selecciona un cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id_cliente }}" {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                            {{ $cliente->nombre }} {{ $cliente->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="id_servicio">Servicio *</label>
                <select name="id_servicio" id="id_servicio" required>
                    <option value="">Selecciona un servicio</option>
                    @foreach ($servicios as $servicio)
                        <option 
                            value="{{ $servicio->id_servicio }}"
                            data-precio="{{ $servicio->precio }}"
                            data-duracion="{{ $servicio->duracion_minutos }}"
                            {{ old('id_servicio') == $servicio->id_servicio ? 'selected' : '' }}
                        >
                            {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 2) }} - {{ $servicio->duracion_minutos }} min
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="id_barbero">Barbero *</label>
                <select name="id_barbero" id="id_barbero" required>
                    <option value="">Selecciona un barbero</option>
                    @foreach ($barberos as $barbero)
                        <option value="{{ $barbero->id_usuario }}" {{ old('id_barbero') == $barbero->id_usuario ? 'selected' : '' }}>
                            {{ $barbero->nombre }} - {{ ucfirst($barbero->rol) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha *</label>
                <input 
                    type="date" 
                    name="fecha" 
                    id="fecha" 
                    value="{{ old('fecha', date('Y-m-d')) }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="hora_inicio">Hora de inicio *</label>
                <input 
                    type="time" 
                    name="hora_inicio" 
                    id="hora_inicio" 
                    value="{{ old('hora_inicio') }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label>Resumen del servicio</label>
                <div class="detail-item">
                    <span>Precio</span>
                    <strong id="resumenPrecio">$0.00</strong>
                    <br><br>
                    <span>Duración aproximada</span>
                    <strong id="resumenDuracion">0 min</strong>
                </div>
            </div>

            <div class="form-group full">
                <label for="observaciones">Observaciones</label>
                <textarea 
                    name="observaciones" 
                    id="observaciones"
                    placeholder="Notas de la cita, preferencias del cliente o detalles importantes."
                >{{ old('observaciones') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Guardar cita
            </button>

            <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
    const servicioSelect = document.getElementById('id_servicio');
    const resumenPrecio = document.getElementById('resumenPrecio');
    const resumenDuracion = document.getElementById('resumenDuracion');

    function actualizarResumenServicio() {
        const selected = servicioSelect.options[servicioSelect.selectedIndex];

        const precio = selected.getAttribute('data-precio') || 0;
        const duracion = selected.getAttribute('data-duracion') || 0;

        resumenPrecio.textContent = '$' + parseFloat(precio).toFixed(2);
        resumenDuracion.textContent = duracion + ' min';
    }

    servicioSelect.addEventListener('change', actualizarResumenServicio);
    actualizarResumenServicio();
</script>

@endsection