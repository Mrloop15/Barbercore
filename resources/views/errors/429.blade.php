@extends('errors.layout')

@section('code', '429')
@section('eyebrow', 'Demasiadas solicitudes')
@section('title', 'Vamos con un poco de calma')
@section('message', 'Recibimos varias solicitudes en poco tiempo. Espera un momento antes de intentarlo otra vez.')
@section('icon')
    <svg viewBox="0 0 24 24"><path d="M3 12h7M14 12h7M12 3v7M12 14v7"/><circle cx="12" cy="12" r="2"/></svg>
@endsection
@section('actions')
    <button class="action" type="button" onclick="location.reload()"><svg viewBox="0 0 24 24"><path d="M21 12a9 9 0 0 1-15.3 6.4L3 16"/><path d="M3 21v-5h5M3 12A9 9 0 0 1 18.3 5.6L21 8M21 3v5h-5"/></svg>Intentar de nuevo</button>
    <a class="action secondary" href="/"><svg viewBox="0 0 24 24"><path d="m3 11 9-8 9 8"/><path d="M5 10v11h14V10"/></svg>Ir al inicio</a>
@endsection
