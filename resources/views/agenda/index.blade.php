@extends('layouts.app')

@section('title', 'Agenda | BarberCore')
@section('page-title', 'Agenda')

@section('content')
<section class="agenda-hero">
    <div>
        <span class="agenda-eyebrow">Planificación de servicios</span>
        <h3>{{ $tituloPeriodo }}</h3>
        <p>Visualiza la ocupación del equipo y consulta el detalle de cada cita.</p>
    </div>
    <a href="{{ route('citas.create') }}" class="btn module-action-btn"><x-icon name="plus" /> <span>Agendar cita</span></a>
</section>

<section class="agenda-toolbar">
    <div class="agenda-view-selector">
        <span class="filter-label">Vista de agenda</span>
        <nav class="agenda-tabs" aria-label="Tipo de vista">
            <a href="{{ route('agenda.index', ['vista' => 'dia', 'fecha' => $fechaBase->toDateString()]) }}" class="agenda-tab {{ $vista === 'dia' ? 'active' : '' }}" @if($vista === 'dia') aria-current="page" @endif>Día</a>
            <a href="{{ route('agenda.index', ['vista' => 'semana', 'fecha' => $fechaBase->toDateString()]) }}" class="agenda-tab {{ $vista === 'semana' ? 'active' : '' }}" @if($vista === 'semana') aria-current="page" @endif>Semana</a>
            <a href="{{ route('agenda.index', ['vista' => 'mes', 'fecha' => $fechaBase->toDateString()]) }}" class="agenda-tab {{ $vista === 'mes' ? 'active' : '' }}" @if($vista === 'mes') aria-current="page" @endif>Mes</a>
        </nav>
    </div>

    <form method="GET" action="{{ route('agenda.index') }}" class="agenda-filter">
        <input type="hidden" name="vista" value="{{ $vista }}">
        <label class="filter-field filter-field-date"><span class="filter-label">Fecha de referencia</span><input type="date" name="fecha" value="{{ $fechaBase->toDateString() }}"></label>
        <div class="filter-actions">
            <button type="submit" class="btn btn-secondary"><x-icon name="calendar" /> Ir a fecha</button>
            @if (!$fechaBase->isToday())<a href="{{ route('agenda.index', ['vista' => $vista, 'fecha' => now()->toDateString()]) }}" class="btn filter-clear">Hoy</a>@endif
        </div>
    </form>
</section>

<section class="agenda-summary agenda-summary-wide">
    <div class="agenda-summary-card"><span>Total de citas</span><strong>{{ $totalCitas }}</strong></div>
    <div class="agenda-summary-card"><span>Pendientes</span><strong>{{ $pendientes }}</strong></div>
    <div class="agenda-summary-card"><span>Completadas</span><strong>{{ $completadas }}</strong></div>
    <div class="agenda-summary-card"><span>Canceladas</span><strong>{{ $canceladas }}</strong></div>
    <div class="agenda-summary-card"><span>Ingresos estimados</span><strong>${{ number_format($ingresosEstimados, 2) }}</strong></div>
    <div class="agenda-summary-card"><span>Ingresos generados</span><strong>${{ number_format($ingresosGenerados, 2) }}</strong></div>
</section>

