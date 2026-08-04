<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1C1C1C">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Iniciar sesión | BarberCore</title>
    <link rel="icon" type="image/png" href="{{ asset('images/branding/icon_192_Barbercore.png') }}">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --gold:#C9A227; --gold-dark:#927015; --ink:#1C1C1C; --paper:#FAF8F2; --white:#fff; --muted:#6B6B6B; --border:#E5E0D6; --danger:#C62828; }
        * { box-sizing:border-box; }
        html, body { min-height:100%; }
        body { margin:0; min-width:320px; background:var(--paper); color:var(--ink); font-family:'DM Sans',sans-serif; }
        button, input { font:inherit; }
        .login-page { min-height:100vh; min-height:100dvh; display:grid; grid-template-columns:minmax(420px,1.18fr) minmax(420px,.82fr); overflow:hidden; }

        .login-visual { position:relative; min-height:100vh; isolation:isolate; overflow:hidden; background:#141414; color:var(--white); }
        .login-visual::before { content:''; position:absolute; inset:-3%; z-index:-3; background:url('{{ asset('images/landing/hero-barbershop-premium.png') }}') center/cover no-repeat; animation:ambientZoom 18s ease-in-out infinite alternate; }
        .login-visual::after { content:''; position:absolute; inset:0; z-index:-2; background:linear-gradient(105deg,rgba(15,15,15,.9) 0%,rgba(15,15,15,.67) 48%,rgba(15,15,15,.25) 100%),linear-gradient(0deg,rgba(0,0,0,.5),transparent 55%); }
        .visual-grain { position:absolute; inset:0; z-index:-1; opacity:.1; pointer-events:none; background-image:radial-gradient(rgba(255,255,255,.65) .55px,transparent .55px); background-size:5px 5px; }
        .visual-content { height:100%; display:flex; flex-direction:column; justify-content:space-between; padding:clamp(34px,5vw,72px); }
        .visual-mark { display:flex; align-items:center; gap:12px; font-family:'Manrope',sans-serif; font-size:14px; font-weight:800; letter-spacing:1.5px; text-transform:uppercase; animation:fadeDown .7s .1s both; }
        .visual-mark::before { content:''; width:30px; height:2px; background:var(--gold); }
        .visual-copy { max-width:570px; padding-bottom:4vh; }
        .visual-copy span { display:block; margin-bottom:16px; color:#e5c75d; font-size:11px; font-weight:800; letter-spacing:2.3px; text-transform:uppercase; animation:fadeUp .7s .25s both; }
        .visual-copy h2 { max-width:520px; margin:0; font-family:'Manrope',sans-serif; font-size:clamp(38px,5vw,68px); line-height:1.02; letter-spacing:-2.8px; animation:fadeUp .8s .38s both; }
        .visual-copy p { max-width:440px; margin:22px 0 0; color:rgba(255,255,255,.72); font-size:15px; line-height:1.7; animation:fadeUp .8s .5s both; }
        .visual-foot { display:flex; align-items:center; gap:10px; color:rgba(255,255,255,.55); font-size:11px; letter-spacing:.7px; animation:fadeUp .8s .6s both; }
        .visual-foot i { width:7px; height:7px; border-radius:50%; background:var(--gold); box-shadow:0 0 0 5px rgba(201,162,39,.12); }

        .login-panel { position:relative; display:flex; align-items:center; justify-content:center; padding:48px clamp(32px,5vw,76px); background:radial-gradient(circle at 100% 0%,rgba(201,162,39,.13),transparent 25rem),var(--paper); }
        .login-panel::before { content:''; position:absolute; inset:0; pointer-events:none; opacity:.38; background-image:linear-gradient(rgba(201,162,39,.055) 1px,transparent 1px),linear-gradient(90deg,rgba(201,162,39,.055) 1px,transparent 1px); background-size:38px 38px; mask-image:linear-gradient(to left,#000,transparent 78%); }
        .login-card { position:relative; width:100%; max-width:430px; animation:cardEnter .8s cubic-bezier(.22,.8,.24,1) both; }
        .brand { display:flex; align-items:center; gap:14px; margin-bottom:45px; animation:fadeUp .6s .12s both; }
        .brand img { width:58px; height:58px; object-fit:contain; border:1px solid var(--border); border-radius:18px; background:var(--white); box-shadow:0 12px 30px rgba(28,28,28,.08); }
        .brand strong { display:block; font-family:'Manrope',sans-serif; font-size:22px; letter-spacing:-.8px; }
        .brand span { display:block; margin-top:3px; color:var(--muted); font-size:10px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; }
        .login-heading { margin-bottom:30px; animation:fadeUp .6s .22s both; }
        .eyebrow { display:block; margin-bottom:10px; color:var(--gold-dark); font-size:10px; font-weight:800; letter-spacing:1.8px; text-transform:uppercase; }
        .login-heading h1 { margin:0; font-family:'Manrope',sans-serif; font-size:clamp(30px,4vw,40px); line-height:1.08; letter-spacing:-1.7px; }
        .login-heading p { margin:10px 0 0; color:var(--muted); font-size:14px; line-height:1.6; }
        .error { display:flex; align-items:flex-start; gap:10px; margin-bottom:22px; padding:13px 14px; border:1px solid #f1c4c4; border-radius:13px; background:#fff1f1; color:#9e2020; font-size:13px; line-height:1.45; animation:errorShake .42s both; }
        .error svg { flex:0 0 auto; margin-top:1px; }
        .login-form { animation:fadeUp .65s .34s both; }
        .form-group { margin-bottom:19px; }
        label { display:block; margin-bottom:8px; color:#3b3935; font-size:12px; font-weight:700; }
        .input-shell { position:relative; }
        .input-icon { position:absolute; left:14px; top:50%; width:18px; height:18px; transform:translateY(-50%); fill:none; stroke:#aaa49a; stroke-width:1.8; pointer-events:none; transition:.2s ease; }
        input { width:100%; height:52px; padding:0 46px; border:1px solid var(--border); border-radius:13px; outline:none; background:rgba(255,255,255,.88); color:var(--ink); font-size:14px; transition:border-color .2s,box-shadow .2s,background .2s; }
        input::placeholder { color:#aaa49a; }
        input:focus { border-color:var(--gold); background:var(--white); box-shadow:0 0 0 4px rgba(201,162,39,.12),0 8px 22px rgba(28,28,28,.04); }
        input:focus + .input-icon, .input-shell:focus-within .input-icon { stroke:var(--gold-dark); }
        .password-toggle { position:absolute; right:8px; top:50%; width:36px; height:36px; transform:translateY(-50%); display:grid; place-items:center; border:0; border-radius:9px; background:transparent; color:#918b81; cursor:pointer; }
        .password-toggle:hover, .password-toggle:focus-visible { background:rgba(201,162,39,.1); color:var(--gold-dark); outline:none; }
        .password-toggle svg { width:18px; height:18px; fill:none; stroke:currentColor; stroke-width:1.8; }

        .btn-login { position:relative; width:100%; min-height:56px; margin-top:8px; display:flex; align-items:center; justify-content:center; gap:13px; overflow:hidden; border:0; border-radius:13px; background:var(--ink); color:var(--white); font-weight:800; font-size:14px; cursor:pointer; box-shadow:0 14px 28px rgba(28,28,28,.18); transition:transform .2s,box-shadow .2s,background .2s; }
        .btn-login::after { content:''; position:absolute; top:-30%; left:-55%; width:36%; height:160%; transform:skewX(-22deg); background:linear-gradient(90deg,transparent,rgba(255,255,255,.2),transparent); transition:left .65s ease; }
        .btn-login:hover { transform:translateY(-2px); background:#282828; box-shadow:0 18px 34px rgba(28,28,28,.22); }
        .btn-login:hover::after { left:125%; }
        .btn-login:disabled { cursor:wait; transform:none; }
        .barber-loader { position:relative; width:20px; height:38px; flex:0 0 20px; filter:drop-shadow(0 2px 3px rgba(0,0,0,.18)); }
        .pole-stripes { position:absolute; z-index:1; top:8px; bottom:8px; left:5px; right:5px; overflow:hidden; border:0; border-radius:2px; background:var(--white); transition:background .3s ease; }
        .pole-cap { position:absolute; z-index:3; left:2px; width:16px; height:5px; border:0; border-radius:999px; background:var(--white); }
        .pole-cap.top { top:5px; }
        .pole-cap.bottom { bottom:5px; }
        .pole-knob { position:absolute; z-index:2; left:7px; width:6px; height:6px; border:0; border-radius:50%; background:var(--white); }
        .pole-knob::after { content:''; position:absolute; left:2px; top:5px; width:2px; height:3px; border-radius:2px; background:var(--white); }
        .pole-knob.top { top:0; }
        .pole-knob.bottom { bottom:0; transform:rotate(180deg); }
        .btn-login.loading .pole-stripes { background:repeating-linear-gradient(-45deg,#fff 0 7px,var(--gold) 7px 14px,var(--gold-dark) 14px 21px,#fff 21px 28px); background-size:40px 40px; animation:poleSpin .72s linear infinite; }
        .btn-login.loading .button-text { animation:softPulse 1.1s ease-in-out infinite; }
        .login-info { display:flex; justify-content:center; align-items:center; gap:8px; margin-top:22px; color:var(--muted); font-size:11px; animation:fadeUp .6s .48s both; }
        .login-info svg { width:14px; height:14px; fill:none; stroke:var(--gold-dark); stroke-width:1.8; }

        @keyframes ambientZoom { from { transform:scale(1.03); } to { transform:scale(1.09) translate3d(-.5%,.5%,0); } }
        @keyframes fadeDown { from { opacity:0; transform:translateY(-12px); } to { opacity:1; transform:none; } }
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:none; } }
        @keyframes cardEnter { from { opacity:0; transform:translateX(24px); } to { opacity:1; transform:none; } }
        @keyframes errorShake { 0%,100% { transform:translateX(0); } 25% { transform:translateX(-6px); } 50% { transform:translateX(5px); } 75% { transform:translateX(-3px); } }
        @keyframes poleSpin { to { background-position:0 40px; } }
        @keyframes softPulse { 50% { opacity:.62; } }

        @media (max-width:900px) {
            .login-page { grid-template-columns:1fr; position:relative; }
            .login-visual { position:absolute; inset:0; min-height:100%; }
            .login-visual::after { background:linear-gradient(rgba(15,15,15,.7),rgba(15,15,15,.82)); }
            .visual-content { display:none; }
            .login-panel { min-height:100vh; min-height:100dvh; padding:32px 20px; background:rgba(250,248,242,.73); backdrop-filter:blur(10px); }
            .login-panel::before { display:none; }
            .login-card { max-width:460px; padding:34px; border:1px solid rgba(255,255,255,.8); border-radius:24px; background:rgba(250,248,242,.94); box-shadow:0 24px 70px rgba(0,0,0,.22); }
            .brand { margin-bottom:34px; }
        }
        @media (max-width:700px) {
            body { background:#171717; }
            .login-page { display:block; overflow:visible; background:var(--paper); }
            .login-visual { position:relative; height:220px; min-height:220px; }
            .login-visual::before { inset:0; background-position:center 34%; animation:none; }
            .login-visual::after { background:linear-gradient(0deg,rgba(15,15,15,.86),rgba(15,15,15,.3)); }
            .visual-content { display:flex; justify-content:flex-end; padding:24px 24px 48px; }
            .visual-mark, .visual-copy span, .visual-copy p, .visual-foot { display:none; }
            .visual-copy { padding:0; }
            .visual-copy h2 { display:block; max-width:310px; color:var(--white); font-size:31px; line-height:1.04; letter-spacing:-1.5px; animation:fadeUp .65s .1s both; }
            .login-panel { z-index:2; min-height:calc(100vh - 186px); min-height:calc(100dvh - 186px); margin-top:-34px; padding:34px 18px 30px; align-items:flex-start; border-radius:30px 30px 0 0; background:var(--paper); backdrop-filter:none; }
            .login-card { max-width:480px; padding:18px 10px; border:0; border-radius:0; background:transparent; box-shadow:none; }
            .brand { margin-bottom:30px; }
            .brand img { width:50px; height:50px; border-radius:15px; }
            .brand strong { font-size:20px; }
            .login-heading h1 { font-size:32px; }
        }
        @media (max-width:520px) {
            .login-visual { height:190px; min-height:190px; }
            .visual-content { padding:20px 20px 42px; }
            .visual-copy h2 { max-width:270px; font-size:27px; }
            .login-panel { min-height:calc(100vh - 158px); min-height:calc(100dvh - 158px); margin-top:-32px; padding:28px 14px 24px; border-radius:26px 26px 0 0; }
            .login-card { padding:16px 8px; }
            .brand { margin-bottom:26px; }
            .login-heading { margin-bottom:23px; }
            .login-heading h1 { font-size:29px; }
            .login-heading p { font-size:13px; }
            input { height:50px; }
            .btn-login { min-height:54px; }
        }
        @media (prefers-reduced-motion:reduce) {
            *, *::before, *::after { scroll-behavior:auto !important; animation-duration:.01ms !important; animation-iteration-count:1 !important; transition-duration:.01ms !important; }
        }
    </style>
</head>
<body>
    <main class="login-page">
        <section class="login-visual" aria-label="Experiencia BarberCore">
            <div class="visual-grain"></div>
            <div class="visual-content">
                <div class="visual-mark">BarberCore Studio</div>
                <div class="visual-copy">
                    <span>Precisión en cada detalle</span>
                    <h2>Tu barbería, bajo control.</h2>
                    <p>Gestiona agenda, clientes, inventario y crecimiento desde un espacio diseñado para trabajar con claridad.</p>
                </div>
                <div class="visual-foot"><i></i><span>Administración inteligente para barberías modernas</span></div>
            </div>
        </section>

        <section class="login-panel">
            <div class="login-card">
                <div class="brand">
                    <img src="{{ asset('images/branding/icon_192_Barbercore.png') }}" alt="Logotipo de BarberCore">
                    <div><strong>BarberCore</strong><span>Panel administrativo</span></div>
                </div>

                <header class="login-heading">
                    <span class="eyebrow">Acceso seguro</span>
                    <h1>Bienvenido de nuevo</h1>
                    <p>Ingresa tus credenciales para continuar con tu jornada.</p>
                </header>

                @if ($errors->any())
                    <div class="error" role="alert">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v6M12 17h.01"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="login-form" id="loginForm">
                    @csrf
                    <div class="form-group">
                        <label for="correo">Correo electrónico</label>
                        <div class="input-shell">
                            <input type="email" name="correo" id="correo" value="{{ old('correo') }}" placeholder="nombre@barbercore.com" autocomplete="email" autofocus required>
                            <svg class="input-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-shell">
                            <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña" autocomplete="current-password" required>
                            <svg class="input-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                            <button type="button" class="password-toggle" id="passwordToggle" aria-label="Mostrar contraseña" aria-pressed="false">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="loginButton">
                        <span class="barber-loader" aria-hidden="true">
                            <i class="pole-knob top"></i><i class="pole-cap top"></i>
                            <span class="pole-stripes"></span>
                            <i class="pole-cap bottom"></i><i class="pole-knob bottom"></i>
                        </span>
                        <span class="button-text">Iniciar sesión</span>
                    </button>
                </form>

                <div class="login-info">
                    <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-5"/></svg>
                    <span>Acceso protegido · BarberCore</span>
                </div>
            </div>
        </section>
    </main>

    <script>
        (function () {
            const form = document.getElementById('loginForm');
            const button = document.getElementById('loginButton');
            const buttonText = button?.querySelector('.button-text');
            const password = document.getElementById('password');
            const toggle = document.getElementById('passwordToggle');
            let submitting = false;

            toggle?.addEventListener('click', function () {
                const show = password.type === 'password';
                password.type = show ? 'text' : 'password';
                toggle.setAttribute('aria-pressed', String(show));
                toggle.setAttribute('aria-label', show ? 'Ocultar contraseña' : 'Mostrar contraseña');
                password.focus();
            });

            form?.addEventListener('submit', function (event) {
                if (!form.checkValidity() || !button || submitting) return;
                event.preventDefault();
                submitting = true;
                button.classList.add('loading');
                button.disabled = true;
                button.setAttribute('aria-busy', 'true');
                if (buttonText) buttonText.textContent = 'Verificando acceso';

                window.setTimeout(function () {
                    form.submit();
                }, 1400);
            });
        })();
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
        }
    </script>
</body>
</html>
