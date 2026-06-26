@extends('layouts.app')

@section('title', 'Recompensas | BarberCore')
@section('page-title', 'Recompensas')

@section('content')

<div class="agenda-summary">
    <div class="agenda-summary-card">
        <span>Recompensas activas</span>
        <strong>{{ $totalRecompensas }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Canjes realizados</span>
        <strong>{{ $totalCanjes }}</strong>
    </div>

    <div class="agenda-summary-card">
        <span>Clientes con puntos</span>
        <strong>{{ $clientesConPuntos }}</strong>
    </div>
</div>

<div class="content-card">
    <div class="page-actions">
        <form method="GET" action="{{ route('recompensas.index') }}" class="search-form">
            <input 
                type="text" 
                name="buscar" 
                value="{{ $buscar }}" 
                placeholder="Buscar recompensa por nombre, descripción o tipo"
            >

            <button type="submit" class="btn btn-secondary">
                Buscar
            </button>
        </form>

        <div class="actions">
            <a href="{{ route('recompensas.formCanjear') }}" class="btn btn-success">
                Canjear recompensa
            </a>

            <a href="{{ route('recompensas.create') }}" class="btn btn-primary">
                Agregar recompensa
            </a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Recompensa</th>
                <th>Tipo</th>
                <th>Puntos requeridos</th>
                <th>Valor</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($recompensas as $recompensa)
                <tr>
                    <td>
                        <strong>{{ $recompensa->nombre }}</strong>
                        <br>
                        <span style="color: var(--gris); font-size: 13px;">
                            {{ $recompensa->descripcion ?? 'Sin descripción' }}
                        </span>
                    </td>

                    <td>
                        <span class="reward-type">
                            {{ $recompensa->tipo }}
                        </span>
                    </td>

                    <td>
                        <span class="reward-points">
                            {{ $recompensa->puntos_requeridos }} pts
                        </span>
                    </td>

                    <td>
                        ${{ number_format($recompensa->valor, 2) }}
                    </td>

                    <td>
                        <span class="badge badge-completada">
                            Activa
                        </span>
                    </td>

                    <td>
                        <div class="actions">
                            <a href="{{ route('recompensas.edit', $recompensa->id_recompensa) }}" class="btn btn-primary btn-sm">
                                Editar
                            </a>

                            <form method="POST" action="{{ route('recompensas.destroy', $recompensa->id_recompensa) }}" onsubmit="return confirm('¿Seguro que deseas eliminar esta recompensa?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay recompensas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $recompensas->links() }}
    </div>
</div>

@endsection