@extends('layouts.app')

@section('title', 'Nueva venta | BarberCore')
@section('page-title', 'Nueva venta de producto')

@section('content')

<div class="content-card">
    <form method="POST" action="{{ route('ventas-productos.store') }}" id="ventaForm">
        @csrf

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="form-grid">
            <div class="form-group full">
                <label for="id_cliente">Cliente</label>
                <select name="id_cliente" id="id_cliente">
                    <option value="">Cliente general / venta sin cliente registrado</option>

                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id_cliente }}" {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                            {{ $cliente->nombre }} {{ $cliente->apellido }} - {{ $cliente->puntos }} pts
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3>Productos vendidos</h3>

        <div id="productosContainer">
            <div class="form-grid producto-row">
                <div class="form-group">
                    <label>Producto *</label>
                    <select name="productos[]" class="producto-select" required>
                        <option value="">Selecciona un producto</option>

                        @foreach ($productos as $producto)
                            <option 
                                value="{{ $producto->id_producto }}"
                                data-precio="{{ $producto->precio_venta }}"
                                data-stock="{{ $producto->stock }}"
                            >
                                {{ $producto->nombre }} - ${{ number_format($producto->precio_venta, 2) }} - Stock: {{ $producto->stock }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Cantidad *</label>
                    <input type="number" name="cantidades[]" class="cantidad-input" min="1" value="1" required>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary" id="agregarProducto">
                Agregar otro producto
            </button>
        </div>

        <div class="points-box" style="margin-top: 18px;">
            <span style="color: var(--gris);">Total estimado</span>
            <br>
            <strong id="totalVenta">$0.00</strong>

            <br><br>

            <span style="color: var(--gris);">Puntos estimados para el cliente</span>
            <br>
            <strong id="puntosEstimados">0 pts</strong>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Guardar venta
            </button>

            <a href="{{ route('ventas-productos.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
    const productosContainer = document.getElementById('productosContainer');
    const agregarProductoBtn = document.getElementById('agregarProducto');
    const totalVenta = document.getElementById('totalVenta');
    const puntosEstimados = document.getElementById('puntosEstimados');

    function actualizarTotal() {
        let total = 0;

        document.querySelectorAll('.producto-row').forEach(row => {
            const select = row.querySelector('.producto-select');
            const cantidadInput = row.querySelector('.cantidad-input');

            const selected = select.options[select.selectedIndex];
            const precio = parseFloat(selected.getAttribute('data-precio') || 0);
            const cantidad = parseInt(cantidadInput.value || 0);

            total += precio * cantidad;
        });

        totalVenta.textContent = '$' + total.toFixed(2);
        puntosEstimados.textContent = Math.floor(total / 50) + ' pts';
    }

    function activarEventos() {
        document.querySelectorAll('.producto-select, .cantidad-input').forEach(input => {
            input.removeEventListener('change', actualizarTotal);
            input.removeEventListener('input', actualizarTotal);

            input.addEventListener('change', actualizarTotal);
            input.addEventListener('input', actualizarTotal);
        });
    }

    agregarProductoBtn.addEventListener('click', () => {
        const primeraFila = document.querySelector('.producto-row');
        const nuevaFila = primeraFila.cloneNode(true);

        nuevaFila.querySelector('.producto-select').value = '';
        nuevaFila.querySelector('.cantidad-input').value = 1;

        productosContainer.appendChild(nuevaFila);

        activarEventos();
        actualizarTotal();
    });

    activarEventos();
    actualizarTotal();
</script>

@endsection