<section class="timeline-card">
    <header class="timeline-card-header">
        <div>
            <span class="agenda-eyebrow">Línea de tiempo</span>
            <h3>Ocupación de servicios</h3>
        </div>
        <div class="timeline-header-tools">
            <label class="timeline-jump" for="timelineHourSelect">
                <span>Ir a horario</span>
                <select id="timelineHourSelect">
                    <option value="start">Inicio del día</option>
                    @foreach ($marcadoresHorario as $hora)
                        @if (($hora - $horaInicioAgenda) % 2 === 0)
                            <option value="{{ $hora }}">{{ sprintf('%02d:00', $hora) }}</option>
                        @endif
                    @endforeach
                </select>
            </label>
            <div class="timeline-legend" aria-label="Estados de las citas">
                <span><i class="pending"></i>Pendiente</span>
                <span><i class="completed"></i>Completada</span>
                <span><i class="cancelled"></i>Cancelada</span>
            </div>
        </div>
    </header>

    @if ($citasAgrupadas->isNotEmpty())
        <div class="timeline-scroll" id="timelineScroll" tabindex="0" aria-label="Diagrama temporal de citas" data-start-hour="{{ $horaInicioAgenda }}">
            <div class="gantt" style="--hour-count: {{ max(1, $horaFinAgenda - $horaInicioAgenda) }};">
                <div class="gantt-corner">Día</div>
                <div class="gantt-hours">
                    @foreach ($marcadoresHorario as $hora)
                        <span style="left: {{ (($hora - $horaInicioAgenda) / max(1, $horaFinAgenda - $horaInicioAgenda)) * 100 }}%">{{ sprintf('%02d:00', $hora) }}</span>
                    @endforeach
                </div>

                @foreach ($citasAgrupadas as $fecha => $citasDelDia)
                    @php
                        $finalPorCarril = [];
                        $carrilPorCita = [];

                        foreach ($citasDelDia as $citaParaCarril) {
                            $inicioParaCarril = \Carbon\Carbon::parse($citaParaCarril->hora_inicio);
                            $finParaCarril = \Carbon\Carbon::parse($citaParaCarril->hora_fin);
                            $inicioEnMinutos = ($inicioParaCarril->hour * 60) + $inicioParaCarril->minute;
                            $finEnMinutos = ($finParaCarril->hour * 60) + $finParaCarril->minute;
                            $carrilDisponible = null;

                            foreach ($finalPorCarril as $numeroCarril => $finDelCarril) {
                                if ($finDelCarril <= $inicioEnMinutos) {
                                    $carrilDisponible = $numeroCarril;
                                    break;
                                }
                            }

                            if ($carrilDisponible === null) {
                                $carrilDisponible = count($finalPorCarril);
                            }

                            $finalPorCarril[$carrilDisponible] = $finEnMinutos;
                            $carrilPorCita[$citaParaCarril->id_cita] = $carrilDisponible;
                        }

                        $totalCarriles = max(1, count($finalPorCarril));
                    @endphp
                    <div class="gantt-day" style="--lanes: {{ $totalCarriles }};">
                        <strong>{{ \Carbon\Carbon::parse($fecha)->translatedFormat('D d') }}</strong>
                        <span>{{ $citasDelDia->count() }} {{ $citasDelDia->count() === 1 ? 'cita' : 'citas' }}</span>
                    </div>
                    <div class="gantt-track" style="--lanes: {{ $totalCarriles }};">
                        @foreach ($citasDelDia as $cita)
                            @php
                                $inicioCita = \Carbon\Carbon::parse($cita->hora_inicio);
                                $finCita = \Carbon\Carbon::parse($cita->hora_fin);
                                $minutosInicio = (($inicioCita->hour - $horaInicioAgenda) * 60) + $inicioCita->minute;
                                $duracionCita = max(15, $inicioCita->diffInMinutes($finCita));
                                $left = max(0, min(100, ($minutosInicio / $duracionJornadaMinutos) * 100));
                                $width = max(2.5, min(100 - $left, ($duracionCita / $duracionJornadaMinutos) * 100));
                            @endphp
                            <button type="button"
                               class="gantt-event status-{{ $cita->estado }} js-appointment-modal"
                               style="left: {{ $left }}%; width: {{ $width }}%; --lane: {{ $carrilPorCita[$cita->id_cita] }};"
                               data-id="{{ $cita->id_cita }}"
                               data-date="{{ \Carbon\Carbon::parse($cita->fecha)->translatedFormat('l d \d\e F \d\e Y') }}"
                               data-start="{{ $inicioCita->format('H:i') }}"
                               data-end="{{ $finCita->format('H:i') }}"
                               data-client="{{ trim(($cita->cliente->nombre ?? 'Sin cliente') . ' ' . ($cita->cliente->apellido ?? '')) }}"
                               data-service="{{ $cita->servicio->nombre ?? 'Sin servicio' }}"
                               data-barber="{{ $cita->barbero->nombre ?? 'Sin asignar' }}"
                               data-price="${{ number_format($cita->precio, 2) }}"
                               data-status="{{ $cita->estado }}"
                               data-notes="{{ $cita->observaciones ?? 'Sin observaciones' }}"
                               data-edit-url="{{ $cita->estado === 'pendiente' ? route('citas.edit', $cita->id_cita) : '' }}"
                               data-complete-url="{{ $cita->estado === 'pendiente' ? route('citas.completar', $cita->id_cita) : '' }}"
                               data-cancel-url="{{ $cita->estado === 'pendiente' ? route('citas.cancelar', $cita->id_cita) : '' }}"
                               aria-label="Ver detalle de la cita de {{ $cita->cliente->nombre ?? 'cliente' }}">
                                <i aria-hidden="true"></i>
                                <strong><time>{{ $inicioCita->format('H:i') }}</time><span>{{ $cita->cliente->nombre ?? 'Sin cliente' }}</span></strong>
                            </button>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="timeline-empty">
            <strong>La agenda está libre</strong>
            <span>No hay citas registradas para el periodo seleccionado.</span>
        </div>
    @endif
