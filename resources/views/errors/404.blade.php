@extends('errors.layout')

@section('code', '404')
@section('eyebrow', 'Página no encontrada')
@section('title', 'Esta silla está vacía')
@section('message', 'La página que buscas cambió de lugar, ya no existe o la dirección está incompleta.')
@section('icon')
    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M8.5 8.5 13.5 13.5M13.5 8.5l-5 5"/></svg>
@endsection
@section('actions')
    <a class="action" href="/"><svg viewBox="0 0 24 24"><path d="m3 11 9-8 9 8"/><path d="M5 10v11h14V10M9 21v-7h6v7"/></svg>Ir al inicio</a>
    <button class="action secondary" type="button" onclick="history.back()"><svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>Volver</button>
@endsection
