@extends('errors.layout')

@section('code', '503')
@section('eyebrow', 'Servicio temporalmente pausado')
@section('title', 'Estamos preparando todo')
@section('message', 'BarberCore está recibiendo mantenimiento. Estaremos disponibles nuevamente en unos minutos.')
@section('icon')
    <svg viewBox="0 0 24 24"><path d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/><circle cx="12" cy="12" r="3"/></svg>
@endsection
@section('actions')
    <button class="action" type="button" onclick="location.reload()"><svg viewBox="0 0 24 24"><path d="M21 12a9 9 0 0 1-15.3 6.4L3 16"/><path d="M3 21v-5h5M3 12A9 9 0 0 1 18.3 5.6L21 8M21 3v5h-5"/></svg>Comprobar de nuevo</button>
@endsection
