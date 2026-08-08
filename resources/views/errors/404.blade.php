@extends('errors.layout')

@section('code', '404')
@section('eyebrow', 'Página no encontrada')
@section('title', 'Esta silla está vacía')
@section('message', 'La página que buscas cambió de lugar, ya no existe o la dirección está incompleta.')
@section('icon')
    <span class="material-symbol">search_off</span>
@endsection
@section('actions')
    <a class="action" href="/"><span class="material-symbol" aria-hidden="true">home</span>Ir al inicio</a>
    <button class="action secondary" type="button" onclick="history.back()"><span class="material-symbol" aria-hidden="true">arrow_back</span>Volver</button>
@endsection
