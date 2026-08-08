@extends('errors.layout')

@section('code', '429')
@section('eyebrow', 'Demasiadas solicitudes')
@section('title', 'Vamos con un poco de calma')
@section('message', 'Recibimos varias solicitudes en poco tiempo. Espera un momento antes de intentarlo otra vez.')
@section('icon')
    <span class="material-symbol">hourglass_top</span>
@endsection
@section('actions')
    <button class="action" type="button" onclick="location.reload()"><span class="material-symbol" aria-hidden="true">refresh</span>Intentar de nuevo</button>
    <a class="action secondary" href="/"><span class="material-symbol" aria-hidden="true">home</span>Ir al inicio</a>
@endsection
