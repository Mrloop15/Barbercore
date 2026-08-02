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
    <div class="module-tools">
        <div class="filter-panel">
            <div class="filter-panel-head">
                <div class="filter-panel-heading"><span class="filter-panel-icon"><x-icon name="filter" /></span><div><strong class="filter-panel-title">Buscar recompensas</strong><span class="filter-panel-subtitle">Consulta por nombre, descripción o tipo.</span></div></div>
                <div class="filter-panel-meta">
                    <span class="filter-result-count"><strong>{{ $recompensas->total() }}</strong> resultados</span>
                    <div class="module-primary-actions">
                        <a href="{{ route('recompensas.formCanjear') }}" class="btn module-action-btn-secondary"><x-icon name="gift" /> Canjear</a>
                        <a href="{{ route('recompensas.create') }}" class="btn module-action-btn"><x-icon name="plus" /> <span>Nueva recompensa</span></a>
                    </div>
                </div>
            </div>
            <form method="GET" action="{{ route('recompensas.index') }}" class="filter-form">
                <label class="filter-field filter-field-grow"><span class="filter-label">Recompensa</span><span class="filter-search-control"><x-icon name="search" /><input type="search" name="buscar" value="{{ $buscar }}" placeholder="Ej. Corte gratis" autocomplete="off"></span></label>
                <div class="filter-actions"><button type="submit" class="btn btn-secondary"><x-icon name="search" /> Buscar</button>@if (filled($buscar))<a href="{{ route('recompensas.index') }}" class="btn filter-clear"><x-icon name="close" /> Limpiar</a>@endif</div>
            </form>
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
                            <a href="{{ route('recompensas.edit', $recompensa->id_recompensa) }}" class="btn btn-primary btn-icon" title="Editar recompensa" aria-label="Editar recompensa">
                                <x-icon name="edit" /><span class="sr-only">Editar recompensa</span>
                            </a>

                            <form method="POST" action="{{ route('recompensas.destroy', $recompensa->id_recompensa) }}" onsubmit="return confirm('¿Seguro que deseas eliminar esta recompensa?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-icon" title="Eliminar recompensa" aria-label="Eliminar recompensa">
                                    <x-icon name="trash" /><span class="sr-only">Eliminar recompensa</span>
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
