@extends('layouts.app')

@section('title', 'Nueva cita | BarberCore')
@section('page-title', 'Nueva cita')

@section('content')

@php
    $horariosCita = $horarios->mapWithKeys(fn ($horario) => [
        (string) $horario->dia_semana => [
            'abierto' => (bool) $horario->abierto,
            'apertura' => $horario->hora_apertura ? substr($horario->hora_apertura, 0, 5) : null,
            'cierre' => $horario->hora_cierre ? substr($horario->hora_cierre, 0, 5) : null,
        ],
    ])->all();
    $diasAbiertos = $horarios->where('abierto', true)->pluck('dia_semana')->implode(',');
@endphp

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
                    <button type="button" class="date-shortcut" data-date="{{ \App\Support\BusinessClock::today()->toDateString() }}">Hoy</button>
                    <button type="button" class="date-shortcut" data-date="{{ \App\Support\BusinessClock::today()->addDay()->toDateString() }}">Mañana</button>
                    <button type="button" class="date-shortcut" data-date="{{ \App\Support\BusinessClock::today()->next('saturday')->toDateString() }}">Sábado</button>
                </div>
                <input 
                    type="date" 
                    name="fecha" 
                    id="fecha" 
                    value="{{ old('fecha', $fechaInicial->toDateString()) }}" 
                    min="{{ \App\Support\BusinessClock::today()->toDateString() }}"
                    data-open-weekdays="{{ $diasAbiertos }}"
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
                    data-availability-url="{{ route('citas.disponibilidad') }}"
                    data-available-times=""
                    data-unavailable="true"
                    data-unavailable-label="Selecciona los datos"
                    data-unavailable-message="Selecciona servicio, barbero y fecha para consultar los horarios."
                    required
                >
                <div class="time-shortcuts" id="timeShortcuts"></div>
                <small class="field-help" id="horarioDiaHelp"></small>
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
    const barberoSelect = document.getElementById('id_barbero');
    const resumenPrecio = document.getElementById('resumenPrecio');
    const resumenDuracion = document.getElementById('resumenDuracion');
    const resumenHorario = document.getElementById('resumenHorario');
    const fechaInput = document.getElementById('fecha');
    const horaInput = document.getElementById('hora_inicio');
    const fechaLegible = document.getElementById('fechaLegible');
    const horarioDiaHelp = document.getElementById('horarioDiaHelp');
    const timeShortcuts = document.getElementById('timeShortcuts');
    const horariosAtencion = @json($horariosCita);
    let availabilityRequest = 0;

    function horarioParaFecha(fecha) {
        if (!fecha) return null;
        const dia = new Date(fecha + 'T12:00:00');
        const indice = (dia.getDay() + 6) % 7;
        const horario = horariosAtencion[String(indice)];
        return horario?.abierto && horario.apertura && horario.cierre ? horario : null;
    }

    function marcarHorarioNoDisponible(label, message) {
        horaInput.value = '';
        horaInput.dataset.availableTimes = '';
        horaInput.dataset.unavailable = 'true';
        horaInput.dataset.unavailableLabel = label;
        horaInput.dataset.unavailableMessage = message;
        horaInput.removeAttribute('min');
        horaInput.removeAttribute('max');
        horarioDiaHelp.textContent = message;
        horaInput.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function agregarAccesosRapidos(horarios) {
        timeShortcuts.innerHTML = '';
        const seleccionados = horarios.filter((hora, index) => index % 8 === 0).slice(0, 5);
        if (horarios.length && !seleccionados.includes(horarios.at(-1)) && seleccionados.length < 5) seleccionados.push(horarios.at(-1));

        seleccionados.forEach(hora => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'time-shortcut';
            button.dataset.time = hora;
            button.textContent = hora;
            button.addEventListener('click', () => {
                horaInput.value = hora;
                horaInput.dispatchEvent(new Event('change', { bubbles: true }));
            });
            timeShortcuts.appendChild(button);
        });
    }

    async function actualizarDisponibilidad() {
        const requestId = ++availabilityRequest;
        const horario = horarioParaFecha(fechaInput.value);
        const horaPrevia = horaInput.value.slice(0, 5);
        timeShortcuts.innerHTML = '';

        if (!horario) {
            fechaInput.setCustomValidity('Selecciona un día con horario de atención.');
            marcarHorarioNoDisponible('Día cerrado', 'La barbería permanece cerrada este día.');
            return;
        }

        fechaInput.setCustomValidity('');
        if (!servicioSelect.value || !barberoSelect.value || !fechaInput.value) {
            marcarHorarioNoDisponible('Selecciona los datos', 'Selecciona servicio, barbero y fecha para consultar los horarios.');
            return;
        }

        marcarHorarioNoDisponible('Consultando horarios', 'Revisando la agenda del barbero…');

        try {
            const query = new URLSearchParams({
                fecha: fechaInput.value,
                id_barbero: barberoSelect.value,
                id_servicio: servicioSelect.value,
            });
            const response = await fetch(`${horaInput.dataset.availabilityUrl}?${query}`, { headers: { Accept: 'application/json' } });
            const data = await response.json();
            if (requestId !== availabilityRequest) return;
            if (!response.ok) throw new Error(data.message || 'No fue posible consultar la disponibilidad.');

            const horarios = Array.isArray(data.horarios) ? data.horarios : [];
            if (!horarios.length) {
                marcarHorarioNoDisponible('Sin disponibilidad', data.message || 'No quedan horarios disponibles para esta selección.');
                return;
            }

            horaInput.dataset.availableTimes = horarios.join(',');
            horaInput.dataset.unavailable = 'false';
            delete horaInput.dataset.unavailableLabel;
            delete horaInput.dataset.unavailableMessage;
            horaInput.min = horarios[0];
            horaInput.max = horarios.at(-1);
            horaInput.value = horarios.includes(horaPrevia) ? horaPrevia : '';
            horarioDiaHelp.textContent = `${horarios.length} horarios disponibles · atención de ${data.apertura} a ${data.cierre}.`;
            agregarAccesosRapidos(horarios);
            horaInput.dispatchEvent(new Event('change', { bubbles: true }));
        } catch (error) {
            if (requestId !== availabilityRequest) return;
            marcarHorarioNoDisponible('No disponible', error.message || 'No fue posible consultar los horarios.');
        }
    }

    function actualizarResumenServicio() {
        const selected = servicioSelect.options[servicioSelect.selectedIndex];

        const precio = selected.getAttribute('data-precio') || 0;
        const duracion = selected.getAttribute('data-duracion') || 0;

        resumenPrecio.textContent = '$' + parseFloat(precio).toFixed(2);
        resumenDuracion.textContent = duracion + ' min';
        actualizarDisponibilidad();
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

    document.querySelectorAll('.date-shortcut').forEach(btn => {
        btn.disabled = !horarioParaFecha(btn.dataset.date);
        btn.addEventListener('click', () => { fechaInput.value = btn.dataset.date; fechaInput.dispatchEvent(new Event('change', { bubbles: true })); });
    });
    fechaInput.addEventListener('change', () => { actualizarDisponibilidad(); actualizarHorario(); });
    horaInput.addEventListener('change', actualizarHorario);

    servicioSelect.addEventListener('change', actualizarResumenServicio);
    barberoSelect.addEventListener('change', actualizarDisponibilidad);
    actualizarResumenServicio();
</script>

@endsection
