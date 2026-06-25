<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'BarberCore')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --dorado: #C9A227;
            --fondo: #FAF8F2;
            --blanco: #FFFFFF;
            --texto: #1C1C1C;
            --gris: #6B6B6B;
            --borde: #E5E0D6;
            --rojo: #C62828;
            --verde: #2E7D32;
        }

        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: var(--fondo);
            color: var(--texto);
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: var(--blanco);
            border-right: 1px solid var(--borde);
            padding: 24px 18px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 35px;
        }

        .brand-icon {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            background: var(--dorado);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .brand-text strong {
            display: block;
            font-size: 20px;
        }

        .brand-text span {
            color: var(--gris);
            font-size: 12px;
        }

        .menu a,
        .logout-button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 12px 14px;
            margin-bottom: 8px;
            border-radius: 12px;
            color: var(--texto);
            text-decoration: none;
            font-size: 15px;
            border: none;
            background: transparent;
            cursor: pointer;
        }

        .menu a:hover,
        .menu a.active {
            background: rgba(201,162,39,0.16);
            color: var(--dorado);
            font-weight: 700;
        }

        .logout-button {
            color: var(--rojo);
            margin-top: 20px;
        }

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            padding: 24px;
        }

        .topbar {
            background: var(--blanco);
            border: 1px solid var(--borde);
            border-radius: 18px;
            padding: 18px 22px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.04);
        }

        .topbar h2 {
            margin: 0;
            font-size: 22px;
        }

        .topbar-info {
            text-align: right;
            color: var(--gris);
            font-size: 14px;
        }

        .content-card {
            background: var(--blanco);
            border: 1px solid var(--borde);
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.04);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--blanco);
            border: 1px solid var(--borde);
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.04);
        }

        .stat-card span {
            color: var(--gris);
            font-size: 14px;
        }

        .stat-card h3 {
            margin: 10px 0 0;
            font-size: 27px;
            color: var(--dorado);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            color: var(--gris);
            font-size: 14px;
            padding: 12px;
            border-bottom: 1px solid var(--borde);
        }

        td {
            padding: 12px;
            border-bottom: 1px solid var(--borde);
            font-size: 14px;
        }

        .badge {
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-pendiente {
            background: rgba(201,162,39,0.18);
            color: var(--dorado);
        }

        .badge-completada {
            background: rgba(46,125,50,0.14);
            color: var(--verde);
        }

        .badge-cancelada {
            background: rgba(198,40,40,0.14);
            color: var(--rojo);
        }

        @media (max-width: 900px) {
            .sidebar {
                position: static;
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--borde);
            }

            .app {
                display: block;
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .topbar {
                display: block;
            }

            .topbar-info {
                text-align: left;
                margin-top: 8px;
            }
        }

        @media (max-width: 600px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .main {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

<div class="app">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">✂</div>
            <div class="brand-text">
                <strong>BarberCore</strong>
                <span>Panel administrativo</span>
            </div>
        </div>

        <nav class="menu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="#">Clientes</a>
            <a href="#">Clientes inactivos</a>
            <a href="#">Citas</a>
            <a href="#">Agenda</a>
            <a href="#">Servicios</a>
            <a href="#">Productos</a>
            <a href="#">Recompensas</a>
            <a href="#">Estadísticas</a>
            <a href="#">Configuración</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-button">
                    Cerrar sesión
                </button>
            </form>
        </nav>
    </aside>

    <main class="main">
        <div class="topbar">
            <div>
                <h2>@yield('page-title', 'Panel BarberCore')</h2>
            </div>

            <div class="topbar-info">
                <div>{{ auth()->user()->barberia->nombre ?? 'BarberCore Studio' }}</div>
                <div>{{ auth()->user()->nombre ?? 'Usuario' }} · {{ now()->format('d/m/Y') }}</div>
            </div>
        </div>

        @yield('content')
    </main>
</div>

</body>
</html>