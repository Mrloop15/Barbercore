@extends('layouts.app')

@section('title', 'Canjear recompensa | BarberCore')
@section('page-title', 'Canjear recompensa')

@section('content')

<div class="content-card">
    <div class="page-actions">
        <div>
            <h3 style="margin: 0;">Canje de puntos</h3>
            <p style="margin: 6px 0 0; color: var(--gris);">
                Selecciona un cliente y una recompensa disponible.
            </p>
        </div>

        <a href="{{ route('recompensas.index') }}" class="btn btn-secondary">
            Volver a recompensas
        </a>
    </div>

    <form method="POST" action="{{ route('recompensas.canjear') }}">
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
                        <option 
                            value="{{ $cliente->id_cliente }}"
                            data-puntos="{{ $cliente->puntos }}"
                            {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}
                        >
                            {{ $cliente->nombre }} {{ $cliente->apellido }} - {{ $cliente->puntos }} pts
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="id_recompensa">Recompensa *</label>
                <select name="id_recompensa" id="id_recompensa" required>
                    <option value="">Selecciona una recompensa</option>
                    @foreach ($recompensas as $recompensa)
                        <option 
                            value="{{ $recompensa->id_recompensa }}"
                            data-puntos="{{ $recompensa->puntos_requeridos }}"
                            data-tipo="{{ $recompensa->tipo }}"
                            {{ old('id_recompensa') == $recompensa->id_recompensa ? 'selected' : '' }}
                        >
                            {{ $recompensa->nombre }} - {{ $recompensa->puntos_requeridos }} pts
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group full">
                <div class="points-box">
                    <span style="color: var(--gris);">Puntos del cliente</span>
                    <br>
                    <strong id="puntosCliente">0 pts</strong>

                    <br><br>

                    <span style="color: var(--gris);">Puntos requeridos</span>
                    <br>
                    <strong id="puntosRequeridos">0 pts</strong>

                    <br><br>

                    <span style="color: var(--gris);">Resultado</span>
                    <br>
                    <strong id="resultadoCanje">Selecciona cliente y recompensa</strong>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                Confirmar canje
            </button>

            <a href="{{ route('recompensas.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

<div class="content-card" style="margin-top: 22px;">
    <h3 style="margin-top: 0;">Últimos canjes</h3>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Recompensa</th>
                <th>Puntos usados</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($ultimosCanjes as $canje)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($canje->fecha_canje)->format('d/m/Y H:i') }}</td>

                    <td>
                        {{ $canje->cliente->nombre ?? 'Sin cliente' }}
                        {{ $canje->cliente->apellido ?? '' }}
                    </td>

                    <td>{{ $canje->recompensa->nombre ?? 'Recompensa no disponible' }}</td>

                    <td>
                        <span class="reward-points">
                            {{ $canje->puntos_usados }} pts
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Aún no hay canjes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    const clienteSelect = document.getElementById('id_cliente');
    const recompensaSelect = document.getElementById('id_recompensa');

    const puntosCliente = document.getElementById('puntosCliente');
    const puntosRequeridos = document.getElementById('puntosRequeridos');
    const resultadoCanje = document.getElementById('resultadoCanje');

    function actualizarResumenCanje() {
        const clienteOption = clienteSelect.options[clienteSelect.selectedIndex];
        const recompensaOption = recompensaSelect.options[recompensaSelect.selectedIndex];

        const puntosDisponibles = parseInt(clienteOption.getAttribute('data-puntos') || 0);
        const puntosNecesarios = parseInt(recompensaOption.getAttribute('data-puntos') || 0);

        puntosCliente.textContent = puntosDisponibles + ' pts';
        puntosRequeridos.textContent = puntosNecesarios + ' pts';

        if (!clienteSelect.value || !recompensaSelect.value) {
            resultadoCanje.textContent = 'Selecciona cliente y recompensa';
            resultadoCanje.style.color = 'var(--gris)';
            return;
        }

        if (puntosDisponibles >= puntosNecesarios) {
            resultadoCanje.textContent = 'El cliente puede canjear esta recompensa';
            resultadoCanje.style.color = 'var(--verde)';
        } else {
            resultadoCanje.textContent = 'Puntos insuficientes para este canje';
            resultadoCanje.style.color = 'var(--rojo)';
        }
    }

    clienteSelect.addEventListener('change', actualizarResumenCanje);
    recompensaSelect.addEventListener('change', actualizarResumenCanje);

    actualizarResumenCanje();
</script>

@endsection