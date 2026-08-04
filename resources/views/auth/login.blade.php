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
    <link rel="stylesheet" href="{{ asset('css/barbercore-login.css') }}">
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
