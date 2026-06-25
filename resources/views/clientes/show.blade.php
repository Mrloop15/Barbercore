@extends('layouts.app')

@section('title', 'Detalle del cliente | BarberCore')
@section('page-title', 'Detalle del cliente')

@section('content')

<div class="content-card">
    <div class="page-actions">
        <div>
            <h3 style="margin: 0;">
                {{ $cliente->nombre }} {{ $cliente->apellido }}
            </h3>
            <p style="margin: 6px 0 0; color: var(--gris);">
                Información general, puntos e historial de citas.
            </p>
        </div>

        <div class="actions">
            <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="btn btn-primary">
                Editar
            </a>

            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                Volver
            </a>
        </div>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <span>Teléfono</span>
            <strong>{{ $cliente->telefono ?? 'No registrado' }}</strong>
        </div>

        <div class="detail-item">
            <span>Cumpleaños</span>
            <strong>
                {{ $cliente->cumpleanos ? \Carbon\Carbon::parse($cliente->cumpleanos)->format('d/m/Y') : 'No registrado' }}
            </strong>
        </div>

        <div class="detail-item">
            <span>Puntos acumulados</span>
            <strong>{{ $cliente->puntos }}</strong>
        </div>

        <div class="detail-item">
            <span>Última visita</span>
            <strong>
                {{ $cliente->ultima_visita ? \Carbon\Carbon::parse($cliente->ultima_visita)->format('d/m/Y') : 'Sin visitas registradas' }}
            </strong>
        </div>

        <div class="detail-item" style="grid-column: 1 / -1;">
            <span>Observaciones</span>
            <strong>{{ $cliente->observaciones ?? 'Sin observaciones.' }}</strong>
        </div>
    </div>
</div>

<div class="content-card" style="margin-top: 22px;">
    <h3 style="margin-top: 0;">Historial de citas</h3>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Servicio</th>
                <th>Precio</th>
                <th>Estado</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($cliente->citas->sortByDesc('fecha') as $cita)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $cita->hora_inicio }}</td>
                    <td>{{ $cita->servicio->nombre ?? 'Sin servicio' }}</td>
                    <td>${{ number_format($cita->precio, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $cita->estado }}">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Este cliente aún no tiene citas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection