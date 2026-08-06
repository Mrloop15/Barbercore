@extends('errors.layout')

@section('code', '403')
@section('eyebrow', 'Acceso restringido')
@section('title', 'Esta sección está reservada')
@section('message', 'Tu cuenta no tiene permiso para abrir este contenido. Regresa a una sección disponible.')
@section('icon')
    <svg viewBox="0 0 24 24"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/></svg>
@endsection
@section('actions')
    <a class="action" href="/"><svg viewBox="0 0 24 24"><path d="m3 11 9-8 9 8"/><path d="M5 10v11h14V10M9 21v-7h6v7"/></svg>Ir al inicio</a>
    <button class="action secondary" type="button" onclick="history.back()"><svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>Volver</button>
@endsection
