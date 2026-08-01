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
                    <button type="button" class="date-shortcut" data-date="{{ now()->toDateString() }}">Hoy</button>
                    <button type="button" class="date-shortcut" data-date="{{ now()->addDay()->toDateString() }}">Mañana</button>
                    <button type="button" class="date-shortcut" data-date="{{ now()->next('saturday')->toDateString() }}">Sábado</button>
                </div>
                <input 
                    type="date" 
                    name="fecha" 
                    id="fecha" 
                    value="{{ old('fecha', $fechaInicial->toDateString()) }}" 
                    min="{{ now()->toDateString() }}"
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
    const resumenPrecio = document.getElementById('resumenPrecio');
    const resumenDuracion = document.getElementById('resumenDuracion');
    const resumenHorario = document.getElementById('resumenHorario');
    const fechaInput = document.getElementById('fecha');
    const horaInput = document.getElementById('hora_inicio');
    const fechaLegible = document.getElementById('fechaLegible');
    const horarioDiaHelp = document.getElementById('horarioDiaHelp');
    const timeShortcuts = document.getElementById('timeShortcuts');
    const horariosAtencion = @json($horariosCita);

    const minutos = hora => {
        const [h, m] = (hora || '00:00').split(':').map(Number);
        return (h * 60) + m;
    };

    const horaDesdeMinutos = total => `${String(Math.floor(total / 60)).padStart(2, '0')}:${String(total % 60).padStart(2, '0')}`;

    function horarioParaFecha(fecha) {
        if (!fecha) return null;
        const dia = new Date(fecha + 'T12:00:00');
        const indice = (dia.getDay() + 6) % 7;
        const horario = horariosAtencion[String(indice)];
        return horario?.abierto && horario.apertura && horario.cierre ? horario : null;
    }

    function actualizarDisponibilidad() {
        const horario = horarioParaFecha(fechaInput.value);
        const duracion = Math.max(15, parseInt(servicioSelect.options[servicioSelect.selectedIndex]?.getAttribute('data-duracion') || 0, 10));
        timeShortcuts.innerHTML = '';

        if (!horario) {
            horaInput.value = '';
            horaInput.dataset.unavailable = 'true';
            horaInput.dataset.unavailableMessage = 'La barbería permanece cerrada este día.';
            horaInput.removeAttribute('min');
            horaInput.removeAttribute('max');
            horarioDiaHelp.textContent = 'La barbería permanece cerrada este día.';
            fechaInput.setCustomValidity('Selecciona un día con horario de atención.');
            horaInput.dispatchEvent(new Event('change', { bubbles: true }));
            return;
        }

        fechaInput.setCustomValidity('');
        horaInput.dataset.unavailable = 'false';
        delete horaInput.dataset.unavailableMessage;
        const apertura = minutos(horario.apertura);
        const ultimoInicio = minutos(horario.cierre) - duracion;

        if (ultimoInicio < apertura) {
            horaInput.value = '';
            horaInput.dataset.unavailable = 'true';
            horaInput.dataset.unavailableMessage = 'Este servicio no cabe dentro del horario de atención de este día.';
            horaInput.removeAttribute('min');
            horaInput.removeAttribute('max');
            horarioDiaHelp.textContent = horaInput.dataset.unavailableMessage;
            horaInput.dispatchEvent(new Event('change', { bubbles: true }));
            return;
        }

        horaInput.min = horario.apertura;
        horaInput.max = horaDesdeMinutos(ultimoInicio);
        horarioDiaHelp.textContent = `Atención de ${horario.apertura} a ${horario.cierre}. Último inicio para este servicio: ${horaInput.max}.`;

        if (horaInput.value && (minutos(horaInput.value) < apertura || minutos(horaInput.value) > ultimoInicio)) {
            horaInput.value = '';
        }

        const candidatos = [];
        for (let valor = apertura; valor <= ultimoInicio && candidatos.length < 5; valor += 120) candidatos.push(valor);
        if (ultimoInicio >= apertura && !candidatos.includes(ultimoInicio)) candidatos.push(ultimoInicio);
        candidatos.slice(0, 5).forEach(valor => {
            const hora = horaDesdeMinutos(valor);
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

        horaInput.dispatchEvent(new Event('change', { bubbles: true }));
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
    actualizarResumenServicio();
</script>

@endsection
