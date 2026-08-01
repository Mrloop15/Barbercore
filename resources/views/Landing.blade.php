<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cortes, barba y cuidado personal en {{ $barberia?->nombre ?? 'BarberCore Studio' }}.">
    <title>{{ $barberia?->nombre ?? 'BarberCore Studio' }} | Barbería profesional</title>
    <link rel="icon" type="image/png" href="{{ asset('images/branding/icon_192_Barbercore.png') }}">
    <script>document.documentElement.classList.add('js');</script>

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
        .topbar-group-status { display: flex; align-items: center; gap: 20px; }
        .topbar-status { display: inline-flex; align-items: center; gap: 7px; font-size: 11px; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; }
        .topbar-status.is-open { color: var(--verde); }
        .topbar-status.is-closed { color: #E2795A; }
        .topbar-status-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }

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
        .hero-content > * { opacity: 0; animation: hero-reveal .7s cubic-bezier(.22,.8,.3,1) forwards; }
        .hero-content > :nth-child(2) { animation-delay: .08s; }
        .hero-content > :nth-child(3) { animation-delay: .16s; }
        .hero-content > :nth-child(4) { animation-delay: .24s; }
        .hero-content > :nth-child(5) { animation-delay: .32s; }
        @keyframes hero-reveal { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }

        .js [data-reveal] { opacity: 0; transform: translateY(18px); transition: opacity .58s ease, transform .58s cubic-bezier(.22,.8,.3,1); transition-delay: var(--reveal-delay, 0ms); }
        .js [data-reveal].is-visible { opacity: 1; transform: none; }

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
        .carousel-controls { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin: -22px 0 20px; }
        .carousel-navigation { min-width: 0; display: flex; align-items: center; gap: 15px; }
        .carousel-status { min-width: 42px; color: var(--gris); font-size: 10px; font-weight: 900; letter-spacing: .13em; white-space: nowrap; }
        .carousel-status strong { color: var(--texto); font-size: 13px; }
        .carousel-progress { width: clamp(74px, 10vw, 132px); height: 2px; overflow: hidden; border-radius: 99px; background: var(--borde); }
        .carousel-progress-bar { width: 100%; height: 100%; display: block; border-radius: inherit; background: linear-gradient(90deg, #b78a12, var(--dorado)); transform: scaleX(0); transform-origin: left; transition: transform .45s cubic-bezier(.22, 1, .36, 1); }
        .carousel-dots { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
        .carousel-dot { width: 7px; height: 7px; border-radius: 5px; border: 0; padding: 0; background: var(--borde); cursor: pointer; transition: background .3s ease, width .4s cubic-bezier(.22, 1, .36, 1), transform .25s ease; }
        .carousel-dot:hover { background: rgba(201, 162, 39, .62); transform: scale(1.12); }
        .carousel-dot.is-active { width: 24px; background: var(--dorado); }
        .carousel-buttons { display: flex; gap: 5px; padding: 5px; border: 1px solid var(--borde); border-radius: 999px; background: var(--blanco); box-shadow: 0 10px 28px rgba(17, 17, 17, .07); }
        .carousel-button { width: 39px; height: 39px; border-radius: 50%; display: grid; place-items: center; border: 0; background: transparent; color: var(--texto); cursor: pointer; transition: background .3s ease, color .3s ease, box-shadow .3s ease, transform .25s ease, opacity .25s ease; }
        .carousel-button:hover:not(:disabled) { background: var(--oscuro); color: var(--blanco); box-shadow: 0 8px 18px rgba(17, 17, 17, .18); transform: scale(1.04); }
        .carousel-button:active:not(:disabled) { transform: translateY(0); }
        .carousel-button:disabled { opacity: .3; cursor: default; box-shadow: none; transform: none; }
        .services-carousel { position: relative; }
        .services-carousel.is-active .services { grid-template-columns: none; grid-auto-flow: column; grid-auto-columns: calc((100% - 54px) / 4); overflow-x: auto; padding: 8px 2px 29px; scroll-snap-type: x mandatory; scroll-padding-inline: 2px; overscroll-behavior-inline: contain; scrollbar-width: none; cursor: grab; }
        .services-carousel.is-active .services::-webkit-scrollbar { display: none; }
        .services-carousel.is-active .services.is-dragging, .services-carousel.is-active .services.is-animating { scroll-snap-type: none; }
        .services-carousel.is-active .services.is-dragging { cursor: grabbing; user-select: none; }
        .services-carousel.is-active .service { scroll-snap-align: start; scroll-snap-stop: always; }
        .service { min-height: 470px; display: flex; flex-direction: column; overflow: hidden; border: 1px solid var(--borde); background: var(--blanco); box-shadow: 0 10px 28px rgba(17,17,17,.045); transition: background .25s ease, transform .25s ease, box-shadow .25s ease; }
        .service:hover { position: relative; z-index: 2; background: var(--oscuro); color: var(--blanco); transform: translateY(-7px); box-shadow: 0 24px 46px rgba(17,17,17,.15); }
        .service-media { position: relative; height: 205px; overflow: hidden; background: var(--oscuro); }
        .service-media::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 55%, rgba(17,17,17,.38)); pointer-events: none; }
        .service-media img { width: 100%; height: 100%; object-fit: cover; transition: transform .45s ease; }
        .service:hover .service-media img { transform: scale(1.045); }
        .service-media-empty { width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; color: rgba(229, 224, 214, .68); background: linear-gradient(145deg, #191919, #0e0e0e); }
        .service-media-empty .icon { width: 38px; height: 38px; color: var(--dorado); opacity: .8; }
        .service-media-empty span { font-size: 10px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
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
        .services-empty { grid-column: 1 / -1; padding: 38px; border: 1px solid var(--borde); background: var(--blanco); text-align: center; }
        .services-empty strong { display: block; margin-bottom: 8px; font-family: Georgia, serif; font-size: 22px; }

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
        .contact-actions { display: flex; flex-wrap: wrap; gap: 10px; }
        .btn-map { border-color: var(--borde); background: var(--blanco); color: var(--texto); }
        .hours-summary { display: grid; gap: 5px; }
        .hours-line { display: flex; justify-content: space-between; gap: 18px; color: var(--gris); font-size: 11px; line-height: 1.45; }
        .hours-line b { color: var(--texto); font-weight: 800; }

        .faq-section { padding: 72px 0; background: var(--blanco); }
        .faq-wrap { display: grid; grid-template-columns: .72fr 1.28fr; gap: 56px; align-items: start; }
        .faq-intro { position: sticky; top: 125px; }
        .faq-intro .copy { margin-top: 20px; }
        .faq-list { border-top: 1px solid var(--borde); }
        .faq-item { border-bottom: 1px solid var(--borde); }
        .faq-item summary { position: relative; display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 18px 4px; cursor: pointer; list-style: none; font-family: Georgia, serif; font-size: 17px; font-weight: 700; }
        .faq-item summary::-webkit-details-marker { display: none; }
        .faq-item summary::after { content: "+"; width: 30px; height: 30px; display: grid; place-items: center; flex: 0 0 auto; border: 1px solid var(--borde); border-radius: 50%; color: var(--dorado); font-family: Arial, sans-serif; transition: transform .3s ease, background .3s ease, color .3s ease, border-color .3s ease; }
        .faq-item.is-expanded summary::after { background: var(--oscuro); border-color: var(--oscuro); color: var(--blanco); transform: rotate(45deg); }
        .faq-answer { display: grid; grid-template-rows: 0fr; opacity: 0; color: var(--gris); font-size: 13px; line-height: 1.75; transition: grid-template-rows .32s cubic-bezier(.22,.8,.3,1), opacity .25s ease; }
        .faq-answer-inner { min-height: 0; overflow: hidden; padding: 0 52px 0 4px; transition: padding-bottom .32s ease; }
        .faq-item.is-expanded .faq-answer { grid-template-rows: 1fr; opacity: 1; }
        .faq-item.is-expanded .faq-answer-inner { padding-bottom: 18px; }

        .cta { background: var(--dorado); color: var(--blanco); }
        .cta-inner { min-height: 200px; display: grid; grid-template-columns: 1fr auto; gap: 40px; align-items: center; }
        .cta h2 { margin: 0 0 8px; font-family: Georgia, serif; font-size: clamp(34px, 4vw, 49px); letter-spacing: -.04em; }
        .cta p { margin: 0; font-size: 14px; opacity: .9; }

        body.booking-open { overflow: hidden; }
        .booking-modal { position: fixed; inset: 0; z-index: 100; display: grid; place-items: center; padding: 22px; background: rgba(17,17,17,.72); opacity: 0; visibility: hidden; transition: opacity .2s ease, visibility .2s ease; }
        .booking-modal.is-open { opacity: 1; visibility: visible; }
        .booking-dialog { width: min(640px, 100%); max-height: calc(100vh - 44px); overflow-y: auto; border: 1px solid var(--borde); background: var(--blanco); box-shadow: 0 28px 80px rgba(0,0,0,.3); transform: translateY(12px); transition: transform .2s ease; }
        .booking-modal.is-open .booking-dialog { transform: none; }
        .booking-head { display: grid; grid-template-columns: 1fr 42px; gap: 20px; align-items: start; padding: 26px 28px; background: var(--oscuro); color: var(--blanco); }
        .booking-head .kicker { margin-bottom: 8px; }
        .booking-head h2 { margin: 0; font-family: Georgia, serif; font-size: 29px; }
        .booking-close { width: 40px; height: 40px; display: grid; place-items: center; border: 1px solid rgba(255,255,255,.18); border-radius: 50%; background: transparent; color: var(--blanco); cursor: pointer; }
        .booking-body { padding: 28px; }
        .booking-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 17px; }
        .booking-field.full { grid-column: 1 / -1; }
        .booking-field label { display: block; margin-bottom: 7px; color: var(--texto); font-size: 11px; font-weight: 800; letter-spacing: .04em; }
        .booking-field input, .booking-field select, .booking-field textarea { width: 100%; border: 1px solid var(--borde); border-radius: 0; padding: 12px 13px; background: var(--blanco); color: var(--texto); font: inherit; font-size: 13px; outline: none; }
        .booking-field textarea { min-height: 85px; resize: vertical; }
        .booking-field input:focus, .booking-field select:focus, .booking-field textarea:focus { border-color: var(--dorado); box-shadow: 0 0 0 3px rgba(201,162,39,.12); }
        .booking-native-picker { position: absolute !important; width: 1px !important; height: 1px !important; padding: 0 !important; border: 0 !important; opacity: 0; pointer-events: none; }
        .booking-picker { position: relative; width: 100%; }
        .booking-picker-trigger { width: 100%; min-height: 44px; display: flex; align-items: center; justify-content: space-between; gap: 12px; border: 1px solid var(--borde); padding: 11px 13px; background: var(--blanco); color: var(--texto); cursor: pointer; font: inherit; font-size: 13px; font-weight: 700; text-align: left; }
        .booking-picker-trigger:hover, .booking-picker-trigger[aria-expanded="true"] { border-color: var(--dorado); box-shadow: 0 0 0 3px rgba(201,162,39,.12); }
        .booking-picker-trigger:disabled { opacity: .55; cursor: not-allowed; border-color: var(--borde); box-shadow: none; }
        .booking-picker-trigger .icon { width: 18px; height: 18px; color: var(--dorado); }
        .booking-calendar, .booking-time-panel { position: fixed; z-index: 120; display: none; width: 310px; border: 1px solid var(--borde); border-radius: 18px; padding: 16px; background: var(--blanco); box-shadow: 0 22px 55px rgba(17,17,17,.2); }
        .booking-calendar.open, .booking-time-panel.open { display: block; animation: picker-in .16s ease-out; }
        @keyframes picker-in { from { opacity: 0; transform: translateY(-5px) scale(.98); } to { opacity: 1; transform: none; } }
        .booking-calendar-head, .booking-time-head { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 14px; }
        .booking-calendar-head strong, .booking-time-head strong { font-family: Arial, sans-serif; font-size: 14px; text-transform: capitalize; }
        .booking-calendar-nav { width: 34px; height: 34px; display: grid; place-items: center; border: 1px solid var(--borde); border-radius: 9px; background: var(--blanco); color: var(--texto); cursor: pointer; }
        .booking-calendar-nav:hover { border-color: var(--dorado); color: var(--dorado); }
        .booking-calendar-week, .booking-calendar-days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
        .booking-calendar-week span { padding-bottom: 7px; color: var(--gris); font-size: 9px; font-weight: 800; text-align: center; text-transform: uppercase; }
        .booking-calendar-day { aspect-ratio: 1; border: 0; border-radius: 9px; background: transparent; color: var(--texto); cursor: pointer; font-size: 11px; font-weight: 700; }
        .booking-calendar-day:hover { background: rgba(201,162,39,.12); color: var(--dorado); }
        .booking-calendar-day.outside { color: #bbb5aa; font-weight: 500; }
        .booking-calendar-day.today { box-shadow: inset 0 0 0 1px var(--dorado); color: var(--dorado); }
        .booking-calendar-day.selected { background: var(--dorado); color: var(--blanco); box-shadow: 0 5px 12px rgba(201,162,39,.25); }
        .booking-calendar-day:disabled { opacity: .28; cursor: not-allowed; background: transparent; color: var(--gris); }
        .booking-calendar-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--borde); }
        .booking-calendar-footer button { border: 0; background: transparent; color: var(--dorado); padding: 4px; cursor: pointer; font-size: 11px; font-weight: 800; }
        .booking-calendar-footer span, .booking-time-head span { color: var(--gris); font-size: 10px; }
        .booking-time-panel { width: 330px; max-height: min(390px, calc(100vh - 24px)); overflow-y: auto; scrollbar-width: none; }
        .booking-time-panel::-webkit-scrollbar { display: none; }
        .booking-time-period + .booking-time-period { margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--borde); }
        .booking-time-label { display: flex; align-items: center; gap: 7px; margin-bottom: 9px; color: var(--gris); font-size: 9px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
        .booking-time-label::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: var(--dorado); }
        .booking-time-slots { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; }
        .booking-time-slot { border: 1px solid var(--borde); border-radius: 9px; padding: 8px 5px; background: var(--blanco); color: var(--texto); cursor: pointer; font-size: 10px; font-weight: 700; }
        .booking-time-slot:hover { border-color: var(--dorado); background: rgba(201,162,39,.08); color: var(--dorado); }
        .booking-time-slot.selected { border-color: var(--dorado); background: var(--dorado); color: var(--blanco); box-shadow: 0 5px 12px rgba(201,162,39,.22); }
        .booking-time-empty { grid-column: 1 / -1; padding: 16px 8px; color: var(--gris); font-size: 11px; line-height: 1.5; text-align: center; }
        .booking-note { margin: 18px 0; padding: 13px 14px; border-left: 3px solid var(--dorado); background: var(--fondo); color: var(--gris); font-size: 11px; line-height: 1.6; }
        .booking-submit { width: 100%; border: 0; background: var(--verde); color: var(--blanco); cursor: pointer; }

        .rewards-submit { width: 100%; margin-top: 6px; }
        .rewards-result { margin-top: 22px; }
        .rewards-client { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 16px 18px; border: 1px solid var(--borde); background: var(--fondo); margin-bottom: 16px; }
        .rewards-client strong { font-family: Georgia, serif; font-size: 16px; }
        .rewards-points { color: var(--dorado); font-size: 21px; font-weight: 900; white-space: nowrap; }
        .reward-item { display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 13px 16px; border: 1px solid var(--borde); margin-bottom: 8px; }
        .reward-item.is-available { border-color: var(--dorado); background: rgba(201, 162, 39, .06); }
        .reward-item-name { font-weight: 800; font-size: 13px; }
        .reward-item-desc { margin-top: 3px; color: var(--gris); font-size: 11px; line-height: 1.5; }
        .reward-item-points { color: var(--gris); font-size: 11px; font-weight: 800; text-align: right; white-space: nowrap; }
        .reward-item.is-available .reward-item-points { color: var(--dorado); }
        .rewards-empty, .rewards-error { padding: 18px; border: 1px dashed var(--borde); color: var(--gris); font-size: 13px; text-align: center; }
        .rewards-error { border-color: #c0392b; color: #c0392b; }

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
        .footer-links button { border: 0; background: none; padding: 0; color: inherit; font: inherit; text-align: left; cursor: pointer; }
        .footer-links button:hover { color: var(--dorado); }
        .footer-bottom { display: flex; justify-content: space-between; gap: 20px; padding-top: 22px; border-top: 1px solid rgba(229,224,214,.16); color: var(--gris); font-size: 10px; }

        .whatsapp-widget { position: fixed; right: 22px; bottom: 22px; z-index: 70; font-family: Arial, Helvetica, sans-serif; }
        .whatsapp-toggle { position: relative; width: 62px; height: 62px; display: grid; place-items: center; border: 3px solid var(--blanco); border-radius: 50%; background: var(--verde); color: var(--blanco); box-shadow: 0 14px 32px rgba(17,17,17,.25); cursor: pointer; transition: transform .2s ease, box-shadow .2s ease; }
        .whatsapp-toggle:hover { transform: translateY(-4px); box-shadow: 0 18px 38px rgba(17,17,17,.3); }
        .whatsapp-toggle svg { width: 28px; height: 28px; fill: currentColor; }
        .whatsapp-badge { position: absolute; top: -2px; right: -2px; width: 18px; height: 18px; display: grid; place-items: center; border: 2px solid var(--blanco); border-radius: 50%; background: var(--dorado); color: var(--oscuro); font-size: 9px; font-weight: 900; }
        .whatsapp-prompt { position: absolute; right: 74px; bottom: 8px; width: max-content; max-width: 210px; border: 1px solid var(--borde); border-radius: 10px; padding: 11px 14px; background: var(--blanco); color: var(--texto); box-shadow: 0 10px 28px rgba(17,17,17,.12); font-size: 12px; font-weight: 700; opacity: 0; visibility: hidden; transform: translateX(8px); transition: opacity .25s ease, transform .25s ease, visibility .25s ease; }
        .whatsapp-prompt::after { content: ""; position: absolute; right: -6px; bottom: 15px; width: 10px; height: 10px; border-top: 1px solid var(--borde); border-right: 1px solid var(--borde); background: var(--blanco); transform: rotate(45deg); }
        .whatsapp-widget.show-prompt:not(.is-open) .whatsapp-prompt { opacity: 1; visibility: visible; transform: translateX(0); }
        .whatsapp-widget.is-open .whatsapp-badge { display: none; }

        .whatsapp-panel { position: absolute; right: 0; bottom: 78px; width: 350px; overflow: hidden; border: 1px solid var(--borde); border-radius: 18px; background: var(--blanco); box-shadow: 0 24px 65px rgba(17,17,17,.23); opacity: 0; visibility: hidden; transform: translateY(16px) scale(.97); transform-origin: bottom right; transition: opacity .25s ease, transform .25s ease, visibility .25s ease; }
        .whatsapp-widget.is-open .whatsapp-panel { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }
        .chat-header { display: grid; grid-template-columns: 48px 1fr 32px; gap: 12px; align-items: center; padding: 17px 16px; background: var(--oscuro); color: var(--blanco); }
        .chat-avatar { position: relative; width: 48px; height: 48px; display: grid; place-items: center; border-radius: 50%; background: var(--blanco); }
        .chat-avatar img { width: 42px; height: 42px; object-fit: contain; }
        .chat-avatar::after { content: ""; position: absolute; right: 0; bottom: 1px; width: 11px; height: 11px; border: 2px solid var(--oscuro); border-radius: 50%; background: var(--verde); }
        .chat-identity strong { display: block; margin-bottom: 3px; font-family: Georgia, serif; font-size: 15px; }
        .chat-identity span { display: flex; align-items: center; gap: 6px; color: var(--borde); font-size: 10px; }
        .chat-close { width: 32px; height: 32px; display: grid; place-items: center; border: 0; border-radius: 50%; background: rgba(255,255,255,.08); color: var(--blanco); cursor: pointer; }
        .chat-close:hover { background: rgba(255,255,255,.16); }
        .chat-close .icon { width: 16px; height: 16px; }
        .chat-body { padding: 22px 17px 17px; background-color: var(--fondo); background-image: radial-gradient(rgba(201,162,39,.12) 1px, transparent 1px); background-size: 17px 17px; }
        .chat-bubble { position: relative; max-width: 88%; border: 1px solid var(--borde); border-radius: 4px 14px 14px 14px; padding: 14px 15px 11px; background: var(--blanco); box-shadow: 0 6px 17px rgba(17,17,17,.06); color: var(--texto); font-size: 13px; line-height: 1.55; }
        .chat-bubble strong { display: block; margin-bottom: 4px; }
        .chat-time { display: block; margin-top: 8px; color: var(--gris); font-size: 9px; text-align: right; }
        .chat-form { margin-top: 16px; }
        .chat-label { display: block; margin-bottom: 7px; color: var(--gris); font-size: 10px; font-weight: 800; }
        .chat-message { width: 100%; min-height: 74px; display: block; resize: vertical; border: 1px solid var(--borde); border-radius: 9px; padding: 11px 12px; background: var(--blanco); color: var(--texto); font: inherit; font-size: 12px; line-height: 1.5; outline: none; transition: border-color .2s ease, box-shadow .2s ease; }
        .chat-message::placeholder { color: var(--gris); }
        .chat-message:focus { border-color: var(--dorado); box-shadow: 0 0 0 3px rgba(201,162,39,.13); }
        .chat-action { width: 100%; display: flex; align-items: center; justify-content: center; gap: 9px; margin-top: 10px; border: 0; border-radius: 9px; padding: 13px 16px; background: var(--verde); color: var(--blanco); font: inherit; font-size: 12px; font-weight: 900; cursor: pointer; transition: filter .2s ease, transform .2s ease; }
        .chat-action:hover { filter: brightness(.95); transform: translateY(-1px); }
        .chat-action svg { width: 18px; height: 18px; fill: currentColor; }
        .chat-note { display: block; margin-top: 10px; color: var(--gris); font-size: 9px; text-align: center; }

        @media (max-width: 1050px) {
            .header-inner { grid-template-columns: auto 1fr auto; }
            .brand { justify-self: start; }
            .nav-left { display: none; }
            .nav-right .nav-link { display: none; }
            .services { grid-template-columns: repeat(2, 1fr); }
            .services-carousel.is-active .services { grid-auto-columns: calc((100% - 18px) / 2); }
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
            .faq-wrap { grid-template-columns: 1fr; gap: 38px; }
            .faq-intro { position: static; }
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
            .services-carousel.is-active .services { grid-auto-columns: 88%; }
            .carousel-controls { margin-top: -28px; }
            .carousel-progress { display: none; }
            .carousel-navigation { gap: 10px; }
            .service { min-height: 455px; }
            .standard-dark { min-height: 390px; padding: 32px; }
            .standard-logo { width: 86px; height: 86px; }
            .standards-list { padding: 25px; }
            .detail { padding: 22px; }
            .footer-bottom { display: block; line-height: 1.7; }
            .whatsapp-widget { right: 15px; bottom: 15px; }
            .whatsapp-panel { right: 0; width: min(350px, calc(100vw - 30px)); }
            .whatsapp-prompt { display: none; }
            .booking-modal { padding: 10px; }
            .booking-dialog { max-height: calc(100vh - 20px); }
            .booking-head, .booking-body { padding: 21px; }
            .booking-grid { grid-template-columns: 1fr; }
            .booking-field.full { grid-column: auto; }
            .booking-calendar, .booking-time-panel { width: min(330px, calc(100vw - 24px)); }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { transition-duration: .01ms !important; }
            .services-carousel.is-active .services { scroll-behavior: auto; }
            .hero-content > * { opacity: 1; animation: none; }
            .js [data-reveal] { opacity: 1; transform: none; }
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
        $usarCarrusel = $servicios->count() > 4;
        $horariosAbiertos = $horarios->where('abierto', true);
        $horaMinima = substr($horariosAbiertos->min('hora_apertura') ?: '08:00', 0, 5);
        $horaMaxima = substr($horariosAbiertos->max('hora_cierre') ?: '20:00', 0, 5);
        $horariosReserva = $horarios->mapWithKeys(fn ($horario) => [
            (string) $horario->dia_semana => [
                'abierto' => (bool) $horario->abierto,
                'apertura' => $horario->hora_apertura ? substr($horario->hora_apertura, 0, 5) : null,
                'cierre' => $horario->hora_cierre ? substr($horario->hora_cierre, 0, 5) : null,
            ],
        ])->all();

        $ahoraNegocio = now('America/Mexico_City');
        $horarioHoy = $horarios->firstWhere('dia_semana', ($ahoraNegocio->dayOfWeekIso - 1));
        $negocioAbierto = $horarioHoy
            && $horarioHoy->abierto
            && $horarioHoy->hora_apertura
            && $horarioHoy->hora_cierre
            && $ahoraNegocio->format('H:i:s') >= $horarioHoy->hora_apertura
            && $ahoraNegocio->format('H:i:s') <= $horarioHoy->hora_cierre;
    @endphp

    <div class="topbar">
        <div class="container topbar-inner">
            <div class="topbar-group">
                <span class="topbar-item"><svg class="icon" viewBox="0 0 24 24"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>{{ $barberia?->direccion ?? 'Ubicación disponible por WhatsApp' }}</span>
                <span class="topbar-item"><svg class="icon" viewBox="0 0 24 24"><path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 3.1 5.2 2 2 0 0 1 5.1 3h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.7 2.6a2 2 0 0 1-.5 2.1L9 10.7a16 16 0 0 0 4.3 4.3l1.3-1.3a2 2 0 0 1 2.1-.5c.8.4 1.7.6 2.6.7a2 2 0 0 1 1.7 2Z"/></svg>{{ $barberia?->telefono ?? 'Atención directa' }}</span>
            </div>
            <div class="topbar-group topbar-group-status">
                <span class="topbar-status {{ $negocioAbierto ? 'is-open' : 'is-closed' }}"><span class="topbar-status-dot" aria-hidden="true"></span>{{ $negocioAbierto ? 'Abierto ahora' : 'Cerrado ahora' }}</span>
                <span class="topbar-tag">Precisión · Estilo · Confianza</span>
            </div>
        </div>
    </div>

    <header class="header">
        <div class="container header-inner">
            <nav class="nav-list nav-left" aria-label="Navegación principal">
                <a class="nav-link" href="#inicio">Inicio</a>
                <a class="nav-link" href="#servicios">Servicios</a>
                <a class="nav-link" href="#estandar">Nuestro estándar</a>
                @if($preguntasFrecuentes->isNotEmpty())<a class="nav-link" href="#preguntas">Preguntas</a>@endif
            </nav>

            <a class="brand" href="#inicio" aria-label="Inicio de {{ $nombreBarberia }}">
                <img class="brand-logo" src="{{ $logoBarberia }}" alt="Logo de {{ $nombreBarberia }}">
                <span class="brand-name">{{ $nombreBarberia }}<small>Barber Studio</small></span>
            </a>

            <nav class="nav-list nav-right" aria-label="Acciones">
                <a class="nav-link" href="#contacto">Contacto</a>
                <button class="btn btn-map" type="button" data-rewards-open><svg class="icon" viewBox="0 0 24 24"><path d="M12 2 3 7v6c0 5 4 9 9 9s9-4 9-9V7l-9-5Z"/><path d="m9 12 2 2 4-5"/></svg><span>Mis recompensas</span></button>
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
                        <button class="btn btn-gold" type="button" data-booking-open><svg class="icon" viewBox="0 0 24 24"><path d="M8 2v3M16 2v3M3.5 9h17M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/><path d="m9 16 2 2 4-5"/></svg>Reservar una cita</button>
                        <a class="btn btn-light" href="#servicios">Conocer servicios</a>
                    </div>
                    <div class="hero-signature">El estilo correcto habla antes que tú.</div>
                </div>
            </div>
        </section>

        <div class="trust-wrap">
            <div class="container trust-bar">
                <div class="trust-item" data-reveal><span class="trust-icon"><svg class="icon" viewBox="0 0 24 24"><path d="m20 6-11 11-5-5"/></svg></span><div><strong>Atención personalizada</strong><span>Escuchamos lo que buscas antes de empezar.</span></div></div>
                <div class="trust-item" data-reveal style="--reveal-delay: 70ms"><span class="trust-icon"><svg class="icon" viewBox="0 0 24 24"><path d="M3 12h18M5 7h14M7 17h10"/><path d="M8 3h8M9 21h6"/></svg></span><div><strong>Precisión profesional</strong><span>Técnica y cuidado en cada acabado.</span></div></div>
                <div class="trust-item" data-reveal style="--reveal-delay: 140ms"><span class="trust-icon"><svg class="icon" viewBox="0 0 24 24"><path d="M8 2v3M16 2v3M3.5 9h17M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/></svg></span><div><strong>Reserva directa</strong><span>Agenda fácilmente desde WhatsApp.</span></div></div>
            </div>
        </div>

        <section class="section" id="servicios">
            <div class="container">
                <div class="section-head" data-reveal>
                    <div><span class="kicker">Servicios seleccionados</span><h2 class="title">Un servicio a la altura de tu estilo.</h2></div>
                    <p class="copy">Opciones claras, atención sin prisas y resultados pensados para tu imagen y rutina.</p>
                </div>

                @if($usarCarrusel)
                    <div class="carousel-controls" aria-label="Controles del carrusel de servicios">
                        <div class="carousel-navigation">
                            <span class="carousel-status" data-carousel-status aria-live="polite"><strong>01</strong> / 01</span>
                            <span class="carousel-progress" aria-hidden="true"><span class="carousel-progress-bar" data-carousel-progress></span></span>
                            <div class="carousel-dots" data-carousel-dots></div>
                        </div>
                        <div class="carousel-buttons">
                            <button class="carousel-button" type="button" data-carousel-prev aria-label="Ver servicios anteriores">
                                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                            </button>
                            <button class="carousel-button" type="button" data-carousel-next aria-label="Ver más servicios">
                                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="services-carousel {{ $usarCarrusel ? 'is-active' : '' }}" @if($usarCarrusel) data-services-carousel @endif>
                <div class="services" @if($usarCarrusel) data-carousel-track tabindex="0" aria-label="Servicios disponibles" @endif>
                    @forelse($servicios as $servicio)
                        <article class="service" data-reveal style="--reveal-delay: {{ min($loop->index * 55, 220) }}ms">
                            <div class="service-media">
                                @if($servicio->imagen)
                                    <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="{{ $servicio->nombre }} en {{ $nombreBarberia }}" loading="lazy">
                                @else
                                    <div class="service-media-empty" role="img" aria-label="Imagen no disponible para {{ $servicio->nombre }}">
                                        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m3 16 4.5-4.5 4 4 2.5-2.5 7 7"/></svg>
                                        <span>Imagen no disponible</span>
                                    </div>
                                @endif
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
                        <div class="services-empty">
                            <strong>Próximamente nuevos servicios</strong>
                            <span class="copy">Contáctanos por WhatsApp para conocer las opciones disponibles.</span>
                        </div>
                    @endforelse
                </div>
                </div>
            </div>
        </section>

        <section class="standard" id="estandar">
            <div class="container standard-grid">
                <div class="standard-dark" data-reveal>
                    <img class="standard-logo" src="{{ $logoFondoOscuro }}" alt="Emblema de {{ $nombreBarberia }}">
                    <div class="standard-quote"><span>Nuestro compromiso</span><h2>Confianza que se construye en cada visita.</h2></div>
                </div>
                <div class="standards-list">
                    <article class="standard-item" data-reveal><span class="standard-number">01</span><div><h3>Primero te escuchamos</h3><p>Entendemos el resultado que buscas y te orientamos para elegir lo que mejor te favorece.</p></div></article>
                    <article class="standard-item" data-reveal style="--reveal-delay: 70ms"><span class="standard-number">02</span><div><h3>Cuidamos el proceso</h3><p>Trabajamos con orden, higiene y atención para que disfrutes el servicio de principio a fin.</p></div></article>
                    <article class="standard-item" data-reveal style="--reveal-delay: 140ms"><span class="standard-number">03</span><div><h3>Revisamos el resultado</h3><p>El servicio termina cuando cada línea, transición y detalle está en su lugar.</p></div></article>
                </div>
            </div>
        </section>

        <section class="contact-section" id="contacto">
            <div class="container contact-card" data-reveal>
                <div class="contact-copy">
                    <span class="kicker">Visítanos</span>
                    <h2 class="title">Tu próxima cita comienza aquí.</h2>
                    <p class="copy">Consulta nuestros horarios y encuentra la barbería fácilmente.</p>
                    <div class="contact-actions">
                        @if($googleMapsUrl)
                            <a class="btn btn-map" href="{{ $googleMapsUrl }}" target="_blank" rel="noopener noreferrer"><svg class="icon" viewBox="0 0 24 24"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>Ver en Google Maps</a>
                        @endif
                    </div>
                </div>
                <div class="contact-details">
                    <div class="detail"><span class="detail-icon"><svg class="icon" viewBox="0 0 24 24"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></span><div><small>Dirección</small><strong>{{ $barberia?->direccion ?? 'Solicita la ubicación por WhatsApp' }}</strong>@if($googleMapsUrl)<a class="duration" href="{{ $googleMapsUrl }}" target="_blank" rel="noopener noreferrer">Abrir ubicación</a>@endif</div></div>
                    <div class="detail"><span class="detail-icon"><svg class="icon" viewBox="0 0 24 24"><path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 3.1 5.2 2 2 0 0 1 5.1 3h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.7 2.6a2 2 0 0 1-.5 2.1L9 10.7a16 16 0 0 0 4.3 4.3l1.3-1.3a2 2 0 0 1 2.1-.5c.8.4 1.7.6 2.6.7a2 2 0 0 1 1.7 2Z"/></svg></span><div><small>Teléfono</small><strong>{{ $barberia?->telefono ?? 'Disponible por WhatsApp' }}</strong></div></div>
                    <div class="detail"><span class="detail-icon"><svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span><div><small>Horarios de atención</small><div class="hours-summary">
                        @forelse($horarios as $horario)
                            <span class="hours-line"><b>{{ $diasSemana[$horario->dia_semana] }}</b><span>{{ $horario->abierto ? substr($horario->hora_apertura, 0, 5) . ' – ' . substr($horario->hora_cierre, 0, 5) : 'Cerrado' }}</span></span>
                        @empty
                            <strong>Consulta disponibilidad por WhatsApp.</strong>
                        @endforelse
                    </div></div></div>
                </div>
            </div>
        </section>

        @if($preguntasFrecuentes->isNotEmpty())
            <section class="faq-section" id="preguntas">
                <div class="container faq-wrap">
                    <div class="faq-intro" data-reveal><span class="kicker">Antes de tu visita</span><h2 class="title">Preguntas frecuentes.</h2><p class="copy">Información clara para que reserves y llegues con toda confianza.</p></div>
                    <div class="faq-list">
                        @foreach($preguntasFrecuentes as $faq)
                            <details class="faq-item" data-reveal style="--reveal-delay: {{ min($loop->index * 55, 220) }}ms" @if($loop->first) open @endif>
                                <summary>{{ $faq->pregunta }}</summary>
                                <div class="faq-answer"><div class="faq-answer-inner">{{ $faq->respuesta }}</div></div>
                            </details>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="cta">
            <div class="container cta-inner" data-reveal><div><h2>Haz espacio para verte mejor.</h2><p>Completa tus datos y envía tu solicitud directamente por WhatsApp.</p></div><button class="btn btn-dark" type="button" data-booking-open><svg class="icon" viewBox="0 0 24 24"><path d="M8 2v3M16 2v3M3.5 9h17M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/></svg>Reservar ahora</button></div>
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

    <div class="booking-modal" id="booking-modal" role="dialog" aria-modal="true" aria-labelledby="booking-title" aria-hidden="true">
        <div class="booking-dialog">
            <div class="booking-head">
                <div><span class="kicker">Solicitud por WhatsApp</span><h2 id="booking-title">Cuéntanos cuándo quieres venir</h2></div>
                <button class="booking-close" type="button" data-booking-close aria-label="Cerrar formulario"><svg class="icon" viewBox="0 0 24 24"><path d="m6 6 12 12M18 6 6 18"/></svg></button>
            </div>
            <form class="booking-body" id="booking-form">
                <div class="booking-grid">
                    <div class="booking-field full"><label for="booking-name">Tu nombre *</label><input type="text" id="booking-name" name="nombre" maxlength="100" autocomplete="name" required></div>
                    <div class="booking-field full"><label for="booking-service">Servicio de interés *</label><select id="booking-service" name="servicio" required><option value="">Selecciona un servicio</option>@foreach($servicios as $servicio)<option value="{{ $servicio->nombre }}" data-duration="{{ $servicio->duracion_minutos }}">{{ $servicio->nombre }} · ${{ number_format($servicio->precio, 2) }}</option>@endforeach<option value="Por definir" data-duration="0">No estoy seguro todavía</option></select></div>
                    <div class="booking-field"><label for="booking-date">Fecha deseada *</label><input type="date" id="booking-date" name="fecha" min="{{ now()->format('Y-m-d') }}" required></div>
                    <div class="booking-field"><label for="booking-time">Hora deseada *</label><input type="time" id="booking-time" name="hora" min="{{ $horaMinima }}" max="{{ $horaMaxima }}" step="900" required></div>
                    <div class="booking-field full"><label for="booking-notes">Comentario opcional</label><textarea id="booking-notes" name="comentarios" maxlength="400" placeholder="Ej. Prefiero un corte con acabado natural."></textarea></div>
                </div>
                <p class="booking-note">Esta solicitud no crea ni confirma una cita automáticamente. Se abrirá WhatsApp y nuestro equipo confirmará contigo la disponibilidad.</p>
                <button class="btn booking-submit" type="submit"><svg class="icon" viewBox="0 0 24 24"><path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4Z"/></svg>Enviar solicitud por WhatsApp</button>
            </form>
        </div>
    </div>

    <div class="booking-modal" id="rewards-modal" role="dialog" aria-modal="true" aria-labelledby="rewards-title" aria-hidden="true">
        <div class="booking-dialog">
            <div class="booking-head">
                <div><span class="kicker">Programa de lealtad</span><h2 id="rewards-title">Consulta tus recompensas</h2></div>
                <button class="booking-close" type="button" data-rewards-close aria-label="Cerrar formulario"><svg class="icon" viewBox="0 0 24 24"><path d="m6 6 12 12M18 6 6 18"/></svg></button>
            </div>
            <div class="booking-body">
                <form id="rewards-form">
                    <div class="booking-grid">
                        <div class="booking-field full">
                            <label for="rewards-phone">Número de teléfono *</label>
                            <input type="tel" id="rewards-phone" name="telefono" placeholder="Ej. 3312345678" maxlength="20" autocomplete="tel" required>
                        </div>
                    </div>
                    <p class="booking-note">Busca tus puntos acumulados y las recompensas disponibles con el número registrado en {{ $nombreBarberia }}.</p>
                    <button class="btn btn-gold rewards-submit" type="submit"><svg class="icon" viewBox="0 0 24 24"><path d="M12 2 3 7v6c0 5 4 9 9 9s9-4 9-9V7l-9-5Z"/><path d="m9 12 2 2 4-5"/></svg>Consultar recompensas</button>
                </form>
                <div class="rewards-result" id="rewards-result" hidden></div>
            </div>
        </div>
    </div>

    <div class="whatsapp-widget" id="whatsapp-widget">
        <span class="whatsapp-prompt" aria-hidden="true">Hola, ¿podemos ayudarte?</span>

        <div class="whatsapp-panel" id="whatsapp-panel" role="dialog" aria-label="Contacto por WhatsApp" aria-hidden="true">
            <div class="chat-header">
                <span class="chat-avatar"><img src="{{ $logoBarberia }}" alt=""></span>
                <div class="chat-identity">
                    <strong>{{ $nombreBarberia }}</strong>
                    <span>En línea · WhatsApp</span>
                </div>
                <button class="chat-close" type="button" aria-label="Cerrar chat">
                    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            </div>

            <div class="chat-body">
                <div class="chat-bubble">
                    <strong>¡Hola! 👋</strong>
                    Gracias por contactar a {{ $nombreBarberia }}. ¿Te gustaría conocer disponibilidad o agendar un servicio?
                    <span class="chat-time">Ahora</span>
                </div>

                <form class="chat-form" action="https://wa.me/{{ $telefonoWhatsapp }}" method="get" target="_blank" rel="noopener noreferrer">
                    <label class="chat-label" for="whatsapp-message">Escribe tu mensaje</label>
                    <textarea class="chat-message" id="whatsapp-message" name="text" placeholder="Ej. Hola, quiero agendar un corte..." required maxlength="500"></textarea>
                    <button class="chat-action" type="submit">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A11.7 11.7 0 0 0 12.1 0C5.6 0 .3 5.3.3 11.8c0 2.1.5 4.1 1.6 5.9L.2 24l6.5-1.7a11.8 11.8 0 0 0 5.4 1.4c6.5 0 11.8-5.3 11.8-11.8 0-3.2-1.2-6.1-3.4-8.4Zm-8.4 18.2c-1.7 0-3.5-.5-5-1.4l-.4-.2-3.8 1 1-3.7-.2-.4a9.8 9.8 0 1 1 8.4 4.7Zm5.4-7.3c-.3-.1-1.7-.8-2-1-.3-.1-.5-.1-.7.2-.2.3-.8 1-.9 1.2-.2.2-.3.2-.6.1-1.7-.8-2.8-1.5-3.9-3.4-.3-.5.3-.5.8-1.6.1-.2 0-.4 0-.6l-.9-2.1c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.2-1.2 2.9s1.2 3.3 1.4 3.5c.1.2 2.4 3.7 5.9 5.2 2.2.9 3.1 1 4.2.8.7-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3-.1-.1-.3-.2-.6-.3Z"/></svg>
                        Enviar por WhatsApp
                    </button>
                </form>
                <span class="chat-note">Se abrirá WhatsApp en una nueva ventana.</span>
            </div>
        </div>

        <button class="whatsapp-toggle" type="button" aria-label="Abrir chat de WhatsApp" aria-controls="whatsapp-panel" aria-expanded="false">
            <span class="whatsapp-badge" aria-hidden="true">1</span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A11.7 11.7 0 0 0 12.1 0C5.6 0 .3 5.3.3 11.8c0 2.1.5 4.1 1.6 5.9L.2 24l6.5-1.7a11.8 11.8 0 0 0 5.4 1.4c6.5 0 11.8-5.3 11.8-11.8 0-3.2-1.2-6.1-3.4-8.4Zm-8.4 18.2c-1.7 0-3.5-.5-5-1.4l-.4-.2-3.8 1 1-3.7-.2-.4a9.8 9.8 0 1 1 8.4 4.7Zm5.4-7.3c-.3-.1-1.7-.8-2-1-.3-.1-.5-.1-.7.2-.2.3-.8 1-.9 1.2-.2.2-.3.2-.6.1-1.7-.8-2.8-1.5-3.9-3.4-.3-.5.3-.5.8-1.6.1-.2 0-.4 0-.6l-.9-2.1c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.2-1.2 2.9s1.2 3.3 1.4 3.5c.1.2 2.4 3.7 5.9 5.2 2.2.9 3.1 1 4.2.8.7-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3-.1-.1-.3-.2-.6-.3Z"/></svg>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const revealElements = document.querySelectorAll('[data-reveal]');

            if (reducedMotion || !('IntersectionObserver' in window)) {
                revealElements.forEach((element) => element.classList.add('is-visible'));
            } else {
                const revealObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) return;
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    });
                }, { threshold: .12, rootMargin: '0px 0px -35px' });
                revealElements.forEach((element) => revealObserver.observe(element));
            }

            document.querySelectorAll('.faq-item').forEach((item) => {
                const summary = item.querySelector('summary');
                let closeTimer;

                if (item.open) item.classList.add('is-expanded');

                summary.addEventListener('click', (event) => {
                    event.preventDefault();
                    window.clearTimeout(closeTimer);

                    if (item.classList.contains('is-expanded')) {
                        item.classList.remove('is-expanded');
                        closeTimer = window.setTimeout(() => item.open = false, reducedMotion ? 0 : 320);
                        return;
                    }

                    item.open = true;
                    window.requestAnimationFrame(() => item.classList.add('is-expanded'));
                });
            });

            const pad = (number) => String(number).padStart(2, '0');
            const toDateValue = (date) => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
            const fromDateValue = (value) => value ? new Date(`${value}T12:00:00`) : new Date();
            const dateLabel = (value) => value ? fromDateValue(value).toLocaleDateString('es-MX', { day: 'numeric', month: 'long', year: 'numeric' }) : 'Seleccionar fecha';
            const months = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
            const weekdays = ['L','M','M','J','V','S','D'];
            const businessHours = @json($horariosReserva);
            const scheduleForDate = (value) => {
                if (!value) return null;
                const date = fromDateValue(value);
                const businessDay = (date.getDay() + 6) % 7;
                const schedule = businessHours[String(businessDay)];
                return schedule?.abierto && schedule.apertura && schedule.cierre ? schedule : null;
            };

            const closeBookingPickers = (except = null) => {
                document.querySelectorAll('.booking-calendar.open, .booking-time-panel.open').forEach((panel) => {
                    if (panel === except) return;
                    panel.classList.remove('open');
                    panel.closest('.booking-picker')?.querySelector('.booking-picker-trigger')?.setAttribute('aria-expanded', 'false');
                });
            };

            const positionBookingPicker = (trigger, panel) => {
                const triggerRect = trigger.getBoundingClientRect();
                const panelWidth = panel.offsetWidth;
                const panelHeight = panel.offsetHeight;
                const left = Math.max(12, Math.min(triggerRect.left, window.innerWidth - panelWidth - 12));
                const fitsBelow = window.innerHeight - triggerRect.bottom >= panelHeight + 10;
                const top = fitsBelow ? triggerRect.bottom + 8 : Math.max(12, triggerRect.top - panelHeight - 8);
                panel.style.left = `${left}px`;
                panel.style.top = `${top}px`;
            };

            const dateInput = document.getElementById('booking-date');
            dateInput.classList.add('booking-native-picker');
            const datePicker = document.createElement('div');
            datePicker.className = 'booking-picker';
            datePicker.innerHTML = `
                <button type="button" class="booking-picker-trigger" aria-expanded="false"><span></span><svg class="icon" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg></button>
                <div class="booking-calendar" role="dialog" aria-label="Seleccionar fecha">
                    <div class="booking-calendar-head"><button type="button" class="booking-calendar-nav prev" aria-label="Mes anterior">‹</button><strong></strong><button type="button" class="booking-calendar-nav next" aria-label="Mes siguiente">›</button></div>
                    <div class="booking-calendar-week">${weekdays.map((day) => `<span>${day}</span>`).join('')}</div>
                    <div class="booking-calendar-days"></div>
                    <div class="booking-calendar-footer"><button type="button">Ir a hoy</button><span>Selecciona un día</span></div>
                </div>`;
            dateInput.insertAdjacentElement('afterend', datePicker);

            const dateTrigger = datePicker.querySelector('.booking-picker-trigger');
            const dateTriggerLabel = dateTrigger.querySelector('span');
            const calendar = datePicker.querySelector('.booking-calendar');
            const calendarTitle = calendar.querySelector('.booking-calendar-head strong');
            const calendarDays = calendar.querySelector('.booking-calendar-days');
            let dateCursor = fromDateValue(dateInput.value);

            const renderCalendar = () => {
                dateTriggerLabel.textContent = dateLabel(dateInput.value);
                calendarTitle.textContent = `${months[dateCursor.getMonth()]} ${dateCursor.getFullYear()}`;
                calendarDays.innerHTML = '';
                const first = new Date(dateCursor.getFullYear(), dateCursor.getMonth(), 1);
                const mondayOffset = (first.getDay() + 6) % 7;
                const gridStart = new Date(dateCursor.getFullYear(), dateCursor.getMonth(), 1 - mondayOffset);
                const today = toDateValue(new Date());

                for (let index = 0; index < 42; index += 1) {
                    const date = new Date(gridStart);
                    date.setDate(gridStart.getDate() + index);
                    const value = toDateValue(date);
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'booking-calendar-day';
                    button.textContent = date.getDate();
                    if (date.getMonth() !== dateCursor.getMonth()) button.classList.add('outside');
                    if (value === today) button.classList.add('today');
                    if (value === dateInput.value) button.classList.add('selected');
                    if ((dateInput.min && value < dateInput.min) || (dateInput.max && value > dateInput.max) || !scheduleForDate(value)) button.disabled = true;
                    button.addEventListener('click', () => {
                        dateInput.value = value;
                        dateInput.dispatchEvent(new Event('change', { bubbles: true }));
                        dateCursor = date;
                        closeBookingPickers();
                        renderCalendar();
                    });
                    calendarDays.appendChild(button);
                }
            };

            dateTrigger.addEventListener('click', () => {
                const opening = !calendar.classList.contains('open');
                closeBookingPickers(calendar);
                calendar.classList.toggle('open', opening);
                dateTrigger.setAttribute('aria-expanded', String(opening));
                if (opening) positionBookingPicker(dateTrigger, calendar);
            });
            calendar.querySelector('.prev').addEventListener('click', () => { dateCursor = new Date(dateCursor.getFullYear(), dateCursor.getMonth() - 1, 1); renderCalendar(); });
            calendar.querySelector('.next').addEventListener('click', () => { dateCursor = new Date(dateCursor.getFullYear(), dateCursor.getMonth() + 1, 1); renderCalendar(); });
            calendar.querySelector('.booking-calendar-footer button').addEventListener('click', () => {
                const today = new Date();
                const value = toDateValue(today);
                if ((!dateInput.min || value >= dateInput.min) && scheduleForDate(value)) {
                    dateInput.value = value;
                    dateInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
                dateCursor = today;
                renderCalendar();
            });
            renderCalendar();

            const timeInput = document.getElementById('booking-time');
            const toMinutes = (value) => { const [hours, minutes] = (value || '00:00').split(':').map(Number); return (hours * 60) + minutes; };
            const toTimeValue = (minutes) => `${pad(Math.floor(minutes / 60))}:${pad(minutes % 60)}`;
            const timeLabel = (value) => value ? new Date(`2000-01-01T${value}`).toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' }) : 'Seleccionar hora';
            timeInput.classList.add('booking-native-picker');
            const timePicker = document.createElement('div');
            timePicker.className = 'booking-picker';
            timePicker.innerHTML = `
                <button type="button" class="booking-picker-trigger" aria-expanded="false"><span></span><svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></button>
                <div class="booking-time-panel" role="dialog" aria-label="Seleccionar hora">
                    <div class="booking-time-head"><strong>Selecciona un horario</strong><span>Intervalos de 15 min</span></div>
                    <div class="booking-time-period morning"><div class="booking-time-label">Mañana</div><div class="booking-time-slots"></div></div>
                    <div class="booking-time-period afternoon"><div class="booking-time-label">Tarde</div><div class="booking-time-slots"></div></div>
                </div>`;
            timeInput.insertAdjacentElement('afterend', timePicker);
            const timeTrigger = timePicker.querySelector('.booking-picker-trigger');
            const timeTriggerLabel = timeTrigger.querySelector('span');
            const timePanel = timePicker.querySelector('.booking-time-panel');
            const bookingService = document.getElementById('booking-service');

            const renderTimes = () => {
                const morning = timePanel.querySelector('.morning .booking-time-slots');
                const afternoon = timePanel.querySelector('.afternoon .booking-time-slots');
                const morningPeriod = morning.closest('.booking-time-period');
                const afternoonPeriod = afternoon.closest('.booking-time-period');
                const panelMeta = timePanel.querySelector('.booking-time-head span');
                morning.innerHTML = '';
                afternoon.innerHTML = '';
                const step = Math.max(5, Number(timeInput.step || 900) / 60);
                const schedule = scheduleForDate(dateInput.value);

                if (!schedule) {
                    timeInput.value = '';
                    timeTrigger.disabled = true;
                    timeTriggerLabel.textContent = dateInput.value ? 'Día cerrado' : 'Selecciona primero una fecha';
                    panelMeta.textContent = 'Sin horario disponible';
                    morningPeriod.hidden = false;
                    afternoonPeriod.hidden = true;
                    morning.innerHTML = '<span class="booking-time-empty">Elige en el calendario un día marcado como disponible.</span>';
                    closeBookingPickers();
                    return;
                }

                timeTrigger.disabled = false;
                timeInput.min = schedule.apertura;
                timeInput.max = schedule.cierre;
                panelMeta.textContent = `${schedule.apertura} – ${schedule.cierre} · cada 15 min`;
                morningPeriod.hidden = false;
                afternoonPeriod.hidden = false;
                const serviceDuration = Math.max(15, Number(bookingService.selectedOptions[0]?.dataset.duration || 0));
                const firstMinute = toMinutes(schedule.apertura);
                const lastMinute = toMinutes(schedule.cierre) - serviceDuration;
                const selectedMinutes = timeInput.value ? toMinutes(timeInput.value) : null;

                if (selectedMinutes !== null && (selectedMinutes < firstMinute || selectedMinutes > lastMinute)) {
                    timeInput.value = '';
                }

                timeTriggerLabel.textContent = timeLabel(timeInput.value);

                for (let minutes = firstMinute; minutes <= lastMinute; minutes += step) {
                    const value = toTimeValue(minutes);
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'booking-time-slot';
                    button.textContent = timeLabel(value);
                    if (value === timeInput.value.slice(0, 5)) button.classList.add('selected');
                    button.addEventListener('click', () => {
                        timeInput.value = value;
                        timeInput.dispatchEvent(new Event('change', { bubbles: true }));
                        closeBookingPickers();
                        renderTimes();
                    });
                    (minutes < 12 * 60 ? morning : afternoon).appendChild(button);
                }

                if (!morning.children.length) morningPeriod.hidden = true;
                if (!afternoon.children.length) afternoonPeriod.hidden = true;
            };

            timeTrigger.addEventListener('click', () => {
                const opening = !timePanel.classList.contains('open');
                closeBookingPickers(timePanel);
                timePanel.classList.toggle('open', opening);
                timeTrigger.setAttribute('aria-expanded', String(opening));
                if (opening) positionBookingPicker(timeTrigger, timePanel);
            });
            timeInput.addEventListener('change', renderTimes);
            dateInput.addEventListener('change', () => {
                dateCursor = fromDateValue(dateInput.value);
                renderCalendar();
                renderTimes();
            });
            bookingService.addEventListener('change', renderTimes);
            renderTimes();

            document.addEventListener('click', (event) => {
                if (!event.target.closest('.booking-picker')) closeBookingPickers();
            });
            window.addEventListener('resize', () => closeBookingPickers());

            const bookingModal = document.getElementById('booking-modal');
            const bookingForm = document.getElementById('booking-form');
            const bookingClose = bookingModal.querySelector('[data-booking-close]');
            let bookingTrigger = null;

            const setBookingOpen = (open) => {
                if (!open) closeBookingPickers();
                bookingModal.classList.toggle('is-open', open);
                bookingModal.setAttribute('aria-hidden', String(!open));
                document.body.classList.toggle('booking-open', open);

                if (open) {
                    window.setTimeout(() => document.getElementById('booking-name').focus(), 50);
                } else if (bookingTrigger) {
                    bookingTrigger.focus();
                }
            };

            document.querySelectorAll('[data-booking-open]').forEach((button) => {
                button.addEventListener('click', () => {
                    bookingTrigger = button;
                    setBookingOpen(true);
                });
            });

            bookingClose.addEventListener('click', () => setBookingOpen(false));
            bookingModal.addEventListener('click', (event) => {
                if (event.target === bookingModal) setBookingOpen(false);
            });

            bookingForm.addEventListener('submit', (event) => {
                event.preventDefault();
                const data = new FormData(bookingForm);
                const rawDate = String(data.get('fecha'));
                const formattedDate = new Date(`${rawDate}T12:00:00`).toLocaleDateString('es-MX', { day: '2-digit', month: 'long', year: 'numeric' });
                const comments = String(data.get('comentarios') || '').trim();
                const businessName = @json($nombreBarberia);
                const message = [
                    `Hola, quiero solicitar una cita en ${businessName}.`,
                    '',
                    `Nombre: ${data.get('nombre')}`,
                    `Servicio: ${data.get('servicio')}`,
                    `Fecha deseada: ${formattedDate}`,
                    `Hora deseada: ${data.get('hora')}`,
                    comments ? `Comentarios: ${comments}` : null,
                    '',
                    'Entiendo que la cita queda pendiente de confirmación por este medio.',
                ].filter((line) => line !== null).join('\n');

                window.open(@json('https://wa.me/' . $telefonoWhatsapp) + `?text=${encodeURIComponent(message)}`, '_blank', 'noopener,noreferrer');
                setBookingOpen(false);
            });

            const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
            }[char]));

            const rewardsModal = document.getElementById('rewards-modal');
            const rewardsForm = document.getElementById('rewards-form');
            const rewardsResult = document.getElementById('rewards-result');
            const rewardsClose = rewardsModal.querySelector('[data-rewards-close]');
            const rewardsSubmit = rewardsForm.querySelector('button[type="submit"]');
            let rewardsTrigger = null;

            const setRewardsOpen = (open) => {
                rewardsModal.classList.toggle('is-open', open);
                rewardsModal.setAttribute('aria-hidden', String(!open));
                document.body.classList.toggle('booking-open', open);

                if (open) {
                    window.setTimeout(() => document.getElementById('rewards-phone').focus(), 50);
                } else if (rewardsTrigger) {
                    rewardsTrigger.focus();
                }
            };

            const renderRewardsResult = (data) => {
                rewardsResult.hidden = false;

                if (!data.encontrado) {
                    rewardsResult.innerHTML = `<div class="rewards-empty">${escapeHtml(data.mensaje || 'No encontramos un cliente con ese número.')}</div>`;
                    return;
                }

                const rewardsHtml = data.recompensas.length
                    ? data.recompensas.map((recompensa) => `
                        <div class="reward-item ${recompensa.disponible ? 'is-available' : ''}">
                            <div>
                                <div class="reward-item-name">${escapeHtml(recompensa.nombre)}</div>
                                ${recompensa.descripcion ? `<div class="reward-item-desc">${escapeHtml(recompensa.descripcion)}</div>` : ''}
                            </div>
                            <div class="reward-item-points">${escapeHtml(recompensa.puntos_requeridos)} pts${recompensa.disponible ? ' · Disponible' : ''}</div>
                        </div>
                    `).join('')
                    : '<div class="rewards-empty">Aún no hay recompensas configuradas.</div>';

                rewardsResult.innerHTML = `
                    <div class="rewards-client"><strong>Cliente verificado</strong><span class="rewards-points">Disponibilidad protegida</span></div>
                    ${rewardsHtml}
                `;
            };

            document.querySelectorAll('[data-rewards-open]').forEach((button) => {
                button.addEventListener('click', () => {
                    rewardsTrigger = button;
                    rewardsForm.reset();
                    rewardsResult.hidden = true;
                    rewardsResult.innerHTML = '';
                    setRewardsOpen(true);
                });
            });

            rewardsClose.addEventListener('click', () => setRewardsOpen(false));
            rewardsModal.addEventListener('click', (event) => {
                if (event.target === rewardsModal) setRewardsOpen(false);
            });

            rewardsForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                const telefono = document.getElementById('rewards-phone').value.trim();
                if (!telefono) return;

                rewardsSubmit.disabled = true;
                const originalLabel = rewardsSubmit.innerHTML;
                rewardsSubmit.textContent = 'Buscando...';

                try {
                    const response = await fetch(`{{ route('landing.recompensas.consultar') }}?telefono=${encodeURIComponent(telefono)}`, {
                        headers: { 'Accept': 'application/json' },
                    });

                    if (!response.ok) throw new Error('request-failed');

                    renderRewardsResult(await response.json());
                } catch (error) {
                    rewardsResult.hidden = false;
                    rewardsResult.innerHTML = '<div class="rewards-error">Ocurrió un error al consultar. Intenta de nuevo.</div>';
                } finally {
                    rewardsSubmit.disabled = false;
                    rewardsSubmit.innerHTML = originalLabel;
                }
            });

            document.querySelectorAll('[data-services-carousel]').forEach((carousel) => {
                const track = carousel.querySelector('[data-carousel-track]');
                const controls = carousel.previousElementSibling;
                const previous = controls?.querySelector('[data-carousel-prev]');
                const next = controls?.querySelector('[data-carousel-next]');
                const dotsContainer = controls?.querySelector('[data-carousel-dots]');
                const status = controls?.querySelector('[data-carousel-status]');
                const progressBar = controls?.querySelector('[data-carousel-progress]');

                if (!track || !previous || !next) return;

                const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const easeInOutCubic = (t) => (t < .5 ? 4 * t * t * t : 1 - ((-2 * t + 2) ** 3) / 2);
                let scrollAnimation = null;

                const stopScrollAnimation = () => {
                    if (scrollAnimation) cancelAnimationFrame(scrollAnimation);
                    scrollAnimation = null;
                    track.classList.remove('is-animating');
                };

                const smoothScrollTo = (target) => {
                    const maximum = track.scrollWidth - track.clientWidth;
                    const clamped = Math.min(Math.max(target, 0), maximum);
                    const start = track.scrollLeft;
                    const distance = clamped - start;
                    stopScrollAnimation();
                    if (Math.abs(distance) < 1) {
                        track.scrollLeft = clamped;
                        return;
                    }

                    if (reduceMotion) {
                        track.scrollLeft = clamped;
                        return;
                    }

                    const duration = Math.min(900, Math.max(650, Math.abs(distance) * .58));
                    let startTime = null;
                    track.classList.add('is-animating');

                    const step = (timestamp) => {
                        if (startTime === null) startTime = timestamp;
                        const progress = Math.min((timestamp - startTime) / duration, 1);
                        track.scrollLeft = start + distance * easeInOutCubic(progress);

                        if (progress < 1) {
                            scrollAnimation = requestAnimationFrame(step);
                            return;
                        }

                        track.scrollLeft = clamped;
                        scrollAnimation = null;
                        requestAnimationFrame(() => track.classList.remove('is-animating'));
                    };

                    scrollAnimation = requestAnimationFrame(step);
                };

                const getCardStep = () => {
                    const card = track.querySelector('.service');
                    if (!card) return track.clientWidth;
                    const gap = Number.parseFloat(getComputedStyle(track).columnGap) || 0;
                    return card.getBoundingClientRect().width + gap;
                };

                const getCardsPerView = () => Math.max(1, Math.round(track.clientWidth / getCardStep()));

                const getPageTargets = () => {
                    const cardCount = track.querySelectorAll('.service').length;
                    const perView = getCardsPerView();
                    const pageCount = Math.max(1, Math.ceil(cardCount / perView));
                    const maximum = Math.max(0, track.scrollWidth - track.clientWidth);
                    return Array.from({ length: pageCount }, (_, page) => Math.min(page * perView * getCardStep(), maximum));
                };

                const getCurrentPage = () => {
                    const targets = getPageTargets();
                    return targets.reduce((closest, target, index) => (
                        Math.abs(target - track.scrollLeft) < Math.abs(targets[closest] - track.scrollLeft) ? index : closest
                    ), 0);
                };

                const scrollToPage = (page) => {
                    const targets = getPageTargets();
                    const index = Math.min(Math.max(page, 0), targets.length - 1);
                    smoothScrollTo(targets[index]);
                };

                const move = (direction) => scrollToPage(getCurrentPage() + direction);

                const buildDots = () => {
                    if (!dotsContainer) return;
                    const cards = track.querySelectorAll('.service');
                    const perView = getCardsPerView();
                    const pageCount = Math.max(1, Math.ceil(cards.length / perView));
                    dotsContainer.innerHTML = '';

                    for (let page = 0; page < pageCount; page += 1) {
                        const dot = document.createElement('button');
                        dot.type = 'button';
                        dot.className = 'carousel-dot';
                        dot.setAttribute('aria-label', `Ir a la página ${page + 1} de servicios`);
                        dot.addEventListener('click', () => scrollToPage(page));
                        dotsContainer.appendChild(dot);
                    }
                };

                const updateControls = () => {
                    const maximum = track.scrollWidth - track.clientWidth;
                    previous.disabled = track.scrollLeft <= 2;
                    next.disabled = track.scrollLeft >= maximum - 2;

                    const targets = getPageTargets();
                    const activePage = getCurrentPage();
                    [...(dotsContainer?.children || [])].forEach((dot, index) => {
                        const active = index === activePage;
                        dot.classList.toggle('is-active', active);
                        dot.setAttribute('aria-current', active ? 'true' : 'false');
                    });

                    if (status) status.innerHTML = `<strong>${String(activePage + 1).padStart(2, '0')}</strong> / ${String(targets.length).padStart(2, '0')}`;
                    if (progressBar) progressBar.style.transform = `scaleX(${(activePage + 1) / targets.length})`;
                };

                previous.addEventListener('click', () => move(-1));
                next.addEventListener('click', () => move(1));
                let controlsFrame = null;
                track.addEventListener('scroll', () => {
                    if (controlsFrame) return;
                    controlsFrame = requestAnimationFrame(() => {
                        updateControls();
                        controlsFrame = null;
                    });
                }, { passive: true });
                track.addEventListener('wheel', () => {
                    stopScrollAnimation();
                }, { passive: true });
                track.addEventListener('keydown', (event) => {
                    if (event.key === 'ArrowRight') { event.preventDefault(); move(1); }
                    if (event.key === 'ArrowLeft') { event.preventDefault(); move(-1); }
                });

                let resizeTimer = null;
                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(() => {
                        buildDots();
                        updateControls();
                    }, 120);
                });

                track.querySelectorAll('img').forEach((image) => image.addEventListener('load', () => {
                    buildDots();
                    updateControls();
                }, { once: true }));

                let isDragging = false;
                let dragMoved = false;
                let dragStartX = 0;
                let dragStartScroll = 0;
                let dragLastScroll = 0;
                let dragLastTime = 0;
                let dragVelocity = 0;

                track.addEventListener('pointerdown', (event) => {
                    if (event.pointerType === 'touch' || event.button !== 0) return;
                    isDragging = true;
                    dragMoved = false;
                    dragStartX = event.clientX;
                    dragStartScroll = track.scrollLeft;
                    dragLastScroll = track.scrollLeft;
                    dragLastTime = performance.now();
                    dragVelocity = 0;
                    track.classList.add('is-dragging');
                    track.setPointerCapture(event.pointerId);
                    stopScrollAnimation();
                });

                track.addEventListener('pointermove', (event) => {
                    if (!isDragging) return;
                    const delta = event.clientX - dragStartX;
                    if (Math.abs(delta) > 3) dragMoved = true;
                    track.scrollLeft = dragStartScroll - delta;
                    const now = performance.now();
                    const elapsed = Math.max(1, now - dragLastTime);
                    dragVelocity = (track.scrollLeft - dragLastScroll) / elapsed;
                    dragLastScroll = track.scrollLeft;
                    dragLastTime = now;
                });

                const endDrag = (event) => {
                    if (!isDragging) return;
                    isDragging = false;
                    track.classList.remove('is-dragging');
                    try { track.releasePointerCapture(event.pointerId); } catch (error) { /* pointer already released */ }
                    const step = getCardStep();
                    if (step > 0) {
                        const projected = track.scrollLeft + (dragVelocity * 170);
                        smoothScrollTo(Math.round(projected / step) * step);
                    }
                };

                track.addEventListener('pointerup', endDrag);
                track.addEventListener('pointercancel', endDrag);
                track.addEventListener('click', (event) => {
                    if (dragMoved) { event.preventDefault(); event.stopPropagation(); }
                }, true);

                buildDots();
                updateControls();
            });

            const widget = document.getElementById('whatsapp-widget');
            const panel = document.getElementById('whatsapp-panel');
            const toggle = widget.querySelector('.whatsapp-toggle');
            const close = widget.querySelector('.chat-close');

            const setOpen = (open) => {
                widget.classList.toggle('is-open', open);
                widget.classList.remove('show-prompt');
                toggle.setAttribute('aria-expanded', String(open));
                toggle.setAttribute('aria-label', open ? 'Cerrar chat de WhatsApp' : 'Abrir chat de WhatsApp');
                panel.setAttribute('aria-hidden', String(!open));
            };

            toggle.addEventListener('click', () => setOpen(!widget.classList.contains('is-open')));
            close.addEventListener('click', () => {
                setOpen(false);
                toggle.focus();
            });

            document.addEventListener('click', (event) => {
                if (!widget.contains(event.target)) setOpen(false);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && bookingModal.classList.contains('is-open')) {
                    setBookingOpen(false);
                    return;
                }

                if (event.key === 'Escape' && rewardsModal.classList.contains('is-open')) {
                    setRewardsOpen(false);
                    return;
                }

                if (event.key === 'Escape' && widget.classList.contains('is-open')) {
                    setOpen(false);
                    toggle.focus();
                }
            });

            window.setTimeout(() => widget.classList.add('show-prompt'), 1400);
        });
    </script>
</body>
</html>