</section>

<section class="agenda-details">
    <div class="agenda-details-heading">
        <div><span class="agenda-eyebrow">Vista detallada</span><h3>Información de las citas</h3></div>
        <span>{{ $totalCitas }} registros</span>
    </div>

    @forelse ($citasAgrupadas as $fecha => $citasDelDia)
        <details class="agenda-day-detail" {{ $loop->first ? 'open' : '' }}>
            <summary>
                <span><strong>{{ \Carbon\Carbon::parse($fecha)->translatedFormat('l d') }}</strong>{{ \Carbon\Carbon::parse($fecha)->translatedFormat('F Y') }}</span>
                <span class="day-count">{{ $citasDelDia->count() }} {{ $citasDelDia->count() === 1 ? 'servicio' : 'servicios' }}</span>
            </summary>
            <div class="agenda-table-wrap">
                <table>
                    <thead><tr><th>Horario</th><th>Cliente</th><th>Servicio</th><th>Barbero</th><th>Precio</th><th>Estado</th><th>Acción</th></tr></thead>
                    <tbody>
                        @foreach ($citasDelDia as $cita)
                            <tr>
                                <td><span class="agenda-time">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }} – {{ \Carbon\Carbon::parse($cita->hora_fin)->format('H:i') }}</span></td>
                                <td><strong>{{ $cita->cliente->nombre ?? 'Sin cliente' }} {{ $cita->cliente->apellido ?? '' }}</strong></td>
                                <td>{{ $cita->servicio->nombre ?? 'Sin servicio' }}</td>
                                <td>{{ $cita->barbero->nombre ?? 'Sin asignar' }}</td>
                                <td>${{ number_format($cita->precio, 2) }}</td>
                                <td><span class="badge badge-{{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></td>
                                <td>
                                    @if ($cita->estado === 'pendiente')
                                        <a href="{{ route('citas.edit', $cita->id_cita) }}" class="btn btn-primary btn-icon" title="Editar cita" aria-label="Editar cita"><x-icon name="edit" /><span class="sr-only">Editar cita</span></a>
                                    @else
                                        <span class="no-action">Sin acción</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </details>
    @empty
        <div class="timeline-empty"><span>No hay detalles para mostrar.</span></div>
    @endforelse
</section>

<script>
    (function () {
        const select = document.getElementById('timelineHourSelect');
        const scroller = document.getElementById('timelineScroll');
        if (!select || !scroller) return;

        select.addEventListener('change', function () {
            if (select.value === 'start') {
                scroller.scrollTo({ left: 0, behavior: 'smooth' });
                return;
            }

            const startHour = Number(scroller.dataset.startHour);
            const selectedHour = Number(select.value);
            const dayColumnWidth = 112;
            const hourWidth = 140;
            scroller.scrollTo({ left: Math.max(0, dayColumnWidth + ((selectedHour - startHour) * hourWidth) - 24), behavior: 'smooth' });
        });
    })();
</script>

