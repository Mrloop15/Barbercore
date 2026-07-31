<!DOCTYPE html>
<html lang="es">
<head>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#C9A227">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="BarberCore">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/branding/icon_192_Barbercore.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/branding/icon_192_Barbercore.png') }}">

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

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
            min-width: 0;
        }

        .search-form select {
            width: auto;
            min-width: 145px;
            flex: 0 0 auto;
        }

        .search-form .btn {
            flex: 0 0 auto;
            white-space: nowrap;
        }

        .product-search-form {
            max-width: 650px;
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

        .whatsapp-btn:hover {
            background: #1fbd5a;
            box-shadow: 0 8px 18px rgba(37,211,102,.28);
        }

        .btn-icon:disabled {
            opacity: .42;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
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

            .search-form select,
            .search-form .btn {
                width: 100%;
            }

            table {
                min-width: 720px;
            }
        }

        .mobile-menu-btn,
        .mobile-close-btn {
            display: none;
        }

        /* Nueva dirección visual conservando la paleta original. */
        * { font-family: 'DM Sans', Arial, sans-serif; }
        html, body, .sidebar, .main, .content-card {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        html::-webkit-scrollbar,
        body::-webkit-scrollbar,
        .sidebar::-webkit-scrollbar,
        .main::-webkit-scrollbar,
        .content-card::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }
        body { background: radial-gradient(circle at 90% 0%, rgba(201,162,39,.10), transparent 28rem), var(--fondo); }
        h1, h2, h3, .brand-text strong { font-family: 'Manrope', 'DM Sans', sans-serif; }
        .sidebar { width: 278px; padding: 28px 20px 22px; border-right: 0; box-shadow: 12px 0 40px rgba(28,28,28,.06); }
        .brand { margin-bottom: 26px; padding: 0 8px; }
        .brand-logo, .brand-icon { width: 48px; height: 48px; border-radius: 16px; }
        .brand-text strong { font-size: 19px; letter-spacing: -.5px; }
        .brand-text span { font-size: 11px; text-transform: uppercase; letter-spacing: 1.1px; }
        .menu-label { display: block; margin: 22px 12px 8px; color: var(--gris); font-size: 10px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; }
        .menu a, .logout-button { display: flex; align-items: center; gap: 12px; margin-bottom: 4px; padding: 11px 13px; border-radius: 11px; font-weight: 600; transition: .2s ease; }
        .menu a::before { content: ''; width: 7px; height: 7px; border: 2px solid var(--borde); border-radius: 50%; flex: 0 0 auto; transition: .2s ease; }
        .menu a:hover { transform: translateX(3px); }
        .menu a.active { background: var(--texto); color: var(--blanco); box-shadow: 0 9px 20px rgba(28,28,28,.14); }
        .menu a.active::before { border-color: var(--dorado); background: var(--dorado); }
        .logout-button { border-top: 1px solid var(--borde); border-radius: 0; padding-top: 18px; }
        .main { margin-left: 278px; width: calc(100% - 278px); padding: 30px 34px 42px; }
        .topbar { background: transparent; border: 0; border-radius: 0; padding: 0 0 24px; margin-bottom: 8px; box-shadow: none; }
        .topbar h2 { font-size: clamp(24px, 3vw, 32px); letter-spacing: -1px; }
        .page-kicker { color: var(--dorado); font-size: 11px; font-weight: 800; letter-spacing: 1.6px; text-transform: uppercase; margin-bottom: 4px; }
        .topbar-info { background: var(--blanco); border: 1px solid var(--borde); border-radius: 14px; padding: 10px 14px; line-height: 1.5; box-shadow: 0 7px 18px rgba(28,28,28,.04); }
        .topbar-info div:first-child { color: var(--texto); font-weight: 700; }
        .content-card, .stat-card { border: 1px solid rgba(229,224,214,.9); border-radius: 20px; box-shadow: 0 10px 30px rgba(28,28,28,.055); transition: transform .2s ease, box-shadow .2s ease; }
        .content-card { padding: 26px; overflow: hidden; }
        .stat-card { position: relative; padding: 22px; overflow: hidden; }
        .stat-card::after { content: ''; position: absolute; right: -12px; bottom: -24px; width: 70px; height: 70px; border-radius: 50%; background: rgba(201,162,39,.10); }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 15px 34px rgba(28,28,28,.08); }
        .stat-card h3 { font-size: 30px; letter-spacing: -1px; }
        .stats-grid { gap: 16px; }
        th { padding: 14px 12px; font-size: 11px; text-transform: uppercase; letter-spacing: .8px; background: var(--fondo); }
        td { padding: 15px 12px; }
        tbody tr { transition: background .18s ease; }
        tbody tr:hover { background: rgba(201,162,39,.045); }
        .btn { padding: 11px 16px; border-radius: 10px; transition: transform .18s ease, box-shadow .18s ease; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 7px 16px rgba(28,28,28,.10); }
        .btn-primary { box-shadow: 0 7px 16px rgba(201,162,39,.20); }
        input, textarea, select { border-radius: 10px; padding: 13px 14px; transition: border-color .18s, box-shadow .18s; }
        input[type="file"] {
            padding: 8px; min-height: 58px; border: 1px dashed var(--borde); background: var(--fondo);
            color: var(--gris); cursor: pointer;
        }
        input[type="file"]::file-selector-button {
            border: 0; border-radius: 9px; background: var(--texto); color: var(--blanco);
            padding: 10px 14px; margin-right: 12px; font-weight: 700; cursor: pointer;
            transition: background .18s ease;
        }
        input[type="file"]:hover { border-color: var(--dorado); background: rgba(201,162,39,.055); }
        input[type="file"]:hover::file-selector-button { background: var(--dorado); }
        .image-selection-preview {
            display: none; grid-template-columns: 76px minmax(0, 1fr); align-items: center; gap: 14px;
            margin-top: 10px; padding: 10px; background: var(--blanco); border: 1px solid var(--borde); border-radius: 12px;
        }
        .image-selection-preview.show { display: grid; }
        .image-selection-preview img { width: 76px; height: 76px; border-radius: 10px; object-fit: cover; background: var(--fondo); }
        .image-selection-preview strong, .image-selection-preview span { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .image-selection-preview strong { font-size: 13px; margin-bottom: 4px; }
        .image-selection-preview span { color: var(--gris); font-size: 12px; }
        .detail-item, .agenda-summary-card { border-radius: 14px; }
        .badge { display: inline-flex; align-items: center; padding: 6px 10px; }
        .ui-icon { display: block; pointer-events: none; }
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
        .btn-icon { width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; }
        .btn-icon:hover { transform: translateY(-2px); }
        .actions { gap: 8px; align-items: center; }
        .actions form { margin: 0; display: inline-flex; }
        .page-actions { padding: 4px 0 22px; border-bottom: 1px solid var(--borde); margin-bottom: 8px; }
        .content-card > table { margin: 0 -26px -26px; width: calc(100% + 52px); }
        .content-card > table th:first-child, .content-card > table td:first-child { padding-left: 26px; }
        .content-card > table th:last-child, .content-card > table td:last-child { padding-right: 26px; }
        .agenda-summary-card { position: relative; overflow: hidden; background: var(--blanco); box-shadow: 0 8px 24px rgba(28,28,28,.045); }
        .agenda-summary-card::after { content: ''; position: absolute; width: 54px; height: 54px; border: 10px solid rgba(201,162,39,.09); border-radius: 50%; right: -17px; top: -17px; }
        .form-grid { gap: 20px 24px; }
        .form-group { margin-bottom: 18px; }
        label { font-size: 12px; letter-spacing: .25px; }
        .form-actions { border-top: 1px solid var(--borde); padding-top: 20px; margin-top: 8px; }
        .content-card h3 { letter-spacing: -.4px; }
        .dashboard-intro { display: flex; align-items: end; justify-content: space-between; gap: 20px; margin-bottom: 20px; }
        .dashboard-intro span { color: var(--dorado); text-transform: uppercase; letter-spacing: 1.2px; font-size: 11px; font-weight: 800; }
        .dashboard-intro h3 { margin: 5px 0 0; font-size: 20px; }
        .dashboard-stats .stat-card:nth-child(4n + 1) { border-top: 3px solid var(--dorado); }
        .dashboard-stats .stat-card span { display: block; min-height: 34px; }
        .empty-state { text-align: center; padding: 36px; color: var(--gris); }
        @media (max-width: 900px) { .sidebar { width: 278px; z-index: 1001; } .mobile-overlay { z-index: 1000; } .main { margin-left: 0; width: 100%; padding: 22px; } }
        @media (max-width: 600px) { .main { padding: 18px 14px 30px; } .topbar { align-items: center; } .topbar-info { display: none; } .content-card { padding: 18px; overflow-x: auto; } .content-card > table { margin-left: -18px; margin-right: -18px; width: calc(100% + 36px); } .content-card > table th:first-child, .content-card > table td:first-child { padding-left: 18px; } .content-card > table th:last-child, .content-card > table td:last-child { padding-right: 18px; } .stat-card { padding: 18px; } .dashboard-intro { align-items: stretch; flex-direction: column; } }
    </style>
</head>
<body>

<div id="pwaStatusBar" class="pwa-status-bar" style="display: none;"></div>
<div id="mobileOverlay" class="mobile-overlay"></div>

<div class="app">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="brand">
                <img src="{{ asset('images/branding/icon_192_Barbercore.png') }}"
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
            <span class="menu-label">Principal</span>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.index') ? 'active' : '' }}">Clientes</a>
            <a href="{{ route('clientes.inactivos') }}" class="{{ request()->routeIs('clientes.inactivos') ? 'active' : '' }}">Clientes inactivos</a>
            @if(auth()->check() && auth()->user()->rol === 'admin')
                <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">Usuarios</a>
            @endif
            <span class="menu-label">Operación</span>
            <a href="{{ route('citas.index') }}" class="{{ request()->routeIs('citas.*') ? 'active' : '' }}">Citas</a>
            <a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.*') ? 'active' : '' }}">Agenda</a>
            <a href="{{ route('servicios.index') }}" class="{{ request()->routeIs('servicios.*') ? 'active' : '' }}">Servicios</a>
            <a href="{{ route('productos.index') }}" class="{{ request()->routeIs('productos.*') ? 'active' : '' }}">Productos</a>
            <a href="{{ route('ventas-productos.index') }}" class="{{ request()->routeIs('ventas-productos.*') ? 'active' : '' }}">Ventas</a>
            <a href="{{ route('recompensas.index') }}" class="{{ request()->routeIs('recompensas.*') ? 'active' : '' }}">Recompensas</a>
            <span class="menu-label">Análisis</span>
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
                    <div class="page-kicker">Panel administrativo</div>
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
    (function () {
        const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"], input[type="file"][accept*="jpg"], input[type="file"][accept*="png"]');

        imageInputs.forEach(function (input) {
            const preview = document.createElement('div');
            preview.className = 'image-selection-preview';
            preview.setAttribute('aria-live', 'polite');
            preview.innerHTML = '<img alt="Vista previa de la imagen seleccionada"><div><strong></strong><span></span></div>';
            input.insertAdjacentElement('afterend', preview);

            input.addEventListener('change', function () {
                const file = input.files && input.files[0];
                if (!file) {
                    preview.classList.remove('show');
                    return;
                }

                const image = preview.querySelector('img');
                const name = preview.querySelector('strong');
                const meta = preview.querySelector('span');
                image.src = URL.createObjectURL(file);
                image.onload = function () { URL.revokeObjectURL(image.src); };
                name.textContent = file.name;
                meta.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB · imagen lista para guardar';
                preview.classList.add('show');
            });
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
