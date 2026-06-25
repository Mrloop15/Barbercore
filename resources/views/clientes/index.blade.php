@extends('layouts.app')

@section('title', 'Clientes | BarberCore')
@section('page-title', 'Clientes')

@section('content')

<div class="content-card">
    <div class="page-actions">
        <form method="GET" action="{{ route('clientes.index') }}" class="search-form">
            <input 
                type="text" 
                name="buscar" 
                value="{{ $buscar }}" 
                placeholder="Buscar por nombre, apellido o teléfono"
            >

            <button type="submit" class="btn btn-secondary">
                Buscar
            </button>
        </form>

        <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            Agregar cliente
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Puntos</th>
                <th>Última visita</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($clientes as $cliente)
                <tr>
                    <td>
                        @if ($cliente->foto)
                            <img src="{{ asset('storage/' . $cliente->foto) }}" alt="Foto cliente" class="client-photo">
                        @else
                            <div class="empty-photo">
                                {{ strtoupper(substr($cliente->nombre, 0, 1)) }}
                            </div>
                        @endif
                    </td>

                    <td>
                        <strong>{{ $cliente->nombre }} {{ $cliente->apellido }}</strong>
                        <br>
                        <span style="color: var(--gris); font-size: 13px;">
                            Cumpleaños: 
                            {{ $cliente->cumpleanos ? \Carbon\Carbon::parse($cliente->cumpleanos)->format('d/m/Y') : 'No registrado' }}
                        </span>
                    </td>

                    <td>{{ $cliente->telefono ?? 'No registrado' }}</td>

                    <td>{{ $cliente->puntos }}</td>

                    <td>
                        {{ $cliente->ultima_visita ? \Carbon\Carbon::parse($cliente->ultima_visita)->format('d/m/Y') : 'Sin visitas' }}
                    </td>

                    <td>
                        <div class="actions">
                            <a href="{{ route('clientes.show', $cliente->id_cliente) }}" class="btn btn-secondary btn-sm">
                                Ver
                            </a>

                            <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="btn btn-primary btn-sm">
                                Editar
                            </a>

                            <form method="POST" action="{{ route('clientes.destroy', $cliente->id_cliente) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?');">
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
                    <td colspan="6">No hay clientes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $clientes->links() }}
    </div>
</div>

@endsection