<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | BarberCore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --dorado: #C9A227;
            --fondo: #FAF8F2;
            --blanco: #FFFFFF;
            --texto: #1C1C1C;
            --gris: #6B6B6B;
            --borde: #E5E0D6;
            --rojo: #C62828;
        }

        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--fondo);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--texto);
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: var(--blanco);
            padding: 35px;
            border-radius: 22px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            border: 1px solid var(--borde);
        }

        .brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 12px;
            border-radius: 50%;
            background: var(--dorado);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
        }

        .brand h1 {
            margin: 0;
            font-size: 28px;
            color: var(--texto);
        }

        .brand p {
            margin-top: 6px;
            color: var(--gris);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 7px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid var(--borde);
            border-radius: 12px;
            outline: none;
            font-size: 15px;
            color: var(--texto);
            background: #fff;
        }

        input:focus {
            border-color: var(--dorado);
            box-shadow: 0 0 0 3px rgba(201,162,39,0.15);
        }

        .btn-login {
            width: 100%;
            border: none;
            background: var(--dorado);
            color: white;
            padding: 14px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            margin-top: 8px;
        }

        .btn-login:hover {
            opacity: 0.92;
        }

        .error {
            background: #FDECEC;
            color: var(--rojo);
            border: 1px solid #F4C7C7;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .info {
            margin-top: 18px;
            text-align: center;
            font-size: 13px;
            color: var(--gris);
        }

        * { font-family: 'DM Sans', Arial, sans-serif; }
        body { padding: 24px; background: radial-gradient(circle at 15% 15%, rgba(201,162,39,.18), transparent 28rem), var(--fondo); }
        body::before { content: ''; position: fixed; inset: 0; pointer-events: none; background-image: linear-gradient(rgba(201,162,39,.035) 1px, transparent 1px), linear-gradient(90deg, rgba(201,162,39,.035) 1px, transparent 1px); background-size: 32px 32px; }
        .login-container { position: relative; max-width: 440px; padding: 42px; border-radius: 26px; box-shadow: 0 24px 70px rgba(28,28,28,.10); }
        .login-container::before { content: ''; position: absolute; top: 0; left: 42px; right: 42px; height: 4px; border-radius: 0 0 5px 5px; background: var(--dorado); }
        .brand h1 { font-family: 'Manrope', sans-serif; letter-spacing: -1px; }
        .brand-icon { border-radius: 20px; box-shadow: 0 12px 24px rgba(201,162,39,.25); }
        input { padding: 14px 15px; border-radius: 10px; transition: .18s ease; }
        .btn-login { padding: 15px; border-radius: 10px; box-shadow: 0 10px 22px rgba(201,162,39,.25); transition: transform .18s ease; }
        .btn-login:hover { opacity: 1; transform: translateY(-2px); }
        @media (max-width: 520px) { .login-container { padding: 34px 24px; } }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="brand">
            <div class="brand-icon">✂</div>
            <h1>BarberCore</h1>
            <p>Panel administrativo para barberías</p>
        </div>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <input 
                    type="email" 
                    name="correo" 
                    id="correo" 
                    value="{{ old('correo') }}" 
                    placeholder="admin@barbercore.com"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    placeholder="Ingresa tu contraseña"
                    required
                >
            </div>

            <button type="submit" class="btn-login">
                Iniciar sesión
            </button>
        </form>

        <div class="info">
            Acceso administrativo BarberCore
        </div>
    </div>

</body>
</html>
