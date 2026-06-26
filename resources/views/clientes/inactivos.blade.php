@extends('layouts.app')

@section('title', 'Clientes inactivos | BarberCore')
@section('page-title', 'Clientes inactivos')

@section('content')

<div class="content-card">
    <div class="page-actions">
        <div>
            <h3 style="margin: 0;">Seguimiento de clientes inactivos</h3>
            <p style="margin: 6px 0 0; color: var(--gris);">
                Clientes con {{ $diasLimite }} días o más sin visitar la barbería.
            </p>
        </div>

        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            Ver clientes
        </a>
    </div>

    <div class="warning-box">
        Total de clientes inactivos encontrados: <strong>{{ $totalInactivos }}</strong>.
        Este listado ayuda a detectar clientes que podrían necesitar seguimiento o recordatorio.
    </div>

    <div class="filter-tabs">
        <a 
            href="{{ route('clientes.inactivos', ['filtro' => 'todos']) }}" 
            class="filter-tab {{ $filtro === 'todos' ? 'active' : '' }}"
        >
            Todos
        </a>

        <a 
            href="{{ route('clientes.inactivos', ['filtro' => 'con_cita']) }}" 
            class="filter-tab {{ $filtro === 'con_cita' ? 'active' : '' }}"
        >
            Con cita pendiente
        </a>

        <a 
            href="{{ route('clientes.inactivos', ['filtro' => 'sin_cita']) }}" 
            class="filter-tab {{ $filtro === 'sin_cita' ? 'active' : '' }}"
        >
            Sin cita pendiente
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Teléfono</th>
                <th>Días sin venir</th>
                <th>Última visita</th>
                <th>Cita pendiente</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($clientes as $cliente)
                @php
                    $telefonoLimpio = preg_replace('/\D/', '', $cliente->telefono ?? '');
                    $mensaje = urlencode(
                        'Hola ' . $cliente->nombre . ', te saludamos de BarberCore. Notamos que hace ' . 
                        $cliente->dias_sin_venir . 
                        ' días no nos visitas. ¿Te gustaría agendar una nueva cita?'
                    );
                @endphp

                <tr>
                    <td>
                        <strong>{{ $cliente->nombre }} {{ $cliente->apellido }}</strong>
                        <br>
                        <span style="color: var(--gris); font-size: 13px;">
                            Puntos: {{ $cliente->puntos }}
                        </span>
                    </td>

                    <td>
                        {{ $cliente->telefono ?? 'No registrado' }}
                    </td>

                    <td>
                        <span class="inactive-days">
                            {{ $cliente->dias_sin_venir }} días
                        </span>
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($cliente->ultima_visita)->format('d/m/Y') }}
                    </td>

                    <td>
                        @if ($cliente->tiene_cita_pendiente)
                            <span class="badge badge-completada">
                                Sí
                            </span>

                            <br>

                            <span style="color: var(--gris); font-size: 13px;">
                                Próxima:
                                {{ \Carbon\Carbon::parse($cliente->citas->first()->fecha)->format('d/m/Y') }}
                                {{ $cliente->citas->first()->hora_inicio }}
                            </span>
                        @else
                            <span class="badge badge-cancelada">
                                No
                            </span>
                        @endif
                    </td>

                    <td>
                        <div class="actions">
                            <a href="{{ route('clientes.show', $cliente->id_cliente) }}" class="btn btn-secondary btn-sm">
                                Ver
                            </a>

                            @if ($telefonoLimpio)
                                <a 
                                    href="https://wa.me/52{{ $telefonoLimpio }}?text={{ $mensaje }}" 
                                    target="_blank" 
                                    class="btn btn-sm whatsapp-btn"
                                >
                                    Enviar mensaje
                                </a>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled>
                                    Sin teléfono
                                </button>
                            @endif

                            <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="btn btn-primary btn-sm">
                                Editar
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        No hay clientes inactivos con el filtro seleccionado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection