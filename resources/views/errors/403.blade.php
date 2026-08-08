@extends('errors.layout')

@section('code', '403')
@section('eyebrow', 'Acceso restringido')
@section('title', 'Esta sección está reservada')
@section('message', 'Tu cuenta no tiene permiso para abrir este contenido. Regresa a una sección disponible.')
@section('icon')
    <span class="material-symbol">lock</span>
@endsection
@section('actions')
    <a class="action" href="/"><span class="material-symbol" aria-hidden="true">home</span>Ir al inicio</a>
    <button class="action secondary" type="button" onclick="history.back()"><span class="material-symbol" aria-hidden="true">arrow_back</span>Volver</button>
@endsection