<div class="appointment-modal" id="appointmentModal" aria-hidden="true">
    <div class="appointment-modal-backdrop" data-modal-close></div>
    <section class="appointment-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="appointmentModalTitle">
        <header class="appointment-modal-header">
            <div>
                <span class="agenda-eyebrow">Detalle de la cita</span>
                <h3 id="appointmentModalTitle">Información del servicio</h3>
            </div>
            <button type="button" class="appointment-modal-close" data-modal-close aria-label="Cerrar modal"><x-icon name="close" /></button>
        </header>
        <div class="appointment-modal-body">
            <div class="modal-appointment-heading">
                <div class="modal-date-icon"><span id="modalDay">--</span><small id="modalMonth">---</small></div>
                <div><strong id="modalDate"></strong><span id="modalTime"></span></div>
                <span class="badge" id="modalStatus"></span>
            </div>
            <div class="modal-detail-grid">
                <div class="modal-detail"><span>Cliente</span><strong id="modalClient"></strong></div>
                <div class="modal-detail"><span>Servicio</span><strong id="modalService"></strong></div>
                <div class="modal-detail"><span>Barbero</span><strong id="modalBarber"></strong></div>
                <div class="modal-detail"><span>Precio</span><strong id="modalPrice"></strong></div>
                <div class="modal-detail modal-notes"><span>Observaciones</span><p id="modalNotes"></p></div>
            </div>
        </div>
        <footer class="appointment-modal-footer">
            <button type="button" class="btn btn-secondary" data-modal-close>Cerrar</button>
            <div class="modal-appointment-actions" id="modalAppointmentActions">
                <form method="POST" action="#" id="modalCompleteForm" data-confirm-title="Completar cita" data-confirm="Se registrará el servicio como realizado y se actualizarán los puntos y el historial del cliente." data-confirm-text="Completar cita" data-confirm-tone="success">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success"><x-icon name="check" /> Completar</button>
                </form>
                <form method="POST" action="#" id="modalCancelForm" data-confirm-title="Cancelar cita" data-confirm="La cita quedará marcada como cancelada y el horario volverá a estar disponible." data-confirm-text="Cancelar cita" data-confirm-tone="danger">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger"><x-icon name="close" /> Cancelar</button>
                </form>
                <a href="#" class="btn btn-primary" id="modalEditLink"><x-icon name="edit" /> Editar</a>
            </div>
        </footer>
    </section>
</div>

<script>
    (function () {
        const modal = document.getElementById('appointmentModal');
        const editLink = document.getElementById('modalEditLink');
        const appointmentActions = document.getElementById('modalAppointmentActions');
        const completeForm = document.getElementById('modalCompleteForm');
        const cancelForm = document.getElementById('modalCancelForm');
        let lastTrigger = null;

        function openModal(trigger) {
            lastTrigger = trigger;
            const data = trigger.dataset;
            document.getElementById('modalDate').textContent = data.date;
            document.getElementById('modalTime').textContent = data.start + ' – ' + data.end;
            document.getElementById('modalClient').textContent = data.client;
            document.getElementById('modalService').textContent = data.service;
            document.getElementById('modalBarber').textContent = data.barber;
            document.getElementById('modalPrice').textContent = data.price;
            document.getElementById('modalNotes').textContent = data.notes;
            const status = document.getElementById('modalStatus');
            status.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
            status.className = 'badge badge-' + data.status;
            const dateParts = data.date.split(' ');
            document.getElementById('modalDay').textContent = dateParts[1] || '--';
            document.getElementById('modalMonth').textContent = (dateParts[3] || '---').slice(0, 3);
            editLink.style.display = data.editUrl ? 'inline-flex' : 'none';
            editLink.href = data.editUrl || '#';
            appointmentActions.hidden = !data.completeUrl;
            completeForm.action = data.completeUrl || '#';
            cancelForm.action = data.cancelUrl || '#';
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');
            modal.querySelector('.appointment-modal-close').focus();
        }

        function closeModal() {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');
            if (lastTrigger) lastTrigger.focus();
        }

        document.querySelectorAll('.js-appointment-modal').forEach(trigger => trigger.addEventListener('click', () => openModal(trigger)));
        modal.querySelectorAll('[data-modal-close]').forEach(button => button.addEventListener('click', closeModal));
        document.addEventListener('keydown', event => { if (event.key === 'Escape' && modal.classList.contains('open')) closeModal(); });
    })();
</script>
@endsection
