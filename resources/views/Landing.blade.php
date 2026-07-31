<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $barberia->nombre ?? 'BarberCore' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/branding/barbercore-192.png') }}">

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

    * {
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        margin: 0;
        background: var(--fondo);
        color: var(--texto);
    }

    a {
        text-decoration: none;
        color: inherit;
    }

    .container {
        width: min(1180px, calc(100% - 32px));
        margin: 0 auto;
    }

    .site-header {
        position: sticky;
        top: 0;
        z-index: 40;
        background: rgba(250, 248, 242, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--borde);
    }

    .site-header-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 14px 0;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .brand img {
        width: 52px;
        height: 52px;
        object-fit: cover;
        border-radius: 16px;
        border: 1px solid var(--borde);
        background: white;
    }

    .brand-placeholder {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        border: 1px solid var(--borde);
        background: var(--dorado);
        color: white;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 800;
    }

    .brand-text strong {
        display: block;
        font-size: 22px;
    }

    .brand-text span {
        display: block;
        font-size: 13px;
        color: var(--gris);
    }

    .nav {
        display: flex;
        gap: 18px;
        align-items: center;
        flex-wrap: wrap;
    }

    .nav a {
        font-size: 14px;
        color: var(--gris);
        font-weight: 700;
    }

    .nav a:hover {
        color: var(--dorado);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 999px;
        padding: 14px 20px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .btn-primary {
        background: var(--dorado);
        color: white;
    }

    .btn-primary:hover {
        opacity: 0.92;
    }

    .btn-secondary {
        background: white;
        color: var(--texto);
        border: 1px solid var(--borde);
    }

    .hero {
        padding: 72px 0 54px;
    }

    .hero-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 28px;
        align-items: center;
    }

    .eyebrow {
        color: var(--dorado);
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-size: 13px;
        margin-bottom: 12px;
    }

    .hero h1 {
        font-size: clamp(36px, 6vw, 62px);
        line-height: 1.05;
        margin: 0 0 18px;
    }

    .hero p {
        font-size: 18px;
        color: var(--gris);
        line-height: 1.7;
        margin: 0 0 28px;
        max-width: 640px;
    }

    .hero-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .hero-card {
        background: white;
        border: 1px solid var(--borde);
        border-radius: 28px;
        padding: 20px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.05);
    }

    .hero-visual {
        min-height: 480px;
        border-radius: 28px;
        border: 1px solid var(--borde);
        background:
            linear-gradient(135deg, rgba(201,162,39,0.16), rgba(201,162,39,0.03)),
            radial-gradient(circle at top right, rgba(201,162,39,0.18), transparent 40%),
            #ffffff;
        display: flex;
        align-items: end;
        justify-content: center;
        padding: 24px;
        overflow: hidden;
    }

    .placeholder-stack {
        width: 100%;
        display: grid;
        gap: 16px;
    }

    .placeholder-box {
        background: linear-gradient(135deg, #f5efe3, #ffffff);
        border: 1px solid var(--borde);
        border-radius: 24px;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gris);
        font-weight: 700;
        text-align: center;
        padding: 16px;
    }

    section {
        padding: 34px 0;
    }

    .section-title {
        margin: 0 0 10px;
        font-size: 34px;
    }

    .section-copy {
        margin: 0 0 24px;
        color: var(--gris);
        line-height: 1.7;
        max-width: 760px;
    }

    .cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }

    .card {
        background: white;
        border: 1px solid var(--borde);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(0,0,0,0.04);
    }

    .card-image {
        height: 180px;
        background:
            linear-gradient(135deg, rgba(201,162,39,0.16), rgba(201,162,39,0.04)),
            #f7f3eb;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gris);
        font-weight: 700;
        text-align: center;
        padding: 16px;
    }

    .card-body {
        padding: 18px;
    }

    .card-tag {
        color: var(--dorado);
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 8px;
    }

    .card h3 {
        margin: 0 0 10px;
        font-size: 22px;
    }

    .card p {
        margin: 0 0 12px;
        color: var(--gris);
        line-height: 1.6;
        font-size: 14px;
    }

    .card-price {
        font-weight: 800;
        color: var(--texto);
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .panel {
        background: white;
        border: 1px solid var(--borde);
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 10px 24px rgba(0,0,0,0.04);
    }

    .gallery {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .gallery-item {
        min-height: 220px;
        border-radius: 24px;
        border: 1px solid var(--borde);
        background:
            linear-gradient(135deg, rgba(201,162,39,0.14), rgba(255,255,255,0.05)),
            #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gris);
        font-weight: 700;
        text-align: center;
        padding: 16px;
    }

    .cta {
        background: white;
        border: 1px solid var(--borde);
        border-radius: 28px;
        padding: 28px;
        text-align: center;
        box-shadow: 0 12px 28px rgba(0,0,0,0.05);
    }

    .cta p {
        color: var(--gris);
        margin: 10px 0 20px;
    }

    .site-footer {
        margin-top: 18px;
        padding: 28px 0 50px;
        border-top: 1px solid var(--borde);
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.9fr;
        gap: 18px;
        align-items: start;
    }

    .footer-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--dorado);
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .footer-copy,
    .footer-list {
        color: var(--gris);
        line-height: 1.7;
        font-size: 14px;
    }

    .footer-list div {
        margin-bottom: 6px;
    }

    .footer-socials {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .social-icon {
        width: 36px;
        height: 36px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 8px 16px rgba(0,0,0,0.10);
        transition: transform 0.18s ease, opacity 0.18s ease;
    }

    .social-icon:hover {
        transform: translateY(-2px);
        opacity: 0.92;
    }

    .social-facebook {
        background: #1877F2;
    }

    .social-instagram {
        background: linear-gradient(135deg, #F58529, #DD2A7B, #8134AF, #515BD4);
    }

    .social-tiktok {
        background: #111111;
    }

    .social-icon svg {
        width: 15px;
        height: 15px;
        fill: currentColor;
    }

    .floating-actions {
        position: fixed;
        right: 18px;
        bottom: 18px;
        display: grid;
        gap: 14px;
        z-index: 60;
    }

    .fab {
        width: 62px;
        height: 62px;
        border-radius: 999px;
        border: none;
        box-shadow: 0 12px 26px rgba(0,0,0,0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 22px;
        font-weight: 900;
        color: white;
    }

    .fab-login {
        background: var(--blanco);
        color: var(--texto);
        border: 1px solid var(--borde);
    }

    .fab-whatsapp {
        background: var(--verde);
    }

    .fab-top {
        background: var(--oscuro);
    }

    @media (max-width: 1000px) {
        .hero-grid,
        .grid-2,
        .footer-grid {
            grid-template-columns: 1fr;
        }

        .cards {
            grid-template-columns: repeat(2, 1fr);
        }

        .gallery {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 700px) {
        .site-header-inner {
            flex-direction: column;
            align-items: flex-start;
        }

        .nav {
            gap: 12px;
        }

        .hero {
            padding-top: 42px;
        }

        .cards,
        .gallery {
            grid-template-columns: 1fr;
        }

        .floating-actions {
            right: 14px;
            bottom: 14px;
        }

        .fab {
            width: 56px;
            height: 56px;
        }
    }
</style>
</head>
<body id="top">

<header class="site-header">
    <div class="container site-header-inner">
        <a href="{{ route('landing') }}" class="brand">
            <img src="{{ asset('images/branding/barbercore-192.png') }}"
                 alt="BarberCore"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="brand-placeholder">✂</div>
            <div class="brand-text">
                <strong>{{ $barberia->nombre ?? 'BarberCore' }}</strong>
                <span>Experiencia, estilo y cuidado personal</span>
            </div>
        </a>

        <nav class="nav">
            <a href="#inicio">Inicio</a>
            <a href="#servicios">Servicios</a>
            <a href="#espacio">Nuestro espacio</a>
            <a href="#contacto">Contacto</a>
            <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
        </nav>
    </div>
</header>

<section class="hero" id="inicio">
    <div class="container hero-grid">
        <div>
            <div class="eyebrow">BarberCore</div>
            <h1>Más que un corte, una experiencia de estilo y confianza</h1>
            <p>
                Diseñamos un espacio masculino moderno donde cada visita combina técnica,
                detalle y atención personalizada. Aunque todavía no tengas todas las imágenes,
                ya puedes mostrar una marca sólida, elegante y lista para crecer.
            </p>

            <div class="hero-actions">
                <a href="{{ route('login') }}" class="btn btn-primary">Entrar al panel</a>
                <a href="https://wa.me/{{ $telefonoWhatsapp }}?text=Hola,%20quiero%20informaci%C3%B3n%20sobre%20BarberCore"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn btn-secondary">
                    Contactar por WhatsApp
                </a>
            </div>
        </div>

        <div class="hero-card">
            <div class="hero-visual">
                <div class="placeholder-stack">
                    <div class="placeholder-box">Espacio para imagen principal de la barbería</div>
                    <div class="placeholder-box">Aquí luego puedes poner una foto de cortes, barba o interior</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="servicios">
    <div class="container">
        <h2 class="section-title">Servicios destacados</h2>
        <p class="section-copy">
            Presenta tus servicios principales de forma clara, elegante y comercial.
        </p>

        <div class="cards">
            @forelse($servicios as $servicio)
                <article class="card">
                    <div class="card-image">
                        Placeholder de imagen para<br>{{ $servicio->nombre }}
                    </div>
                    <div class="card-body">
                        <div class="card-tag">Servicio</div>
                        <h3>{{ $servicio->nombre }}</h3>
                        <p>{{ $servicio->descripcion ?: 'Servicio profesional con atención personalizada.' }}</p>
                        <div class="card-price">${{ number_format($servicio->precio, 2) }}</div>
                    </div>
                </article>
            @empty
                @for($i = 1; $i <= 4; $i++)
                    <article class="card">
                        <div class="card-image">Placeholder de servicio {{ $i }}</div>
                        <div class="card-body">
                            <div class="card-tag">Servicio</div>
                            <h3>Servicio BarberCore {{ $i }}</h3>
                            <p>Espacio listo para colocar el texto real de tu servicio cuando quieras.</p>
                            <div class="card-price">$0.00</div>
                        </div>
                    </article>
                @endfor
            @endforelse
        </div>
    </div>
</section>

<section id="espacio">
    <div class="container grid-2">
        <div class="panel">
            <h2 class="section-title">Un espacio que proyecta identidad</h2>
            <p class="section-copy">
                Esta sección queda preparada para mostrar fotos reales del local, estaciones de trabajo o resultados.
            </p>

            <div class="gallery">
                <div class="gallery-item">Foto futura del interior</div>
                <div class="gallery-item">Foto futura de estación de trabajo</div>
                <div class="gallery-item">Foto futura de cortes o clientes</div>
            </div>
        </div>

        <div class="panel">
            <h2 class="section-title">Por qué elegir BarberCore</h2>
            <p class="section-copy">
                Atención personalizada, servicios bien definidos, imagen profesional
                y una presencia digital lista para crecer con tu barbería.
            </p>

            <div class="footer-list">
                <div>• Marca visual coherente y moderna</div>
                <div>• Servicios claros y fáciles de explorar</div>
                <div>• Acceso rápido al panel administrativo</div>
                <div>• Contacto directo por WhatsApp para nuevos clientes</div>
                <div>• Diseño preparado para agregar imágenes reales después</div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="cta">
            <h2 class="section-title">Agenda tu próxima experiencia</h2>
            <p>
                Esta sección queda lista para luego conectarla a formularios, citas o reservas.
            </p>
            <a href="https://wa.me/{{ $telefonoWhatsapp }}?text=Hola,%20quiero%20agendar%20una%20cita"
               target="_blank"
               rel="noopener noreferrer"
               class="btn btn-primary">
                Solicitar información
            </a>
        </div>
    </div>
</section>

<footer class="site-footer" id="contacto">
    <div class="container footer-grid">
        <div>
            <div class="footer-title">BarberCore</div>
            <div class="footer-copy">
                {{ $barberia->nombre ?? 'BarberCore Studio' }}<br>
                {{ $barberia->direccion ?? 'Dirección pendiente por actualizar' }}<br>
                {{ $barberia->telefono ?? 'Teléfono pendiente' }}
            </div>
        </div>

        <div>
            <div class="footer-title">Síguenos</div>
            <div class="footer-copy">
                Encuéntranos también en nuestras redes sociales.
            </div>

            <div class="footer-socials">
                <a href="https://facebook.com/"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="social-icon social-facebook"
                   title="Facebook">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M13.5 22v-8h2.7l.4-3h-3.1V9.1c0-.9.3-1.6 1.6-1.6H16.7V4.8c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.4V11H7v3h3v8h3.5z"/>
                    </svg>
                </a>

                <a href="https://instagram.com/"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="social-icon social-instagram"
                   title="Instagram">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9zm9.75 1.5a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                    </svg>
                </a>

                <a href="https://tiktok.com/"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="social-icon social-tiktok"
                   title="TikTok">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M14.5 3c.5 2 1.7 3.3 3.8 3.6v2.7c-1.4 0-2.6-.4-3.6-1.1v6.1a5.3 5.3 0 1 1-5.3-5.3c.3 0 .6 0 .9.1v2.8a2.7 2.7 0 1 0 1.7 2.5V3h2.5z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>

<div class="floating-actions">
    <a href="{{ route('login') }}" class="fab fab-login" title="Ir al login">⌂</a>

    <a href="https://wa.me/{{ $telefonoWhatsapp }}?text=Hola,%20quiero%20informaci%C3%B3n%20sobre%20BarberCore"
       class="fab fab-whatsapp"
       target="_blank"
       rel="noopener noreferrer"
       title="Contactar por WhatsApp">
        ✆
    </a>

    <button type="button" class="fab fab-top" title="Ir arriba" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
        ↑
    </button>
</div>

</body>
</html>