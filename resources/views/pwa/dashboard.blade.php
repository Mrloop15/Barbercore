<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberCore PWA - Dashboard</title>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#C9A227">

    <style>
        body {
            margin: 0;
            background: #FAF8F2;
            font-family: Arial, sans-serif;
            color: #1C1C1C;
        }

        .topbar {
            background: #FFFFFF;
            border-bottom: 1px solid #E5E0D6;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 20px;
        }

        .logo {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #C9A227;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .logout-btn {
            border: none;
            background: #C62828;
            color: #FFFFFF;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
        }

        .container {
            padding: 22px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .welcome {
            margin-bottom: 22px;
        }

        .welcome h1 {
            margin: 0 0 6px;
            font-size: 26px;
        }

        .welcome p {
            margin: 0;
            color: #6B6B6B;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 16px;
        }

        .card {
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
        }

        .card span {
            color: #6B6B6B;
            font-size: 14px;
        }

        .card strong {
            display: block;
            margin-top: 8px;
            font-size: 30px;
            color: #C9A227;
        }

        .section {
            margin-top: 24px;
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
        }

        .section h2 {
            margin-top: 0;
            font-size: 20px;
        }

        .list {
            display: grid;
            gap: 10px;
        }

        .item {
            padding: 12px;
            border: 1px solid #E5E0D6;
            border-radius: 12px;
            background: #FAF8F2;
        }

        .item strong {
            display: block;
        }

        .item small {
            color: #6B6B6B;
        }

        .message {
            display: none;
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 12px;
            background: #C62828;
            color: #FFFFFF;
            font-weight: 700;
        }

        @media (max-width: 700px) {
            .container {
                padding: 16px;
            }

            .welcome h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="brand">
            <div class="logo">BC</div>
            <span>BarberCore PWA</span>
        </div>

        <button id="logoutBtn" class="logout-btn">Salir</button>
    </header>

    <main class="container">
        <div id="message" class="message"></div>

        <section class="welcome">
            <h1 id="welcomeTitle">Dashboard</h1>
            <p>Resumen general consumido desde la API REST.</p>
        </section>

        <section class="section">
            <h2>Módulos rápidos</h2>

            <div class="list">
                <a class="item" href="/pwa/clientes" style="text-decoration: none; color: inherit;">
                    <strong>Clientes</strong>
                    <small>Consultar clientes registrados desde la API.</small>
                </a>

                <a class="item" href="/pwa/usuarios" id="moduloUsuarios" style="text-decoration: none; color: inherit; display: none;">
                    <strong>Usuarios</strong>
                    <small>Crear, consultar y activar o desactivar usuarios desde la PWA.</small>
                </a>

                <a class="item" href="/pwa/agenda" style="text-decoration: none; color: inherit;">
                    <strong>Agenda</strong>
                    <small>Consultar citas por día, semana o mes.</small>
                </a>

                <a class="item" href="/pwa/citas" style="text-decoration: none; color: inherit;">
                    <strong>Citas</strong>
                    <small>Crear, completar o cancelar citas desde la PWA.</small>
                </a>

                <a class="item" href="/pwa/productos" style="text-decoration: none; color: inherit;">
                    <strong>Productos</strong>
                    <small>Consultar inventario, bajo stock y actualizar existencias.</small>
                </a>

                <a class="item" href="/pwa/ventas" style="text-decoration: none; color: inherit;">
                    <strong>Ventas</strong>
                    <small>Registrar ventas de productos, descontar stock y sumar puntos.</small>
                </a>

                <a class="item" href="/pwa/recompensas" style="text-decoration: none; color: inherit;">
                    <strong>Recompensas</strong>
                    <small>Crear recompensas y canjear puntos de clientes.</small>
                </a>

                <a class="item" href="/pwa/estadisticas" style="text-decoration: none; color: inherit;">
                    <strong>Estadísticas</strong>
                    <small>Consultar ingresos, clientes, productos y servicios.</small>
                </a>
            </div>
        </section>

        <section class="grid">
            <div class="card">
                <span>Clientes registrados</span>
                <strong id="totalClientes">0</strong>
            </div>

            <div class="card">
                <span>Citas de hoy</span>
                <strong id="citasHoy">0</strong>
            </div>

            <div class="card">
                <span>Citas pendientes</span>
                <strong id="citasPendientes">0</strong>
            </div>

            <div class="card">
                <span>Citas completadas</span>
                <strong id="citasCompletadas">0</strong>
            </div>

            <div class="card">
                <span>Ingresos del día</span>
                <strong id="ingresosDia">$0</strong>
            </div>

            <div class="card">
                <span>Ingresos del mes</span>
                <strong id="ingresosMes">$0</strong>
            </div>
        </section>

        <section class="section">
            <h2>Próximas citas</h2>
            <div id="proximasCitas" class="list">
                <div class="item">Cargando próximas citas...</div>
            </div>
        </section>
    </main>

    <script src="/js/pwa-dashboard.js"></script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
</html>