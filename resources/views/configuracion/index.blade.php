@extends('layouts.app')

@section('title', 'Configuración | BarberCore')
@section('page-title', 'Configuración')

@section('content')

@php
    $horariosAnteriores = old('horarios');
    $tieneLogo = filled($barberia?->logo) && file_exists(public_path('storage/' . $barberia->logo));
@endphp

<style>
    .config-intro { display: flex; align-items: end; justify-content: space-between; gap: 24px; margin-bottom: 22px; padding: 24px 26px; border-radius: 20px; background: linear-gradient(120deg, var(--texto), #303030); color: var(--blanco); }
    .config-intro h3 { margin: 4px 0 7px; font-size: 24px; }
    .config-intro p { max-width: 650px; margin: 0; color: rgba(255,255,255,.68); line-height: 1.6; }
    .config-kicker { color: var(--dorado); font-size: 10px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; }
    .config-nav { display: flex; flex-wrap: wrap; gap: 8px; }
    .config-nav a { padding: 9px 12px; border: 1px solid rgba(255,255,255,.18); border-radius: 9px; color: var(--blanco); font-size: 12px; font-weight: 700; }
    .config-nav a:hover { border-color: var(--dorado); color: var(--dorado); }
    .config-stack { display: grid; gap: 22px; }
    .config-card-header { display: flex; align-items: start; justify-content: space-between; gap: 20px; margin-bottom: 22px; padding-bottom: 17px; border-bottom: 1px solid var(--borde); }
    .config-card-header h3 { margin: 0 0 5px; font-size: 19px; }
    .config-card-header p { margin: 0; color: var(--gris); font-size: 13px; line-height: 1.55; }
    .config-number { width: 34px; height: 34px; display: grid; place-items: center; flex: 0 0 auto; border-radius: 10px; background: rgba(201,162,39,.13); color: var(--dorado); font-size: 12px; font-weight: 900; }
    .business-layout { display: grid; gap: 22px; }
    .business-logo { display: grid; grid-template-columns: 110px minmax(0, 1fr); gap: 18px; align-items: center; padding: 16px; border: 1px solid var(--borde); border-radius: 16px; background: var(--fondo); }
    .logo-visual { width: 110px; height: 110px; }
    .business-logo .logo-preview, .business-logo .logo-placeholder { width: 110px; height: 110px; margin: 0; }
    .business-logo .logo-placeholder { flex-direction: column; gap: 7px; border-style: dashed; background: linear-gradient(145deg, var(--blanco), rgba(201,162,39,.09)); }
    .business-logo .logo-placeholder span { font-family: 'Manrope', sans-serif; font-size: 30px; font-weight: 900; letter-spacing: -1px; }
    .business-logo .logo-placeholder small { margin: 0; color: var(--gris); font-size: 9px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
    .business-logo small { display: block; margin-top: 8px; color: var(--gris); font-size: 11px; line-height: 1.5; }
    .business-logo .is-hidden { display: none !important; }
    .logo-upload-content { min-width: 0; }
    .logo-field-label { display: block; margin-bottom: 8px; color: var(--texto); font-size: 12px; font-weight: 800; }
    .logo-upload-input { position: absolute; width: 1px; height: 1px; min-height: 0 !important; padding: 0 !important; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0 !important; opacity: 0; }
    .logo-upload-button { width: fit-content; min-height: 42px; display: flex; align-items: center; justify-content: center; gap: 8px; margin: 0; border: 1px solid var(--texto); border-radius: 10px; padding: 10px 14px; background: var(--texto); color: var(--blanco); cursor: pointer; font-size: 11px; font-weight: 800; transition: background .18s ease, border-color .18s ease, transform .18s ease; }
    .logo-upload-button:hover { border-color: var(--dorado); background: var(--dorado); transform: translateY(-1px); }
    .logo-upload-input:focus-visible + .logo-upload-button { outline: 3px solid rgba(201,162,39,.25); outline-offset: 3px; }
    .logo-upload-button .ui-icon { width: 16px; height: 16px; }
    .logo-file-name { display: block; overflow: hidden; margin-top: 9px; color: var(--gris); font-size: 10px; line-height: 1.4; text-overflow: ellipsis; white-space: nowrap; }
    .business-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }
    .business-fields .full { grid-column: 1 / -1; }
    .field-note { display: block; margin-top: 6px; color: var(--gris); font-size: 11px; line-height: 1.45; }
    .hours-list { display: grid; gap: 9px; }
    .hours-row { display: grid; grid-template-columns: 125px 92px minmax(130px, 1fr) minmax(130px, 1fr); align-items: center; gap: 13px; padding: 12px 14px; border: 1px solid var(--borde); border-radius: 13px; background: var(--fondo); }
    .hours-day { font-weight: 800; font-size: 13px; }
    .hours-toggle { display: inline-flex; align-items: center; gap: 7px; margin: 0; font-size: 12px; cursor: pointer; }
    .hours-toggle input { width: 17px; height: 17px; accent-color: var(--dorado); }
    .hours-field span { display: block; margin-bottom: 4px; color: var(--gris); font-size: 9px; font-weight: 800; letter-spacing: .7px; text-transform: uppercase; }
    .hours-field input { padding: 9px 11px; }
    .hours-row.is-closed .hours-field { opacity: .42; }
    .faq-list { display: grid; gap: 13px; }
    .faq-editor { padding: 17px; border: 1px solid var(--borde); border-radius: 15px; background: var(--fondo); }
    .faq-editor-head { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin-bottom: 12px; }
    .faq-editor-title { color: var(--dorado); font-size: 11px; font-weight: 900; letter-spacing: 1px; text-transform: uppercase; }
    .faq-editor-grid { display: grid; grid-template-columns: 1fr 1.35fr; gap: 16px; }
    .faq-editor textarea { min-height: 92px; }
    .faq-editor-options { display: flex; align-items: center; justify-content: space-between; margin-top: 12px; }
    .faq-active { display: inline-flex; align-items: center; gap: 8px; margin: 0; font-size: 12px; }
    .faq-active input { width: 17px; height: 17px; accent-color: var(--dorado); }
    .btn-outline-danger { border: 1px solid rgba(192,57,43,.28); background: transparent; color: var(--rojo); }
    .config-add { display: flex; justify-content: flex-start; margin-top: 15px; }
    .account-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; }
    .config-empty { padding: 25px; border: 1px dashed var(--borde); border-radius: 14px; color: var(--gris); text-align: center; }
    .faq-shortcut { display: flex; align-items: center; justify-content: space-between; gap: 22px; padding: 18px; border: 1px solid var(--borde); border-radius: 15px; background: var(--fondo); }
    .faq-shortcut strong { display: block; margin-bottom: 4px; font-size: 16px; }
    .faq-shortcut span { color: var(--gris); font-size: 12px; }

    @media (max-width: 950px) {
        .config-intro { align-items: start; flex-direction: column; }
        .account-grid { grid-template-columns: 1fr; }
        .faq-shortcut { align-items: stretch; flex-direction: column; }
    }

    @media (max-width: 700px) {
        .business-fields, .faq-editor-grid { grid-template-columns: 1fr; }
        .business-fields .full { grid-column: auto; }
        .hours-row { grid-template-columns: 1fr 1fr; }
        .hours-day { grid-column: 1; }
        .hours-toggle { justify-self: end; }
        .config-intro { padding: 20px; }
    }

    @media (max-width: 460px) {
        .business-logo { grid-template-columns: 1fr; }
        .logo-visual { width: 100%; height: 120px; }
        .business-logo .logo-preview, .business-logo .logo-placeholder { width: 100%; height: 120px; }
        .hours-row { grid-template-columns: 1fr; }
        .hours-toggle { justify-self: start; }
        .faq-editor-options { align-items: flex-start; flex-direction: column; }
    }
</style>

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
