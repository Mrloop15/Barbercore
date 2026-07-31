@extends('layouts.app')

@section('title', 'Nueva cita | BarberCore')
@section('page-title', 'Nueva cita')

@section('content')

<div class="content-card appointment-form-card">
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

            <div class="form-group appointment-date-group">
                <label for="fecha">Fecha de la cita *</label>
                <div class="date-shortcuts">
                    <button type="button" class="date-shortcut" data-date="{{ now()->toDateString() }}">Hoy</button>
                    <button type="button" class="date-shortcut" data-date="{{ now()->addDay()->toDateString() }}">Mañana</button>
                    <button type="button" class="date-shortcut" data-date="{{ now()->next('saturday')->toDateString() }}">Sábado</button>
                </div>
                <input 
                    type="date" 
                    name="fecha" 
                    id="fecha" 
                    value="{{ old('fecha', date('Y-m-d')) }}" 
                    min="{{ now()->toDateString() }}"
                    required
                >
                <small class="field-help" id="fechaLegible"></small>
            </div>

            <div class="form-group appointment-time-group">
                <label for="hora_inicio">Hora de inicio *</label>
                <input 
                    type="time" 
                    name="hora_inicio" 
                    id="hora_inicio" 
                    value="{{ old('hora_inicio') }}" 
                    step="900"
                    required
                >
                <div class="time-shortcuts">
                    @foreach (['09:00', '11:00', '13:00', '16:00', '18:00'] as $horaRapida)
                        <button type="button" class="time-shortcut" data-time="{{ $horaRapida }}">{{ $horaRapida }}</button>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label>Resumen del servicio</label>
                <div class="detail-item appointment-summary">
                    <span>Precio</span>
                    <strong id="resumenPrecio">$0.00</strong>
                    <br><br>
                    <span>Duración aproximada</span>
                    <strong id="resumenDuracion">0 min</strong>
                    <div class="appointment-window"><span>Horario estimado</span><strong id="resumenHorario">Selecciona fecha y hora</strong></div>
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
    const resumenHorario = document.getElementById('resumenHorario');
    const fechaInput = document.getElementById('fecha');
    const horaInput = document.getElementById('hora_inicio');
    const fechaLegible = document.getElementById('fechaLegible');

    function actualizarResumenServicio() {
        const selected = servicioSelect.options[servicioSelect.selectedIndex];

        const precio = selected.getAttribute('data-precio') || 0;
        const duracion = selected.getAttribute('data-duracion') || 0;

        resumenPrecio.textContent = '$' + parseFloat(precio).toFixed(2);
        resumenDuracion.textContent = duracion + ' min';
        actualizarHorario();
    }

    function actualizarHorario() {
        const duracion = parseInt(servicioSelect.options[servicioSelect.selectedIndex]?.getAttribute('data-duracion') || 0, 10);
        if (fechaInput.value) {
            const fecha = new Date(fechaInput.value + 'T12:00:00');
            fechaLegible.textContent = fecha.toLocaleDateString('es-MX', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        }
        if (!fechaInput.value || !horaInput.value) { resumenHorario.textContent = 'Selecciona fecha y hora'; return; }
        const inicio = new Date(fechaInput.value + 'T' + horaInput.value);
        const fin = new Date(inicio.getTime() + duracion * 60000);
        resumenHorario.textContent = inicio.toLocaleTimeString('es-MX', {hour:'2-digit', minute:'2-digit'}) + ' – ' + fin.toLocaleTimeString('es-MX', {hour:'2-digit', minute:'2-digit'});
    }

    document.querySelectorAll('.date-shortcut').forEach(btn => btn.addEventListener('click', () => { fechaInput.value = btn.dataset.date; fechaInput.dispatchEvent(new Event('change', { bubbles: true })); }));
    document.querySelectorAll('.time-shortcut').forEach(btn => btn.addEventListener('click', () => { horaInput.value = btn.dataset.time; horaInput.dispatchEvent(new Event('change', { bubbles: true })); }));
    fechaInput.addEventListener('change', actualizarHorario);
    horaInput.addEventListener('change', actualizarHorario);

    servicioSelect.addEventListener('change', actualizarResumenServicio);
    actualizarResumenServicio();
</script>

@endsection
