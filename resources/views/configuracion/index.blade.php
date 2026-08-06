@extends('layouts.app')

@section('title', 'Configuración | BarberCore')
@section('page-title', 'Configuración')

@section('content')

@php
    $horariosAnteriores = old('horarios');
    $tieneLogo = filled($barberia?->logo) && \Illuminate\Support\Facades\Storage::disk('public')->exists($barberia->logo);
@endphp
@push('styles')
<link rel="stylesheet" href="{{ asset('css/modules/configuration.css') }}">
@endpush

@if ($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<section class="config-intro">
    <div>
        <span class="config-kicker">Centro de control</span>
        <h3>Configura el negocio y su presencia pública</h3>
        <p>Los datos de ubicación, horarios y preguntas frecuentes se reflejan automáticamente en la landing.</p>
    </div>
    <nav class="config-nav" aria-label="Secciones de configuración">
        <a href="#negocio">Negocio</a>
        <a href="#horarios">Horarios</a>
        <a href="{{ route('configuracion.preguntas.index') }}">Preguntas</a>
        <a href="#cuenta">Cuenta</a>
    </nav>
</section>

<div class="config-stack">
    <section class="content-card" id="negocio">
        <header class="config-card-header">
            <div><h3>Información pública del negocio</h3><p>Identidad, contacto y enlace de ubicación que verán tus clientes.</p></div>
            <span class="config-number">01</span>
        </header>

        <form method="POST" action="{{ route('configuracion.barberia') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="business-layout">
                <div class="business-logo">
                    <div class="logo-visual">
                        <img id="config-logo-preview" @if($tieneLogo) src="{{ asset('storage/' . $barberia->logo) }}" @endif alt="Vista previa del logotipo" class="logo-preview {{ $tieneLogo ? '' : 'is-hidden' }}">
                        <div id="config-logo-placeholder" class="logo-placeholder {{ $tieneLogo ? 'is-hidden' : '' }}" role="img" aria-label="La barbería no tiene logotipo">
                            <span>BC</span>
                            <small>Sin logotipo</small>
                        </div>
                    </div>
                    <div class="logo-upload-content">
                        <span class="logo-field-label">Logotipo del negocio</span>
                        <input class="logo-upload-input" type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/webp" data-skip-auto-preview>
                        <label class="logo-upload-button" for="logo"><x-icon name="plus" />{{ $tieneLogo ? 'Elegir otro logotipo' : 'Seleccionar logotipo' }}</label>
                        <span class="logo-file-name" id="logo-file-name">Ningún archivo nuevo seleccionado</span>
                        <small>JPG, PNG o WebP · máximo 2 MB.</small>
                    </div>
                </div>

                <div class="business-fields">
                    <div class="form-group">
                        <label for="nombre">Nombre de la barbería *</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $barberia?->nombre) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono / WhatsApp</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $barberia?->telefono) }}" placeholder="Ej. 521234567890">
                        <span class="field-note">Incluye lada y código de país para abrir WhatsApp correctamente.</span>
                    </div>
                    <div class="form-group full">
                        <label for="direccion">Dirección</label>
                        <textarea name="direccion" id="direccion">{{ old('direccion', $barberia?->direccion) }}</textarea>
                    </div>
                    <div class="form-group full">
                        <label for="google_maps_url">Enlace de Google Maps</label>
                        <input type="url" name="google_maps_url" id="google_maps_url" value="{{ old('google_maps_url', $barberia?->google_maps_url) }}" placeholder="https://maps.app.goo.gl/…">
                        <span class="field-note">Abre tu negocio en Google Maps, pulsa Compartir y pega aquí el enlace.</span>
                    </div>
                </div>
            </div>
            <div class="form-actions"><button type="submit" class="btn btn-primary">Guardar información pública</button></div>
        </form>
    </section>

    <section class="content-card" id="horarios">
        <header class="config-card-header">
            <div><h3>Horarios de atención</h3><p>Define qué días abres y el horario que aparecerá en la landing.</p></div>
            <span class="config-number">02</span>
        </header>

        <form method="POST" action="{{ route('configuracion.horarios') }}">
            @csrf
            @method('PUT')
            <div class="hours-list">
                @foreach ($diasSemana as $dia => $nombreDia)
                    @php
                        $horario = $horarios->get($dia);
                        $abierto = $horariosAnteriores !== null
                            ? (bool) data_get($horariosAnteriores, "$dia.abierto", false)
                            : ($horario?->abierto ?? ($dia !== 6));
                        $apertura = old("horarios.$dia.hora_apertura", $horario?->hora_apertura ? substr($horario->hora_apertura, 0, 5) : '09:00');
                        $cierre = old("horarios.$dia.hora_cierre", $horario?->hora_cierre ? substr($horario->hora_cierre, 0, 5) : '19:00');
                    @endphp
                    <div class="hours-row {{ $abierto ? '' : 'is-closed' }}" data-hours-row>
                        <input type="hidden" name="horarios[{{ $dia }}][dia_semana]" value="{{ $dia }}">
                        <span class="hours-day">{{ $nombreDia }}</span>
                        <label class="hours-toggle">
                            <input type="checkbox" name="horarios[{{ $dia }}][abierto]" value="1" @checked($abierto) data-hours-toggle>
                            <span data-hours-state>{{ $abierto ? 'Abierto' : 'Cerrado' }}</span>
                        </label>
                        <label class="hours-field"><span>Apertura</span><input type="time" name="horarios[{{ $dia }}][hora_apertura]" value="{{ $apertura }}" @disabled(!$abierto)></label>
                        <label class="hours-field"><span>Cierre</span><input type="time" name="horarios[{{ $dia }}][hora_cierre]" value="{{ $cierre }}" @disabled(!$abierto)></label>
                    </div>
                @endforeach
            </div>
            <div class="form-actions"><button type="submit" class="btn btn-primary">Guardar horarios</button></div>
        </form>
    </section>

    <section class="content-card" id="preguntas">
        <header class="config-card-header">
            <div><h3>Preguntas frecuentes</h3><p>La administración de preguntas se encuentra en una pantalla independiente.</p></div>
            <span class="config-number">03</span>
        </header>
        <div class="faq-shortcut">
            <div><strong>{{ $totalPreguntas }} {{ $totalPreguntas === 1 ? 'pregunta registrada' : 'preguntas registradas' }}</strong><span>Agrega, edita, elimina y controla cuáles se muestran en la landing.</span></div>
            <a class="btn btn-primary" href="{{ route('configuracion.preguntas.index') }}">Administrar preguntas</a>
        </div>
    </section>

    <section id="cuenta">
        <div class="account-grid">
            <div class="content-card">
                <header class="config-card-header"><div><h3>Información de usuario</h3><p>Datos de acceso del usuario actual.</p></div><span class="config-number">04</span></header>
                <form method="POST" action="{{ route('configuracion.usuario') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group"><label for="usuario_nombre">Nombre *</label><input type="text" name="nombre" id="usuario_nombre" value="{{ old('nombre', $usuario->nombre) }}" required></div>
                    <div class="form-group"><label for="correo">Correo electrónico *</label><input type="email" name="correo" id="correo" value="{{ old('correo', $usuario->correo) }}" required></div>
                    <div class="form-group"><label>Rol</label><input type="text" value="{{ ucfirst($usuario->rol) }}" disabled></div>
                    <div class="form-actions"><button type="submit" class="btn btn-primary">Guardar usuario</button></div>
                </form>
            </div>

            <div class="content-card">
                <header class="config-card-header"><div><h3>Seguridad</h3><p>Actualiza tu contraseña del panel.</p></div><span class="config-number">05</span></header>
                <form method="POST" action="{{ route('configuracion.password') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group"><label for="password_actual">Contraseña actual *</label><input type="password" name="password_actual" id="password_actual" required></div>
                    <div class="form-group"><label for="password">Nueva contraseña *</label><input type="password" name="password" id="password" required></div>
                    <div class="form-group"><label for="password_confirmation">Confirmar contraseña *</label><input type="password" name="password_confirmation" id="password_confirmation" required></div>
                    <div class="form-actions"><button type="submit" class="btn btn-primary">Actualizar contraseña</button></div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const logoInput = document.getElementById('logo');
        const logoPreview = document.getElementById('config-logo-preview');
        const logoPlaceholder = document.getElementById('config-logo-placeholder');
        const logoFileName = document.getElementById('logo-file-name');
        let logoPreviewUrl;

        logoInput?.addEventListener('change', () => {
            const file = logoInput.files?.[0];
            if (!file) return;
            if (logoPreviewUrl) URL.revokeObjectURL(logoPreviewUrl);
            logoPreviewUrl = URL.createObjectURL(file);
            logoPreview.src = logoPreviewUrl;
            logoPreview.classList.remove('is-hidden');
            logoPlaceholder.classList.add('is-hidden');
            logoFileName.textContent = file.name;
        });

        document.querySelectorAll('[data-hours-row]').forEach((row) => {
            const toggle = row.querySelector('[data-hours-toggle]');
            const state = row.querySelector('[data-hours-state]');
            const timeInputs = row.querySelectorAll('input[type="time"]');
            const update = () => {
                row.classList.toggle('is-closed', !toggle.checked);
                state.textContent = toggle.checked ? 'Abierto' : 'Cerrado';
                timeInputs.forEach((input) => input.disabled = !toggle.checked);
            };
            toggle.addEventListener('change', update);
            update();
        });

        const section = new URLSearchParams(window.location.search).get('seccion');
        if (section) document.getElementById(section)?.scrollIntoView({ block: 'start' });
    });
</script>

@endsection
