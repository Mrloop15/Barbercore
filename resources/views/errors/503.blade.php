@extends('errors.layout')

@section('code', '503')
@section('eyebrow', 'Servicio temporalmente pausado')
@section('title', 'Estamos preparando todo')
@section('message', 'BarberCore está recibiendo mantenimiento. Estaremos disponibles nuevamente en unos minutos.')
@section('icon')
    <span class="material-symbol">engineering</span>
@endsection
@section('actions')
    <button class="action" type="button" onclick="location.reload()"><span class="material-symbol" aria-hidden="true">refresh</span>Comprobar de nuevo</button>
@endsection
