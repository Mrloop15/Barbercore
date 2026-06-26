@extends('layouts.app')

@section('title', 'Servicios | BarberCore')
@section('page-title', 'Servicios')

@section('content')

<div class="content-card">
    <div class="page-actions">
        <form method="GET" action="{{ route('servicios.index') }}" class="search-form">
            <input 
                type="text" 
                name="buscar" 
                value="{{ $buscar }}" 
                placeholder="Buscar servicio por nombre o descripción"
            >

            <button type="submit" class="btn btn-secondary">
                Buscar
            </button>
        </form>

        <a href="{{ route('servicios.create') }}" class="btn btn-primary">
            Agregar servicio
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Duración</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($servicios as $servicio)
                <tr>
                    <td>
                        <strong>{{ $servicio->nombre }}</strong>
                    </td>

                    <td>
                        {{ $servicio->descripcion ?? 'Sin descripción' }}
                    </td>

                    <td>
                        ${{ number_format($servicio->precio, 2) }}
                    </td>

                    <td>
                        {{ $servicio->duracion_minutos }} min
                    </td>

                    <td>
                        <span class="badge badge-completada">
                            Activo
                        </span>
                    </td>

                    <td>
                        <div class="actions">
                            <a href="{{ route('servicios.edit', $servicio->id_servicio) }}" class="btn btn-primary btn-sm">
                                Editar
                            </a>

                            <form method="POST" action="{{ route('servicios.destroy', $servicio->id_servicio) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este servicio?');">
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
                    <td colspan="6">No hay servicios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $servicios->links() }}
    </div>
</div>

@endsection