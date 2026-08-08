@extends('errors.layout')

@section('code', '500')
@section('eyebrow', 'Error del sistema')
@section('title', 'Necesitamos ajustar algo')
@section('message', 'BarberCore encontró un problema inesperado. Tus datos permanecen seguros mientras lo resolvemos.')
@section('icon')
    <span class="material-symbol">build_circle</span>
@endsection
@section('actions')
    <button class="action" type="button" onclick="location.reload()"><span class="material-symbol" aria-hidden="true">refresh</span>Intentar de nuevo</button>
    <a class="action secondary" href="/"><span class="material-symbol" aria-hidden="true">home</span>Ir al inicio</a>
@endsection
