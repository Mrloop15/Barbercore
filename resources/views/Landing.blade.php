<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cortes, barba y cuidado personal en {{ $barberia?->nombre ?? 'BarberCore Studio' }}.">
    <title>{{ $barberia?->nombre ?? 'BarberCore Studio' }} | Barbería profesional</title>
    <link rel="icon" type="image/png" href="{{ asset('images/branding/icon_192_Barbercore.png') }}">

    <style>
        :root {
            --dorado: #C9A227;
            --fondo: #FAF8F2;
            --blanco: #FFFFFF;
            --texto: #1C1C1C;
            --gris: #6B6B6B;
            --borde: #E5E0D6;
            --oscuro: #111111;
            --verde: #25D366;
        }

        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; background: var(--fondo); color: var(--texto); font-family: Arial, Helvetica, sans-serif; -webkit-font-smoothing: antialiased; }
        a { color: inherit; text-decoration: none; }
        svg, img { display: block; }
        img { max-width: 100%; }
        :focus-visible { outline: 3px solid rgba(201, 162, 39, .5); outline-offset: 3px; }

        .container { width: min(1200px, calc(100% - 48px)); margin-inline: auto; }
        .icon { width: 20px; height: 20px; flex: 0 0 auto; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

        .topbar { background: var(--oscuro); color: var(--blanco); }
        .topbar-inner { min-height: 38px; display: flex; align-items: center; justify-content: space-between; gap: 24px; font-size: 11px; font-weight: 700; letter-spacing: .06em; }
        .topbar-group { display: flex; align-items: center; gap: 24px; }
        .topbar-item { display: inline-flex; align-items: center; gap: 7px; color: var(--borde); }
        .topbar-item .icon { width: 14px; height: 14px; color: var(--dorado); }
        .topbar-tag { color: var(--dorado); text-transform: uppercase; letter-spacing: .14em; }

        .header { position: sticky; top: 0; z-index: 50; border-bottom: 1px solid var(--borde); background: rgba(255, 255, 255, .96); backdrop-filter: blur(12px); }
        .header-inner { min-height: 88px; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 30px; }
        .nav-list { display: flex; align-items: center; gap: 30px; }
        .nav-list:last-child { justify-content: flex-end; }
        .nav-link { color: var(--gris); font-size: 12px; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; transition: color .2s ease; }
        .nav-link:hover { color: var(--dorado); }

        .brand { display: inline-flex; align-items: center; gap: 11px; justify-self: center; }
        .brand-logo { width: 62px; height: 62px; object-fit: contain; }
        .brand-name { font-family: Georgia, 'Times New Roman', serif; font-size: 21px; font-weight: 700; letter-spacing: -.02em; white-space: nowrap; }
        .brand-name small { display: block; margin-top: 3px; color: var(--dorado); font-family: Arial, sans-serif; font-size: 9px; font-weight: 900; letter-spacing: .22em; text-align: center; text-transform: uppercase; }

        .btn { min-height: 46px; display: inline-flex; align-items: center; justify-content: center; gap: 9px; border: 1px solid transparent; padding: 12px 19px; font-size: 12px; font-weight: 900; letter-spacing: .06em; text-transform: uppercase; transition: transform .2s ease, background .2s ease, color .2s ease, box-shadow .2s ease; }
        .btn:hover { transform: translateY(-2px); }
        .btn-gold { background: var(--dorado); color: var(--blanco); box-shadow: 0 12px 28px rgba(201, 162, 39, .22); }
        .btn-dark { background: var(--oscuro); color: var(--blanco); }
        .btn-light { border-color: rgba(255, 255, 255, .48); background: rgba(17, 17, 17, .12); color: var(--blanco); backdrop-filter: blur(7px); }

        .hero { position: relative; min-height: 720px; display: flex; align-items: center; isolation: isolate; overflow: hidden; background: var(--oscuro); color: var(--blanco); }
        .hero::before { content: ""; position: absolute; inset: 0; z-index: -2; background: url('{{ asset('images/landing/hero-barbershop-premium.png') }}') center center / cover no-repeat; }
        .hero::after { content: ""; position: absolute; inset: 0; z-index: -1; background: linear-gradient(90deg, rgba(17,17,17,.98) 0%, rgba(17,17,17,.90) 28%, rgba(17,17,17,.42) 59%, rgba(17,17,17,.12) 100%); }
        .hero-content { width: min(650px, 58%); padding: 105px 0 145px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 12px; margin-bottom: 24px; color: var(--dorado); font-size: 11px; font-weight: 900; letter-spacing: .2em; text-transform: uppercase; }
        .eyebrow::before { content: ""; width: 42px; height: 1px; background: var(--dorado); }
        .hero h1 { margin: 0; font-family: Georgia, 'Times New Roman', serif; font-size: clamp(54px, 6.5vw, 88px); font-weight: 700; letter-spacing: -.05em; line-height: .96; }
        .hero h1 em { color: var(--dorado); font-style: normal; }
        .hero-copy { max-width: 570px; margin: 28px 0 32px; color: var(--borde); font-size: 17px; line-height: 1.75; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; }
        .hero-signature { display: flex; align-items: center; gap: 13px; margin-top: 38px; color: var(--borde); font-family: Georgia, serif; font-size: 14px; font-style: italic; }
        .hero-signature::before { content: ""; width: 52px; height: 1px; background: rgba(229, 224, 214, .45); }

        .trust-wrap { position: relative; z-index: 5; margin-top: -56px; }
        .trust-bar { display: grid; grid-template-columns: repeat(3, 1fr); border: 1px solid var(--borde); background: var(--blanco); box-shadow: 0 22px 55px rgba(17,17,17,.11); }
        .trust-item { min-height: 112px; display: grid; grid-template-columns: 48px 1fr; gap: 15px; align-items: center; padding: 24px 30px; }
        .trust-item + .trust-item { border-left: 1px solid var(--borde); }
        .trust-icon { width: 45px; height: 45px; display: grid; place-items: center; border: 1px solid var(--borde); border-radius: 50%; color: var(--dorado); }
        .trust-item strong { display: block; margin-bottom: 5px; font-family: Georgia, serif; font-size: 16px; }
        .trust-item span { color: var(--gris); font-size: 12px; line-height: 1.5; }

        .section { padding: 104px 0; }
        .section-head { display: grid; grid-template-columns: 1fr .72fr; gap: 80px; align-items: end; margin-bottom: 48px; }
        .kicker { display: block; margin-bottom: 13px; color: var(--dorado); font-size: 10px; font-weight: 900; letter-spacing: .22em; text-transform: uppercase; }
        .title { margin: 0; font-family: Georgia, 'Times New Roman', serif; font-size: clamp(39px, 4.5vw, 58px); letter-spacing: -.04em; line-height: 1.03; }
        .copy { margin: 0; color: var(--gris); font-size: 15px; line-height: 1.8; }

        .services { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
        .service { min-height: 470px; display: flex; flex-direction: column; overflow: hidden; border: 1px solid var(--borde); background: var(--blanco); box-shadow: 0 10px 28px rgba(17,17,17,.045); transition: background .25s ease, transform .25s ease, box-shadow .25s ease; }
        .service:hover { position: relative; z-index: 2; background: var(--oscuro); color: var(--blanco); transform: translateY(-7px); box-shadow: 0 24px 46px rgba(17,17,17,.15); }
        .service-media { position: relative; height: 205px; overflow: hidden; background: var(--oscuro); }
        .service-media::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 55%, rgba(17,17,17,.38)); pointer-events: none; }
        .service-media img { width: 100%; height: 100%; object-fit: cover; transition: transform .45s ease; }
        .service:hover .service-media img { transform: scale(1.045); }
        .service-body { flex: 1; display: flex; flex-direction: column; padding: 24px 24px 26px; }
        .service-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .service-icon { width: 48px; height: 48px; display: grid; place-items: center; border: 1px solid var(--borde); color: var(--dorado); }
        .service-index { color: var(--borde); font-family: Georgia, serif; font-size: 28px; }
        .service h3 { margin: 0 0 12px; font-family: Georgia, serif; font-size: 22px; line-height: 1.2; }
        .service p { margin: 0 0 22px; color: var(--gris); font-size: 13px; line-height: 1.7; }
        .service:hover p { color: var(--borde); }
        .service-footer { display: flex; align-items: end; justify-content: space-between; gap: 12px; margin-top: auto; padding-top: 18px; border-top: 1px solid var(--borde); }
        .price { color: var(--dorado); font-size: 20px; font-weight: 900; }
        .duration { display: inline-flex; align-items: center; gap: 6px; color: var(--gris); font-size: 11px; }
        .duration .icon { width: 14px; height: 14px; }

        .standard { background: var(--blanco); }
        .standard-grid { display: grid; grid-template-columns: .85fr 1.15fr; min-height: 570px; }
        .standard-dark { position: relative; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; padding: 64px; background: var(--oscuro); color: var(--blanco); }
        .standard-dark::after { content: "BC"; position: absolute; right: -28px; bottom: -62px; color: rgba(201,162,39,.09); font-family: Georgia, serif; font-size: 250px; font-weight: 700; line-height: 1; }
        .standard-logo { position: relative; z-index: 1; width: 118px; height: 118px; object-fit: contain; }
        .standard-quote { position: relative; z-index: 1; max-width: 450px; }
        .standard-quote span { color: var(--dorado); font-size: 10px; font-weight: 900; letter-spacing: .2em; text-transform: uppercase; }
        .standard-quote h2 { margin: 17px 0 0; font-family: Georgia, serif; font-size: clamp(36px, 4vw, 53px); letter-spacing: -.04em; line-height: 1.07; }
        .standards-list { display: grid; align-content: center; padding: 55px 70px; background: var(--fondo); }
        .standard-item { display: grid; grid-template-columns: 54px 1fr; gap: 20px; padding: 27px 0; border-bottom: 1px solid var(--borde); }
        .standard-item:last-child { border-bottom: 0; }
        .standard-number { color: var(--dorado); font-family: Georgia, serif; font-size: 18px; }
        .standard-item h3 { margin: 0 0 7px; font-family: Georgia, serif; font-size: 19px; }
        .standard-item p { margin: 0; color: var(--gris); font-size: 13px; line-height: 1.7; }

        .contact-section { padding: 96px 0; }
        .contact-card { display: grid; grid-template-columns: 1fr 1fr; overflow: hidden; border: 1px solid var(--borde); background: var(--blanco); box-shadow: 0 24px 60px rgba(17,17,17,.08); }
        .contact-copy { padding: 60px; }
        .contact-copy .copy { max-width: 470px; margin: 20px 0 30px; }
        .contact-details { display: grid; border-left: 1px solid var(--borde); }
        .detail { display: grid; grid-template-columns: 48px 1fr; gap: 16px; align-items: center; padding: 27px 36px; border-bottom: 1px solid var(--borde); }
        .detail:last-child { border-bottom: 0; }
        .detail-icon { width: 46px; height: 46px; display: grid; place-items: center; border-radius: 50%; background: var(--fondo); color: var(--dorado); }
        .detail small { display: block; margin-bottom: 5px; color: var(--gris); font-size: 9px; font-weight: 900; letter-spacing: .16em; text-transform: uppercase; }
        .detail strong { font-family: Georgia, serif; font-size: 15px; line-height: 1.45; }

        .cta { background: var(--dorado); color: var(--blanco); }
        .cta-inner { min-height: 200px; display: grid; grid-template-columns: 1fr auto; gap: 40px; align-items: center; }
        .cta h2 { margin: 0 0 8px; font-family: Georgia, serif; font-size: clamp(34px, 4vw, 49px); letter-spacing: -.04em; }
        .cta p { margin: 0; font-size: 14px; opacity: .9; }

        .footer { padding: 55px 0 25px; background: var(--oscuro); color: var(--blanco); }
        .footer-grid { display: grid; grid-template-columns: 1.25fr .75fr .75fr; gap: 55px; padding-bottom: 42px; }
        .footer-brand { display: flex; align-items: center; gap: 13px; margin-bottom: 17px; }
        .footer-brand img { width: 66px; height: 66px; object-fit: contain; }
        .footer-brand strong { font-family: Georgia, serif; font-size: 22px; }
        .footer-about { max-width: 360px; margin: 0; color: var(--borde); font-size: 12px; line-height: 1.75; }
        .socials { display: flex; align-items: center; gap: 10px; margin-top: 22px; }
        .social-link { width: 40px; height: 40px; display: grid; place-items: center; border: 1px solid rgba(229,224,214,.22); border-radius: 50%; color: var(--dorado); transition: color .2s ease, background .2s ease, border-color .2s ease, transform .2s ease; }
        .social-link:hover { border-color: var(--dorado); background: var(--dorado); color: var(--oscuro); transform: translateY(-3px); }
        .social-link svg { width: 16px; height: 16px; fill: currentColor; }
        .footer-title { margin: 5px 0 17px; color: var(--dorado); font-size: 9px; font-weight: 900; letter-spacing: .2em; text-transform: uppercase; }
        .footer-links { display: grid; gap: 12px; color: var(--borde); font-size: 12px; }
        .footer-links a:hover { color: var(--dorado); }
        .footer-bottom { display: flex; justify-content: space-between; gap: 20px; padding-top: 22px; border-top: 1px solid rgba(229,224,214,.16); color: var(--gris); font-size: 10px; }

        .whatsapp { position: fixed; right: 22px; bottom: 22px; z-index: 60; width: 58px; height: 58px; display: grid; place-items: center; border: 3px solid var(--blanco); border-radius: 50%; background: var(--verde); color: var(--blanco); box-shadow: 0 14px 32px rgba(17,17,17,.25); transition: transform .2s ease; }
        .whatsapp:hover { transform: translateY(-4px); }
        .whatsapp svg { width: 27px; height: 27px; fill: currentColor; }

        @media (max-width: 1050px) {
            .header-inner { grid-template-columns: auto 1fr auto; }
            .brand { justify-self: start; }
            .nav-left { display: none; }
            .nav-right .nav-link { display: none; }
            .services { grid-template-columns: repeat(2, 1fr); }
            .standard-grid { grid-template-columns: 1fr 1fr; }
            .standard-dark { padding: 45px; }
            .standards-list { padding: 40px; }
        }

        @media (max-width: 760px) {
            .container { width: min(100% - 28px, 620px); }
            .topbar-inner { justify-content: center; }
            .topbar-group:first-child, .topbar-tag { display: none; }
            .header-inner { min-height: 74px; }
            .brand-logo { width: 50px; height: 50px; }
            .brand-name { font-size: 17px; }
            .nav-right .btn span { display: none; }
            .nav-right .btn { width: 44px; min-height: 44px; padding: 0; }
            .hero { min-height: 660px; align-items: end; }
            .hero::before { background-position: 62% center; }
            .hero::after { background: linear-gradient(0deg, rgba(17,17,17,.98) 0%, rgba(17,17,17,.85) 50%, rgba(17,17,17,.22) 100%); }
            .hero-content { width: 100%; padding: 210px 0 105px; }
            .hero h1 { font-size: clamp(47px, 13vw, 68px); }
            .hero-copy { font-size: 15px; }
            .trust-wrap { margin-top: 0; }
            .trust-bar { grid-template-columns: 1fr; }
            .trust-item { min-height: 95px; }
            .trust-item + .trust-item { border-top: 1px solid var(--borde); border-left: 0; }
            .section { padding: 72px 0; }
            .section-head { grid-template-columns: 1fr; gap: 20px; margin-bottom: 34px; }
            .standard-grid, .contact-card { grid-template-columns: 1fr; }
            .standard-dark { min-height: 440px; }
            .contact-details { border-top: 1px solid var(--borde); border-left: 0; }
            .contact-copy { padding: 42px 28px; }
            .cta-inner { grid-template-columns: 1fr; gap: 25px; padding: 45px 0; }
            .cta-inner .btn { justify-self: start; }
            .footer-grid { grid-template-columns: 1fr; gap: 32px; }
        }

        @media (max-width: 500px) {
            .brand-name small { display: none; }
            .hero-actions { display: grid; }
            .hero-actions .btn { width: 100%; }
            .hero-signature { display: none; }
            .services { grid-template-columns: 1fr; }
            .service { min-height: 455px; }
            .standard-dark { min-height: 390px; padding: 32px; }
            .standard-logo { width: 86px; height: 86px; }
            .standards-list { padding: 25px; }
            .detail { padding: 22px; }
            .footer-bottom { display: block; line-height: 1.7; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { transition-duration: .01ms !important; }
        }
    </style>
</head>
<body id="inicio">
    @php
        $nombreBarberia = $barberia?->nombre ?? 'BarberCore Studio';
        $logoGuardado = $barberia?->logo;
        $logoExiste = $logoGuardado && file_exists(public_path('storage/' . $logoGuardado));
        $logoBarberia = $logoExiste
            ? asset('storage/' . $logoGuardado)
            : asset('images/branding/icon_512_Barbaercore.png');
        $logoFondoOscuro = asset('images/branding/barbercore-logo-dark-transparent.png');
        $imagenesServicios = [
            1 => 'images/services/service-classic-cut.jpg',
            2 => 'images/services/service-cut-beard.jpg',
            3 => 'images/services/service-beard.jpg',
            5 => 'images/services/service-premium-fade.jpg',
        ];
        $imagenesDemo = [
            'images/services/service-classic-cut.jpg',
            'images/services/service-beard.jpg',
            'images/services/service-cut-beard.jpg',
            'images/services/service-premium-fade.jpg',
        ];
    @endphp

    <div class="topbar">
        <div class="container topbar-inner">
            <div class="topbar-group">
                <span class="topbar-item"><svg class="icon" viewBox="0 0 24 24"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>{{ $barberia?->direccion ?? 'Ubicación disponible por WhatsApp' }}</span>
                <span class="topbar-item"><svg class="icon" viewBox="0 0 24 24"><path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 3.1 5.2 2 2 0 0 1 5.1 3h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.7 2.6a2 2 0 0 1-.5 2.1L9 10.7a16 16 0 0 0 4.3 4.3l1.3-1.3a2 2 0 0 1 2.1-.5c.8.4 1.7.6 2.6.7a2 2 0 0 1 1.7 2Z"/></svg>{{ $barberia?->telefono ?? 'Atención directa' }}</span>
            </div>
            <span class="topbar-tag">Precisión · Estilo · Confianza</span>
        </div>
    </div>

    <header class="header">
        <div class="container header-inner">
            <nav class="nav-list nav-left" aria-label="Navegación principal">
                <a class="nav-link" href="#inicio">Inicio</a>
                <a class="nav-link" href="#servicios">Servicios</a>
                <a class="nav-link" href="#estandar">Nuestro estándar</a>
            </nav>

            <a class="brand" href="#inicio" aria-label="Inicio de {{ $nombreBarberia }}">
                <img class="brand-logo" src="{{ $logoBarberia }}" alt="Logo de {{ $nombreBarberia }}">
                <span class="brand-name">{{ $nombreBarberia }}<small>Barber Studio</small></span>
            </a>

            <nav class="nav-list nav-right" aria-label="Acciones">
                <a class="nav-link" href="#contacto">Contacto</a>
                <a class="btn btn-dark" href="{{ route('login') }}"><svg class="icon" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg><span>Acceso</span></a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <span class="eyebrow">Barbería profesional</span>
                    <h1>Tu imagen,<br><em>en buenas manos.</em></h1>
                    <p class="hero-copy">Una experiencia de cuidado masculino basada en técnica, atención y detalle. Porque un buen servicio se reconoce desde el primer momento.</p>
                    <div class="hero-actions">
                        <a class="btn btn-gold" href="https://wa.me/{{ $telefonoWhatsapp }}?text=Hola,%20quiero%20agendar%20una%20cita" target="_blank" rel="noopener noreferrer"><svg class="icon" viewBox="0 0 24 24"><path d="M8 2v3M16 2v3M3.5 9h17M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/><path d="m9 16 2 2 4-5"/></svg>Reservar una cita</a>
                        <a class="btn btn-light" href="#servicios">Conocer servicios</a>
                    </div>
                    <div class="hero-signature">El estilo correcto habla antes que tú.</div>
                </div>
            </div>
        </section>

        <div class="trust-wrap">
            <div class="container trust-bar">
                <div class="trust-item"><span class="trust-icon"><svg class="icon" viewBox="0 0 24 24"><path d="m20 6-11 11-5-5"/></svg></span><div><strong>Atención personalizada</strong><span>Escuchamos lo que buscas antes de empezar.</span></div></div>
                <div class="trust-item"><span class="trust-icon"><svg class="icon" viewBox="0 0 24 24"><path d="M3 12h18M5 7h14M7 17h10"/><path d="M8 3h8M9 21h6"/></svg></span><div><strong>Precisión profesional</strong><span>Técnica y cuidado en cada acabado.</span></div></div>
                <div class="trust-item"><span class="trust-icon"><svg class="icon" viewBox="0 0 24 24"><path d="M8 2v3M16 2v3M3.5 9h17M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/></svg></span><div><strong>Reserva directa</strong><span>Agenda fácilmente desde WhatsApp.</span></div></div>
            </div>
        </div>

        <section class="section" id="servicios">
            <div class="container">
                <div class="section-head">
                    <div><span class="kicker">Servicios seleccionados</span><h2 class="title">Un servicio a la altura de tu estilo.</h2></div>
                    <p class="copy">Opciones claras, atención sin prisas y resultados pensados para tu imagen y rutina.</p>
                </div>

                <div class="services">
                    @forelse($servicios as $servicio)
                        <article class="service">
                            <div class="service-media">
                                <img src="{{ asset($imagenesServicios[$servicio->id_servicio] ?? $imagenesDemo[($loop->iteration - 1) % count($imagenesDemo)]) }}" alt="{{ $servicio->nombre }} en {{ $nombreBarberia }}" loading="lazy">
                            </div>
                            <div class="service-body">
                                <div class="service-top">
                                    <span class="service-icon">
                                        @if($loop->iteration % 3 === 1)
                                            <svg class="icon" viewBox="0 0 24 24"><circle cx="6" cy="7" r="3"/><circle cx="6" cy="17" r="3"/><path d="m8.6 8.5 11.4 7M8.6 15.5 20 8M14.5 12 20 20"/></svg>
                                        @elseif($loop->iteration % 3 === 2)
                                            <svg class="icon" viewBox="0 0 24 24"><path d="M4 17h13l3-7H7l-3 7Z"/><path d="m7 10 2-3h10l1 3M7 17l-2 4M16 17l2 4"/></svg>
                                        @else
                                            <svg class="icon" viewBox="0 0 24 24"><path d="M6 3h12l2 4-8 14L4 7l2-4Z"/><path d="M4 7h16M9 3l3 18 3-18"/></svg>
                                        @endif
                                    </span>
                                    <span class="service-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <h3>{{ $servicio->nombre }}</h3>
                                <p>{{ $servicio->descripcion ?: 'Servicio profesional realizado con atención y cuidado en cada detalle.' }}</p>
                                <div class="service-footer">
                                    <span class="price">${{ number_format($servicio->precio, 2) }}</span>
                                    @if($servicio->duracion_minutos)<span class="duration"><svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>{{ $servicio->duracion_minutos }} min</span>@endif
                                </div>
                            </div>
                        </article>
                    @empty
                        @foreach(['Corte clásico', 'Barba y perfilado', 'Corte + barba', 'Cuidado premium'] as $servicioDemo)
                            <article class="service"><div class="service-media"><img src="{{ asset($imagenesDemo[$loop->index]) }}" alt="{{ $servicioDemo }} en {{ $nombreBarberia }}" loading="lazy"></div><div class="service-body"><div class="service-top"><span class="service-icon"><svg class="icon" viewBox="0 0 24 24"><circle cx="6" cy="7" r="3"/><circle cx="6" cy="17" r="3"/><path d="m8.6 8.5 11.4 7M8.6 15.5 20 8"/></svg></span><span class="service-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span></div><h3>{{ $servicioDemo }}</h3><p>Una experiencia profesional y cuidada, hecha a tu medida.</p><div class="service-footer"><span class="price">Consultar</span></div></div></article>
                        @endforeach
                    @endforelse
                </div>
            </div>
        </section>

        <section class="standard" id="estandar">
            <div class="container standard-grid">
                <div class="standard-dark">
                    <img class="standard-logo" src="{{ $logoFondoOscuro }}" alt="Emblema de {{ $nombreBarberia }}">
                    <div class="standard-quote"><span>Nuestro compromiso</span><h2>Confianza que se construye en cada visita.</h2></div>
                </div>
                <div class="standards-list">
                    <article class="standard-item"><span class="standard-number">01</span><div><h3>Primero te escuchamos</h3><p>Entendemos el resultado que buscas y te orientamos para elegir lo que mejor te favorece.</p></div></article>
                    <article class="standard-item"><span class="standard-number">02</span><div><h3>Cuidamos el proceso</h3><p>Trabajamos con orden, higiene y atención para que disfrutes el servicio de principio a fin.</p></div></article>
                    <article class="standard-item"><span class="standard-number">03</span><div><h3>Revisamos el resultado</h3><p>El servicio termina cuando cada línea, transición y detalle está en su lugar.</p></div></article>
                </div>
            </div>
        </section>

        <section class="contact-section" id="contacto">
            <div class="container contact-card">
                <div class="contact-copy"><span class="kicker">Visítanos</span><h2 class="title">Tu próxima cita comienza aquí.</h2><p class="copy">Ponte en contacto directamente y reserva el horario que mejor se adapte a ti.</p><a class="btn btn-dark" href="https://wa.me/{{ $telefonoWhatsapp }}?text=Hola,%20quiero%20agendar%20una%20cita" target="_blank" rel="noopener noreferrer">Hablar por WhatsApp</a></div>
                <div class="contact-details">
                    <div class="detail"><span class="detail-icon"><svg class="icon" viewBox="0 0 24 24"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></span><div><small>Dirección</small><strong>{{ $barberia?->direccion ?? 'Solicita la ubicación por WhatsApp' }}</strong></div></div>
                    <div class="detail"><span class="detail-icon"><svg class="icon" viewBox="0 0 24 24"><path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 3.1 5.2 2 2 0 0 1 5.1 3h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.7 2.6a2 2 0 0 1-.5 2.1L9 10.7a16 16 0 0 0 4.3 4.3l1.3-1.3a2 2 0 0 1 2.1-.5c.8.4 1.7.6 2.6.7a2 2 0 0 1 1.7 2Z"/></svg></span><div><small>Teléfono</small><strong>{{ $barberia?->telefono ?? 'Disponible por WhatsApp' }}</strong></div></div>
                    <div class="detail"><span class="detail-icon"><svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span><div><small>Atención</small><strong>Agenda con anticipación para asegurar tu horario.</strong></div></div>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="container cta-inner"><div><h2>Haz espacio para verte mejor.</h2><p>Reserva tu próxima visita de forma rápida y directa.</p></div><a class="btn btn-dark" href="https://wa.me/{{ $telefonoWhatsapp }}?text=Hola,%20quiero%20reservar%20una%20cita" target="_blank" rel="noopener noreferrer"><svg class="icon" viewBox="0 0 24 24"><path d="M8 2v3M16 2v3M3.5 9h17M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/></svg>Reservar ahora</a></div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand"><img src="{{ $logoFondoOscuro }}" alt=""><strong>{{ $nombreBarberia }}</strong></div>
                    <p class="footer-about">Cuidado masculino con técnica, atención y un estándar profesional en cada visita.</p>
                    <div class="socials" aria-label="Redes sociales">
                        <a class="social-link" href="https://facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook" title="Facebook">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-8h2.7l.4-3h-3.1V9.1c0-.9.3-1.6 1.6-1.6h1.6V4.8c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.4V11H7v3h3v8h3.5z"/></svg>
                        </a>
                        <a class="social-link" href="https://instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9zm9.75 1.5a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/></svg>
                        </a>
                        <a class="social-link" href="https://tiktok.com/" target="_blank" rel="noopener noreferrer" aria-label="TikTok" title="TikTok">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14.5 3c.5 2 1.7 3.3 3.8 3.6v2.7c-1.4 0-2.6-.4-3.6-1.1v6.1a5.3 5.3 0 1 1-5.3-5.3c.3 0 .6 0 .9.1v2.8a2.7 2.7 0 1 0 1.7 2.5V3h2.5z"/></svg>
                        </a>
                    </div>
                </div>
                <div><h3 class="footer-title">Navegación</h3><div class="footer-links"><a href="#inicio">Inicio</a><a href="#servicios">Servicios</a><a href="#estandar">Nuestro estándar</a><a href="{{ route('login') }}">Acceso al sistema</a></div></div>
                <div><h3 class="footer-title">Contacto</h3><div class="footer-links"><span>{{ $barberia?->telefono ?? 'Teléfono por confirmar' }}</span><span>{{ $barberia?->direccion ?? 'Dirección por confirmar' }}</span><a href="https://wa.me/{{ $telefonoWhatsapp }}" target="_blank" rel="noopener noreferrer">Abrir WhatsApp</a></div></div>
            </div>
            <div class="footer-bottom"><span>© {{ date('Y') }} {{ $nombreBarberia }}. Todos los derechos reservados.</span><span>Precisión · Estilo · Confianza</span></div>
        </div>
    </footer>

    <a class="whatsapp" href="https://wa.me/{{ $telefonoWhatsapp }}?text=Hola,%20quiero%20informaci%C3%B3n%20sobre%20sus%20servicios" target="_blank" rel="noopener noreferrer" aria-label="Contactar por WhatsApp"><svg viewBox="0 0 24 24"><path d="M20.5 3.5A11.7 11.7 0 0 0 12.1 0C5.6 0 .3 5.3.3 11.8c0 2.1.5 4.1 1.6 5.9L.2 24l6.5-1.7a11.8 11.8 0 0 0 5.4 1.4c6.5 0 11.8-5.3 11.8-11.8 0-3.2-1.2-6.1-3.4-8.4Zm-8.4 18.2c-1.7 0-3.5-.5-5-1.4l-.4-.2-3.8 1 1-3.7-.2-.4a9.8 9.8 0 1 1 8.4 4.7Zm5.4-7.3c-.3-.1-1.7-.8-2-1-.3-.1-.5-.1-.7.2-.2.3-.8 1-.9 1.2-.2.2-.3.2-.6.1-1.7-.8-2.8-1.5-3.9-3.4-.3-.5.3-.5.8-1.6.1-.2 0-.4 0-.6l-.9-2.1c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.2-1.2 2.9s1.2 3.3 1.4 3.5c.1.2 2.4 3.7 5.9 5.2 2.2.9 3.1 1 4.2.8.7-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3-.1-.1-.3-.2-.6-.3Z"/></svg></a>
</body>
</html>
