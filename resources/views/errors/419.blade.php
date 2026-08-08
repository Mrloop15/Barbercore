@extends('errors.layout')

@section('code', '419')
@section('eyebrow', 'Sesión finalizada')
@section('title', 'Tu acceso perdió vigencia')
@section('message', 'Por seguridad cerramos la solicitud anterior. Inicia sesión nuevamente para continuar.')
@section('icon')
    <span class="material-symbol">timer_off</span>
@endsection
@section('actions')
    <a class="action" href="/login"><span class="material-symbol" aria-hidden="true">login</span>Iniciar sesión</a>
@endsection
