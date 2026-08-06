@extends('errors.layout')

@section('code', '419')
@section('eyebrow', 'Sesión finalizada')
@section('title', 'Tu acceso perdió vigencia')
@section('message', 'Por seguridad cerramos la solicitud anterior. Inicia sesión nuevamente para continuar.')
@section('icon')
    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/><path d="M4.6 4.6 3 3"/></svg>
@endsection
@section('actions')
    <a class="action" href="/login"><svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5M15 12H3M15 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4"/></svg>Iniciar sesión</a>
@endsection
