<!DOCTYPE html>
<html lang="es">
<head>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#C9A227">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="BarberCore">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/branding/barbercore-192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/branding/barbercore-192.png') }}">

    <style>
        .pwa-install-btn {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 9999;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            border-radius: 999px;
            background: #C9A227;
            color: #FFFFFF;
            padding: 12px 18px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.18);
        }

        .pwa-install-btn:hover {
            background: #b8921f;
        }

        .pwa-status-bar {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10000;
            padding: 12px 20px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
        }

        .pwa-status-bar.online {
            background: #2E7D32;
            color: #FFFFFF;
        }

        .pwa-status-bar.offline {
            background: #C62828;
            color: #FFFFFF;
        }
    </style>

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

        body.sidebar-open {
            overflow: hidden;
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.38);
            z-index: 90;
        }

        .mobile-overlay.show {
            display: block;
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
            z-index: 100;
            overflow-y: auto;
            transition: transform 0.28s ease;
        }

        .sidebar-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .sidebar-close {
            display: none;
            border: none;
            background: transparent;
            color: var(--texto);
            font-size: 28px;
            line-height: 1;
            cursor: pointer;
            padding: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 35px;
            min-width: 0;
        }

        .brand-logo {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            object-fit: cover;
            border: 1px solid var(--borde);
            background: var(--blanco);
            flex-shrink: 0;
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
            flex-shrink: 0;
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
            gap: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.04);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .menu-toggle {
            display: none;
            width: 42px;
            height: 42px;
            border: 1px solid var(--borde);
            border-radius: 12px;
            background: var(--blanco);
            cursor: pointer;
            padding: 0;
            flex-shrink: 0;
        }

        .menu-toggle span,
        .menu-toggle span::before,
        .menu-toggle span::after {
            display: block;
            width: 18px;
            height: 2px;
            background: var(--texto);
            border-radius: 999px;
            position: relative;
            content: "";
            margin: 0 auto;
        }

        .menu-toggle span::before {
            position: absolute;
            top: -6px;
            left: 0;
        }

        .menu-toggle span::after {
            position: absolute;
            top: 6px;
            left: 0;
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

        .stock-ok {
            color: var(--verde);
            font-weight: 800;
        }

        .stock-low {
            color: var(--rojo);
            font-weight: 800;
        }

        .product-profit {
            color: var(--dorado);
            font-weight: 800;
        }

        .reward-points {
            color: var(--dorado);
            font-weight: 900;
        }

        .reward-type {
            text-transform: capitalize;
            font-weight: 700;
        }

        .points-box {
            background: rgba(201,162,39,0.14);
            border: 1px solid rgba(201,162,39,0.35);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 18px;
        }

        .points-box strong {
            color: var(--dorado);
            font-size: 24px;
        }

        .chart-list {
            display: grid;
            gap: 14px;
        }

        .chart-item {
            display: grid;
            grid-template-columns: 180px 1fr 90px;
            gap: 12px;
            align-items: center;
        }

        .chart-label {
            font-weight: 700;
            font-size: 14px;
        }

        .chart-bar-bg {
            background: #F1EBDD;
            border-radius: 999px;
            overflow: hidden;
            height: 14px;
        }

        .chart-bar {
            height: 14px;
            background: var(--dorado);
            border-radius: 999px;
        }

        .chart-value {
            text-align: right;
            font-weight: 800;
            color: var(--dorado);
        }

        .stats-section {
            margin-top: 22px;
        }

        .stats-two-columns {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .month-filter {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .config-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .logo-preview {
            width: 110px;
            height: 110px;
            border-radius: 20px;
            object-fit: cover;
            border: 1px solid var(--borde);
            background: var(--fondo);
        }

        .logo-placeholder {
            width: 110px;
            height: 110px;
            border-radius: 20px;
            background: rgba(201,162,39,0.16);
            color: var(--dorado);
            border: 1px solid var(--borde);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            font-weight: 900;
        }

        .config-section-title {
            margin-top: 0;
            margin-bottom: 6px;
        }

        .config-section-description {
            color: var(--gris);
            margin-top: 0;
            margin-bottom: 18px;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .sidebar {
                position: fixed;
                width: 260px;
                max-width: 85%;
                transform: translateX(-100%);
                box-shadow: 0 8px 30px rgba(0,0,0,0.20);
            }

            .sidebar.open {
                transform: translateX(0);
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
                display: flex;
                gap: 12px;
                align-items: flex-start;
            }

            .topbar-info {
                text-align: left;
                margin-top: 8px;
            }

            .sidebar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            .mobile-menu-btn {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                border-radius: 12px;
                border: 1px solid var(--borde);
                background: var(--blanco);
                cursor: pointer;
                font-size: 20px;
                flex-shrink: 0;
            }

            .mobile-close-btn {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                width: 38px;
                height: 38px;
                border: none;
                background: transparent;
                color: var(--texto);
                cursor: pointer;
                font-size: 22px;
            }

            .mobile-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.35);
                z-index: 999;
            }

            .mobile-overlay.show {
                display: block;
            }
        }

        @media (max-width: 900px) {
            .stats-two-columns {
                grid-template-columns: 1fr;
            }

            .chart-item {
                grid-template-columns: 1fr;
                gap: 6px;
            }

            .chart-value {
                text-align: left;
            }
        }

        @media (max-width: 900px) {
            .config-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .main {
                padding: 16px;
            }

            .page-actions,
            .agenda-header,
            .search-form,
            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .search-form {
                max-width: 100%;
            }

            table {
                min-width: 720px;
            }
        }

        .mobile-menu-btn,
        .mobile-close-btn {
            display: none;
        }
    </style>
</head>
<body>

<div id="pwaStatusBar" class="pwa-status-bar" style="display: none;"></div>
<div id="mobileOverlay" class="mobile-overlay"></div>

<div class="app">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="brand">
                <img src="{{ asset('images/branding/barbercore-192.png') }}"
                     alt="BarberCore"
                     class="brand-logo"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="brand-icon" style="display:none;">✂</div>

                <div class="brand-text">
                    <strong>BarberCore</strong>
                    <span>Panel administrativo</span>
                </div>
            </div>

            <button type="button" class="mobile-close-btn" id="closeSidebar">✕</button>
        </div>

        <nav class="menu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.index') ? 'active' : '' }}">Clientes</a>
            <a href="{{ route('clientes.inactivos') }}" class="{{ request()->routeIs('clientes.inactivos') ? 'active' : '' }}">Clientes inactivos</a>
            <a href="{{ route('citas.index') }}" class="{{ request()->routeIs('citas.*') ? 'active' : '' }}">Citas</a>
            <a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.*') ? 'active' : '' }}">Agenda</a>
            <a href="{{ route('servicios.index') }}" class="{{ request()->routeIs('servicios.*') ? 'active' : '' }}">Servicios</a>
            <a href="{{ route('productos.index') }}" class="{{ request()->routeIs('productos.*') ? 'active' : '' }}">Productos</a>
            <a href="{{ route('ventas-productos.index') }}" class="{{ request()->routeIs('ventas-productos.*') ? 'active' : '' }}">Ventas</a>
            <a href="{{ route('recompensas.index') }}" class="{{ request()->routeIs('recompensas.*') ? 'active' : '' }}">Recompensas</a>
            <a href="{{ route('estadisticas.index') }}" class="{{ request()->routeIs('estadisticas.*') ? 'active' : '' }}">Estadísticas</a>
            <a href="{{ route('configuracion.index') }}" class="{{ request()->routeIs('configuracion.*') ? 'active' : '' }}">Configuración</a>

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
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <button type="button" class="mobile-menu-btn" id="openSidebar">☰</button>
                <div>
                    <h2>@yield('page-title', 'Panel BarberCore')</h2>
                </div>
            </div>

            <div class="topbar-info">
                <div>{{ auth()->user()->barberia->nombre ?? 'BarberCore Studio' }}</div>
                <div>{{ auth()->user()->nombre ?? auth()->user()->name ?? 'Usuario' }} · {{ now()->format('d/m/Y') }}</div>
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

<script>
    (function () {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');

        function openSidebar() {
            if (window.innerWidth <= 900) {
                sidebar.classList.add('open');
                overlay.classList.add('show');
                document.body.classList.add('sidebar-open');
            }
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.classList.remove('sidebar-open');
        }

        if (openBtn) openBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);

        window.addEventListener('resize', function () {
            if (window.innerWidth > 900) {
                closeSidebar();
            }
        });
    })();
</script>

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/sw.js')
                .then(function () {
                    console.log('Service Worker registrado correctamente.');
                })
                .catch(function (error) {
                    console.log('Error al registrar el Service Worker:', error);
                });
        });
    }
</script>

<button id="installPwaBtn" class="pwa-install-btn" style="display: none;">
    Instalar BarberCore
</button>

<script src="/js/pwa-install.js"></script>
<script src="/js/pwa-status.js"></script>

</body>
</html>