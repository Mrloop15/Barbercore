<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberCore PWA - Login</title>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#C9A227">

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: #FAF8F2;
            font-family: Arial, sans-serif;
            color: #1C1C1C;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 90%;
            max-width: 420px;
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        }

        .logo {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            background: #C9A227;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 26px;
            margin: 0 auto 18px;
        }

        h1 {
            text-align: center;
            margin: 0;
            font-size: 26px;
        }

        p {
            text-align: center;
            color: #6B6B6B;
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
            font-size: 14px;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 13px 14px;
            border: 1px solid #E5E0D6;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 15px;
        }

        button {
            width: 100%;
            border: none;
            background: #C9A227;
            color: #FFFFFF;
            padding: 14px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 15px;
            cursor: pointer;
        }

        .message {
            margin-top: 16px;
            text-align: center;
            font-size: 14px;
            color: #C62828;
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">BC</div>

        <h1>BarberCore</h1>
        <p>Acceso PWA mediante API REST</p>

        <form id="pwaLoginForm">
            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" required placeholder="correo@barbercore.test">

            <label for="password">Contraseña</label>
            <input type="password" id="password" required placeholder="Contraseña">

            <button type="submit">Iniciar sesión</button>

            <div id="loginMessage" class="message"></div>
        </form>
    </div>

    <script src="/js/pwa-login.js"></script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
</html>