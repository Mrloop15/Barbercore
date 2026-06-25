@extends('layouts.app')

@section('title', 'Dashboard | BarberCore')
@section('page-title', 'Dashboard principal')

@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <span>Clientes registrados</span>
        <h3>{{ $totalClientes }}</h3>
    </div>

    <div class="stat-card">
        <span>Citas de hoy</span>
        <h3>{{ $citasHoy }}</h3>
    </div>

    <div class="stat-card">
        <span>Citas pendientes</span>
        <h3>{{ $citasPendientes }}</h3>
    </div>

    <div class="stat-card">
        <span>Citas completadas</span>
        <h3>{{ $citasCompletadas }}</h3>
    </div>

    <div class="stat-card">
        <span>Ingresos del día</span>
        <h3>${{ number_format($ingresosDia, 2) }}</h3>
    </div>

    <div class="stat-card">
        <span>Ingresos del mes</span>
        <h3>${{ number_format($ingresosMes, 2) }}</h3>
    </div>

    <div class="stat-card">
        <span>Clientes inactivos</span>
        <h3>{{ $clientesInactivos }}</h3>
    </div>

    <div class="stat-card">
        <span>Productos bajo stock</span>
        <h3>{{ $productosBajoStock }}</h3>
    </div>
</div>

<div class="content-card">
    <h3 style="margin-top: 0;">Próximas citas</h3>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Precio</th>
                <th>Estado</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($proximasCitas as $cita)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $cita->hora_inicio }}</td>
                    <td>{{ $cita->cliente->nombre ?? 'Sin cliente' }} {{ $cita->cliente->apellido ?? '' }}</td>
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
                    <td colspan="6">No hay próximas citas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection