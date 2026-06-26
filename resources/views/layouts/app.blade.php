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

        .page-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            width: 100%;
            max-width: 480px;
        }

        .search-form input {
            flex: 1;
        }

        .btn {
            display: inline-block;
            border: none;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: var(--dorado);
            color: white;
        }

        .btn-secondary {
            background: #F1EBDD;
            color: var(--texto);
        }

        .btn-danger {
            background: var(--rojo);
            color: white;
        }

        .btn-success {
            background: var(--verde);
            color: white;
        }

        .btn-sm {
            padding: 7px 10px;
            font-size: 12px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            font-weight: 700;
            margin-bottom: 7px;
            font-size: 14px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--borde);
            border-radius: 12px;
            outline: none;
            font-size: 14px;
            background: white;
            color: var(--texto);
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: var(--dorado);
            box-shadow: 0 0 0 3px rgba(201,162,39,0.15);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 18px;
        }

        .alert {
            padding: 13px 15px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(46,125,50,0.12);
            color: var(--verde);
            border: 1px solid rgba(46,125,50,0.25);
        }

        .alert-error {
            background: rgba(198,40,40,0.12);
            color: var(--rojo);
            border: 1px solid rgba(198,40,40,0.25);
        }

        .actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .client-photo {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            object-fit: cover;
            border: 1px solid var(--borde);
            background: var(--fondo);
        }

        .empty-photo {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(201,162,39,0.16);
            color: var(--dorado);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .detail-item {
            background: var(--fondo);
            border: 1px solid var(--borde);
            border-radius: 14px;
            padding: 14px;
        }

        .detail-item span {
            display: block;
            color: var(--gris);
            font-size: 13px;
            margin-bottom: 5px;
        }

        .detail-item strong {
            font-size: 15px;
        }

        .pagination {
            margin-top: 18px;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .filter-tab {
            padding: 10px 14px;
            border-radius: 999px;
            text-decoration: none;
            color: var(--texto);
            background: #F1EBDD;
            font-weight: 700;
            font-size: 14px;
        }

        .filter-tab.active {
            background: var(--dorado);
            color: white;
        }

        .inactive-days {
            font-weight: 800;
            color: var(--rojo);
        }

        .whatsapp-btn {
            background: #25D366;
            color: white;
        }

        .warning-box {
            background: rgba(201,162,39,0.14);
            border: 1px solid rgba(201,162,39,0.35);
            color: var(--texto);
            padding: 14px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .agenda-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .agenda-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .agenda-tab {
            padding: 10px 14px;
            border-radius: 999px;
            background: #F1EBDD;
            color: var(--texto);
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
        }

        .agenda-tab.active {
            background: var(--dorado);
            color: white;
        }

        .agenda-filter {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .agenda-day-group {
            margin-bottom: 22px;
        }

        .agenda-day-title {
            background: var(--fondo);
            border: 1px solid var(--borde);
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 12px;
            font-weight: 800;
            color: var(--texto);
        }

        .agenda-time {
            font-weight: 800;
            color: var(--dorado);
        }

        .agenda-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }

        .agenda-summary-card {
            background: var(--fondo);
            border: 1px solid var(--borde);
            border-radius: 16px;
            padding: 16px;
        }

        .agenda-summary-card span {
            color: var(--gris);
            font-size: 13px;
        }

        .agenda-summary-card strong {
            display: block;
            margin-top: 6px;
            font-size: 22px;
            color: var(--dorado);
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
            <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}">Clientes</a>
            <a href="{{ route('clientes.inactivos') }}" class="{{ request()->routeIs('clientes.inactivos') ? 'active' : '' }}">Clientes inactivos</a>
            <a href="{{ route('citas.index') }}" class="{{ request()->routeIs('citas.*') ? 'active' : '' }}">Citas</a>
            <a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.*') ? 'active' : '' }}">Agenda</a>
            <a href="{{ route('servicios.index') }}" class="{{ request()->routeIs('servicios.*') ? 'active' : '' }}">Servicios</a>
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
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        
        @yield('content')
    </main>
</div>

</body>
</html>