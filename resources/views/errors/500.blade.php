@extends('errors.layout')

@section('code', '500')
@section('eyebrow', 'Error del sistema')
@section('title', 'Necesitamos ajustar algo')
@section('message', 'BarberCore encontró un problema inesperado. Tus datos permanecen seguros mientras lo resolvemos.')
@section('icon')
    <svg viewBox="0 0 24 24"><path d="M14.7 6.3a4 4 0 0 0-5 5L3 18l3 3 6.7-6.7a4 4 0 0 0 5-5l-2.4 2.4-3-3 2.4-2.4Z"/></svg>
@endsection
@section('actions')
    <button class="action" type="button" onclick="location.reload()"><svg viewBox="0 0 24 24"><path d="M21 12a9 9 0 0 1-15.3 6.4L3 16"/><path d="M3 21v-5h5M3 12A9 9 0 0 1 18.3 5.6L21 8M21 3v5h-5"/></svg>Intentar de nuevo</button>
    <a class="action secondary" href="/"><svg viewBox="0 0 24 24"><path d="m3 11 9-8 9 8"/><path d="M5 10v11h14V10"/></svg>Ir al inicio</a>
@endsection
