@extends('layouts.app')

@section('title', 'Editar producto | BarberCore')
@section('page-title', 'Editar producto')

@section('content')

<div class="content-card">
    <form method="POST" action="{{ route('productos.update', $producto->id_producto) }}">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="form-grid">
            <div class="form-group">
                <label for="nombre">Nombre del producto *</label>
                <input 
                    type="text" 
                    name="nombre" 
                    id="nombre" 
                    value="{{ old('nombre', $producto->nombre) }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="precio_compra">Precio de compra *</label>
                <input 
                    type="number" 
                    step="0.01" 
                    min="0" 
                    name="precio_compra" 
                    id="precio_compra" 
                    value="{{ old('precio_compra', $producto->precio_compra) }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="precio_venta">Precio de venta *</label>
                <input 
                    type="number" 
                    step="0.01" 
                    min="0" 
                    name="precio_venta" 
                    id="precio_venta" 
                    value="{{ old('precio_venta', $producto->precio_venta) }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="stock">Stock actual *</label>
                <input 
                    type="number" 
                    min="0" 
                    name="stock" 
                    id="stock" 
                    value="{{ old('stock', $producto->stock) }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="stock_minimo">Stock mínimo *</label>
                <input 
                    type="number" 
                    min="0" 
                    name="stock_minimo" 
                    id="stock_minimo" 
                    value="{{ old('stock_minimo', $producto->stock_minimo) }}" 
                    required
                >
            </div>

            <div class="form-group full">
                <label for="descripcion">Descripción</label>
                <textarea 
                    name="descripcion" 
                    id="descripcion"
                >{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Actualizar producto
            </button>

            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection