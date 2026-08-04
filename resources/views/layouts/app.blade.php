<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#C9A227">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="BarberCore">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/branding/icon_192_Barbercore.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/branding/icon_192_Barbercore.png') }}">

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

        html {
            width: 100%;
            min-height: 100%;
            overflow-x: hidden;
        }

        body {
            margin: 0;
            min-width: 0;
            min-height: 100%;
            overflow-x: hidden;
            background: var(--fondo);
            color: var(--texto);
            -webkit-text-size-adjust: 100%;
        }

        body.sidebar-open {
            overflow: hidden;
        }

        .app {
            display: flex;
            width: 100%;
            min-width: 0;
            min-height: 100vh;
        }

        .mobile-overlay {
            display: block;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.38);
            z-index: 1090;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.28s ease, visibility 0.28s ease;
        }

        .mobile-overlay.show {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            height: 100dvh;
            background: var(--blanco);
            border-right: 1px solid var(--borde);
            padding: 24px 18px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1100;
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
            transition: transform 0.28s ease, visibility 0.28s ease;
        }

        .sidebar::-webkit-scrollbar {
            width: 0;
            height: 0;
            display: none;
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

        .brand-text {
            min-width: 0;
        }

        .brand-text strong {
            display: block;
            font-size: 20px;
            overflow-wrap: break-word;
        }

        .brand-text > span {
            color: var(--gris);
            font-size: 12px;
        }

        .connection-status {
            width: max-content;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 7px;
            padding: 4px 8px;
            border: 1px solid rgba(46,125,50,.2);
            border-radius: 999px;
            background: rgba(46,125,50,.09);
            color: #236b27;
            font-size: 9px;
            font-weight: 800;
            line-height: 1;
            letter-spacing: .3px;
            text-transform: none;
            transition: color .2s ease, border-color .2s ease, background .2s ease;
        }

        .connection-status-dot {
            width: 7px;
            height: 7px;
            flex: 0 0 7px;
            border-radius: 50%;
            background: #2E7D32;
            box-shadow: 0 0 0 3px rgba(46,125,50,.13);
        }

        .connection-status.checking { border-color: var(--borde); background: rgba(132,132,132,.08); color: var(--gris); }
        .connection-status.checking .connection-status-dot { background: var(--gris); box-shadow: 0 0 0 3px rgba(132,132,132,.12); animation: connectionPulse 1.2s ease-in-out infinite; }
        .connection-status.offline { border-color: rgba(198,40,40,.2); background: rgba(198,40,40,.09); color: var(--rojo); }
        .connection-status.offline .connection-status-dot { background: var(--rojo); box-shadow: 0 0 0 3px rgba(198,40,40,.12); }
        @keyframes connectionPulse { 50% { opacity: .42; } }

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
            overflow-wrap: break-word;
            touch-action: manipulation;
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
            min-width: 0;
            min-height: 100vh;
            overflow-x: hidden;
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
            min-width: 0;
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
            line-height: 1.25;
            overflow-wrap: break-word;
        }

        .topbar-info {
            min-width: 0;
            text-align: right;
            color: var(--gris);
            font-size: 14px;
            line-height: 1.45;
            overflow-wrap: break-word;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            min-width: 0;
        }

        .pwa-install-btn {
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 40px;
            padding: 9px 13px;
            border: 1px solid rgba(201,162,39,.32);
            border-radius: 12px;
            background: var(--texto);
            color: var(--blanco);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
            cursor: pointer;
            box-shadow: 0 7px 17px rgba(28,28,28,.12);
            transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
        }

        .pwa-install-btn .ui-icon { color: var(--dorado); }
        .pwa-install-btn:hover { transform: translateY(-1px); border-color: var(--dorado); box-shadow: 0 10px 20px rgba(28,28,28,.17); }

        .content-card {
            min-width: 0;
            max-width: 100%;
            background: var(--blanco);
            border: 1px solid var(--borde);
            border-radius: 18px;
            padding: 22px;
            overflow-wrap: break-word;
            word-break: normal;
            box-shadow: 0 8px 20px rgba(0,0,0,0.04);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .stat-card {
            min-width: 0;
            background: var(--blanco);
            border: 1px solid var(--borde);
            border-radius: 18px;
            padding: 20px;
            overflow-wrap: break-word;
            word-break: normal;
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
            max-width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        .table-responsive {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            overscroll-behavior-x: contain;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .table-responsive::-webkit-scrollbar {
            width: 0;
            height: 0;
            display: none;
        }

        th {
            text-align: left;
            color: var(--gris);
            font-size: 14px;
            padding: 12px;
            border-bottom: 1px solid var(--borde);
            overflow-wrap: normal;
            word-break: normal;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid var(--borde);
            font-size: 14px;
            overflow-wrap: normal;
            word-break: normal;
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
            min-width: 0;
        }

        .page-actions > * {
            min-width: 0;
        }

        .search-form {
            display: flex;
            gap: 10px;
            width: 100%;
            max-width: 480px;
            min-width: 0;
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

        .module-tools {
            display: flex;
            align-items: stretch;
            gap: 14px;
            margin-bottom: 20px;
        }

        .filter-panel {
            flex: 1 1 auto;
            min-width: 0;
            padding: 15px 16px;
            border: 1px solid var(--borde);
            border-radius: 16px;
            background: linear-gradient(120deg, var(--blanco), rgba(201,162,39,.055));
        }

        .filter-panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 12px;
        }

        .filter-panel-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .filter-panel-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: rgba(201,162,39,.14);
            color: var(--dorado);
        }

        .filter-panel-title {
            display: block;
            font-size: 13px;
            line-height: 1.2;
        }

        .filter-panel-subtitle {
            display: block;
            margin-top: 3px;
            color: var(--gris);
            font-size: 10px;
            line-height: 1.3;
        }

        .filter-result-count {
            flex: 0 0 auto;
            padding: 6px 10px;
            border: 1px solid rgba(201,162,39,.24);
            border-radius: 999px;
            background: var(--blanco);
            color: var(--gris);
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .filter-result-count strong { color: var(--texto); }

        .filter-panel-meta {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 9px;
            flex: 0 0 auto;
        }

        .filter-form {
            display: flex;
            align-items: end;
            gap: 10px;
            width: 100%;
            min-width: 0;
        }

        .filter-field {
            display: grid;
            gap: 6px;
            flex: 0 1 180px;
            min-width: 145px;
            margin: 0;
        }

        .filter-field-grow { flex: 1 1 290px; }
        .filter-field-date { flex-basis: 180px; }

        .filter-label {
            color: var(--gris);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        .filter-field input,
        .filter-field select {
            width: 100%;
            min-width: 0;
            min-height: 44px;
            background: var(--blanco);
        }

        .filter-search-control { position: relative; display: block; width: 100%; }
        .filter-search-control > .ui-icon {
            position: absolute;
            z-index: 1;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dorado);
            pointer-events: none;
        }
        .filter-search-control input { padding-left: 41px; }

        .filter-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 0 0 auto;
        }

        .filter-actions .btn { min-height: 44px; gap: 7px; }
        .filter-clear { color: var(--gris); background: var(--blanco); border: 1px solid var(--borde); }
        .filter-clear:hover { color: var(--rojo); border-color: rgba(198,40,40,.3); box-shadow: none; }

        .module-primary-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 0 0 auto;
        }

        .module-primary-actions .btn { min-height: 38px; padding: 8px 13px; border-radius: 11px; gap: 8px; }

        .module-action-btn {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(201,162,39,.34);
            background: linear-gradient(135deg, var(--texto), #35332e);
            color: var(--blanco);
            box-shadow: 0 7px 16px rgba(28,28,28,.13);
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .module-action-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(110deg, transparent 20%, rgba(255,255,255,.1) 48%, transparent 72%);
            transform: translateX(-120%);
            transition: transform .45s ease;
            pointer-events: none;
        }

        .module-action-btn:hover {
            border-color: var(--dorado);
            box-shadow: 0 10px 20px rgba(28,28,28,.18);
            transform: translateY(-1px);
        }

        .module-action-btn:hover::after { transform: translateX(120%); }
        .module-action-btn .ui-icon { position: relative; z-index: 1; color: #e5bd3d; }
        .module-action-btn > span { position: relative; z-index: 1; }

        .module-action-btn-secondary {
            border: 1px solid rgba(201,162,39,.38);
            background: var(--blanco);
            color: var(--texto);
            box-shadow: 0 5px 13px rgba(28,28,28,.055);
            transition: color .18s ease, border-color .18s ease, background .18s ease, transform .18s ease;
        }

        .module-action-btn-secondary:hover {
            border-color: var(--dorado);
            background: rgba(201,162,39,.09);
            color: var(--dorado);
            transform: translateY(-1px);
        }

        .module-action-btn-secondary .ui-icon { color: var(--dorado); }

        @media (max-width: 1180px) {
            .module-tools { flex-direction: column; }
            .filter-form { flex-wrap: wrap; }
            .module-primary-actions { justify-content: flex-end; }
        }

        .filter-panel .filter-tabs {
            margin: 0;
            gap: 6px;
        }

        .filter-panel .filter-tab {
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 9px 13px;
            transition: background .18s ease, border-color .18s ease, color .18s ease, transform .18s ease;
        }

        .filter-panel .filter-tab:not(.active):hover {
            border-color: rgba(201,162,39,.3);
            color: var(--dorado);
            transform: translateY(-1px);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.2;
            white-space: nowrap;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            flex-shrink: 0;
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
            min-width: 0;
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

        .table-image-placeholder {
            position: relative;
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            overflow: hidden;
            border: 1px dashed var(--borde);
            border-radius: 14px;
            background: linear-gradient(145deg, var(--blanco), var(--fondo));
            color: var(--dorado);
        }

        .table-image-placeholder::after {
            content: '';
            position: absolute;
            right: -8px;
            bottom: -8px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(201,162,39,.1);
        }

        .table-image-placeholder .ui-icon {
            position: relative;
            z-index: 1;
            width: 21px;
            height: 21px;
            stroke-width: 1.7;
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

        .bc-pagination { display:flex;align-items:center;justify-content:space-between;gap:16px;padding:18px 0 0;margin-top:4px;border-top:1px solid var(--borde);font-size:12px; }
        .bc-pagination-summary { color:var(--gris); }.bc-pagination-summary strong{color:var(--texto)}
        .bc-pagination-controls { display:flex;align-items:center;gap:6px; }
        .bc-page-button { width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--borde);border-radius:9px;background:var(--blanco);color:var(--texto);text-decoration:none;font-weight:700;transition:.18s ease; }
        .bc-page-button svg { width:16px!important;height:16px!important;display:block; }
        a.bc-page-button:hover { border-color:var(--dorado);color:var(--dorado);transform:translateY(-1px); }
        .bc-page-button.active { background:var(--dorado);border-color:var(--dorado);color:var(--blanco);box-shadow:0 5px 12px rgba(201,162,39,.22); }
        .bc-page-button.disabled { opacity:.35;cursor:not-allowed; }.bc-page-dots{padding:0 3px;color:var(--gris)}
        .bc-pagination-simple{justify-content:flex-end}.bc-pagination-simple .bc-page-button{width:auto;padding:0 13px}
        @media(max-width:600px){.bc-pagination{align-items:stretch;flex-direction:column}.bc-pagination-controls{justify-content:center;flex-wrap:wrap}.bc-pagination-summary{text-align:center}}

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

        .filter-tab,
        .agenda-tab,
        .badge {
            white-space: nowrap;
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

        .dashboard-charts {
            margin-bottom: 22px;
        }

        .report-hero { padding: 28px; margin-bottom: 16px; border-radius: 22px; background: linear-gradient(115deg,var(--blanco) 55%,rgba(201,162,39,.14)); border:1px solid var(--borde); box-shadow:0 10px 30px rgba(28,28,28,.05); }
        .report-hero h3 { margin:5px 0;font-size:23px; }.report-hero p{margin:0;color:var(--gris)}
        .report-builder{margin-bottom:16px}.report-filter{display:grid;grid-template-columns:repeat(3,minmax(150px,1fr)) auto;gap:14px;align-items:end}.report-filter .form-group{margin:0}.report-filter-actions{display:flex;align-items:center;gap:8px;padding-bottom:1px}.report-filter-actions .btn{gap:7px}.report-summary{grid-template-columns:repeat(5,1fr);margin-bottom:16px}.report-preview{overflow-x:auto}
        .dashboard-charts{display:grid;grid-template-columns:minmax(300px,.8fr) minmax(420px,1.2fr);gap:18px}.status-chart-card h3,.income-chart-card h3{margin:4px 0 18px}.donut-layout{display:flex;align-items:center;justify-content:center;gap:30px}.status-donut{width:160px;height:160px;border-radius:50%;display:grid;place-items:center;background:conic-gradient(var(--verde) 0 var(--completed),var(--dorado) var(--completed) var(--pending),var(--rojo) var(--pending) 100%);position:relative}.status-donut:after{content:'';position:absolute;inset:18px;background:var(--blanco);border-radius:50%}.status-donut>div{position:relative;z-index:1;text-align:center}.status-donut strong,.status-donut span{display:block}.status-donut strong{font-size:27px}.status-donut span{color:var(--gris);font-size:10px}.chart-legend-list{display:grid;gap:12px}.chart-legend-list span{display:grid;grid-template-columns:9px 1fr auto;align-items:center;gap:7px;color:var(--gris);font-size:11px}.chart-legend-list i{width:8px;height:8px;border-radius:50%}.chart-legend-list .completed{background:var(--verde)}.chart-legend-list .pending{background:var(--dorado)}.chart-legend-list .cancelled{background:var(--rojo)}.chart-legend-list strong{color:var(--texto)}.vertical-chart{height:190px;display:flex;align-items:stretch;gap:10px;border-bottom:1px solid var(--borde);padding:8px 5px 0}.vertical-bar-item{flex:1;display:flex;flex-direction:column;align-items:center;gap:7px;min-width:0}.vertical-bar-track{flex:1;width:min(38px,75%);display:flex;align-items:flex-end;background:var(--fondo);border-radius:8px 8px 0 0;overflow:hidden}.vertical-bar{width:100%;background:linear-gradient(to top,var(--dorado),#e4c75e);border-radius:8px 8px 0 0;transition:height .3s}.vertical-bar-item>span{font-size:9px;color:var(--gris)}
        @media(max-width:900px){.report-filter{grid-template-columns:repeat(2,1fr)}.report-filter-actions{grid-column:1/-1}.report-summary{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:900px){.dashboard-charts{grid-template-columns:1fr}}
        @media(max-width:560px){.report-filter{grid-template-columns:1fr}.report-filter-actions{flex-direction:column}.report-filter-actions .btn{width:100%}}

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
                width: 260px;
                max-width: 85vw;
                padding-top: calc(24px + env(safe-area-inset-top));
                padding-bottom: calc(24px + env(safe-area-inset-bottom));
                transform: translate3d(-100%, 0, 0);
                visibility: hidden;
                pointer-events: none;
                box-shadow: 0 8px 30px rgba(0,0,0,0.20);
            }

            .sidebar.open {
                transform: translate3d(0, 0, 0);
                visibility: visible;
                pointer-events: auto;
            }

            .app {
                display: block;
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .topbar {
                gap: 12px;
                align-items: center;
                flex-wrap: wrap;
            }

            .topbar-left {
                flex: 1 1 240px;
            }

            .topbar-info {
                flex: 1 1 220px;
                text-align: left;
                margin-top: 0;
            }

            .page-actions {
                flex-wrap: wrap;
            }

            .search-form {
                flex: 1 1 320px;
                flex-wrap: wrap;
            }

            /*
             * Solo la tabla se desplaza horizontalmente.
             * Los buscadores, botones y demás contenido permanecen fijos
             * dentro de la card y no se recortan.
             */
            .table-responsive {
                overflow-x: auto;
                overflow-y: hidden;
                overscroll-behavior-x: contain;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                -ms-overflow-style: none;
            }

            .table-responsive::-webkit-scrollbar {
                width: 0;
                height: 0;
                display: none;
            }

            .table-responsive table {
                width: max-content;
                min-width: 100%;
                max-width: none;
            }

            .table-responsive th {
                white-space: nowrap;
                overflow-wrap: normal;
                word-break: normal;
            }

            .table-responsive td {
                min-width: 110px;
                max-width: 280px;
                white-space: normal;
                overflow-wrap: break-word;
                word-break: normal;
                vertical-align: middle;
            }

            .table-responsive .actions {
                min-width: max-content;
                flex-wrap: nowrap;
            }

            .sidebar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                margin-bottom: 20px;
            }

            .sidebar-header .brand {
                margin-bottom: 0;
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
                color: var(--texto);
                cursor: pointer;
                font-size: 20px;
                line-height: 1;
                flex-shrink: 0;
                touch-action: manipulation;
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
                line-height: 1;
                flex-shrink: 0;
                touch-action: manipulation;
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
            .stats-grid,
            .form-grid,
            .detail-grid,
            .agenda-summary {
                grid-template-columns: 1fr;
            }

            .main {
                padding-top: 16px;
                padding-right: max(16px, env(safe-area-inset-right));
                padding-bottom: calc(16px + env(safe-area-inset-bottom));
                padding-left: max(16px, env(safe-area-inset-left));
            }

            .topbar {
                padding: 16px;
                border-radius: 16px;
            }

            .topbar-left,
            .topbar-info {
                flex-basis: 100%;
                width: 100%;
            }

            .topbar h2 {
                font-size: 20px;
            }

            .content-card,
            .stat-card {
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
                flex: 0 0 auto;
                width: 100%;
                max-width: 100%;
            }

            .search-form select,
            .search-form .btn {
                width: 100%;
            }

            .search-form input {
                flex: 0 0 auto;
                width: 100%;
                min-height: 44px;
                height: 44px;
            }

            .module-tools,
            .filter-form,
            .module-primary-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-field,
            .filter-field-grow,
            .filter-field-date {
                flex: 0 0 auto;
                width: 100%;
                min-width: 0;
            }

            .filter-actions { width: 100%; }
            .filter-actions .btn { flex: 1 1 auto; }
            .module-primary-actions .btn { width: 100%; }
            .filter-panel-head { align-items: flex-start; }
            .filter-panel-head { flex-direction: column; }
            .filter-panel-meta { width: 100%; justify-content: space-between; flex-wrap: wrap; }
            .filter-panel-meta .module-primary-actions { width: auto; }

            .page-actions > .btn,
            .page-actions > a.btn,
            .search-form .btn,
            .form-actions .btn {
                width: 100%;
            }

            th {
                white-space: nowrap;
                overflow-wrap: normal;
                word-break: normal;
            }

        }

        @media (prefers-reduced-motion: reduce) {
            .sidebar,
            .mobile-overlay {
                transition: none;
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
        .brand-text > span { font-size: 11px; text-transform: uppercase; letter-spacing: 1.1px; }
        .menu-label { display: block; margin: 22px 12px 8px; color: var(--gris); font-size: 10px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; }
        .menu a, .logout-button { display: flex; align-items: center; gap: 12px; margin-bottom: 4px; padding: 11px 13px; border-radius: 11px; font-weight: 600; transition: .2s ease; }
        .menu-icon { width: 20px; height: 20px; flex: 0 0 20px; display: inline-flex; align-items: center; justify-content: center; color: var(--gris); transition: color .2s ease; }
        .menu-icon svg { width: 19px; height: 19px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .menu-text { min-width: 0; white-space: nowrap; }
        .menu a:hover { transform: translateX(3px); }
        .menu a.active { background: var(--texto); color: var(--blanco); box-shadow: 0 9px 20px rgba(28,28,28,.14); }
        .menu a:hover .menu-icon, .menu a.active .menu-icon { color: var(--dorado); }
        .logout-button { border-top: 1px solid var(--borde); border-radius: 0; padding-top: 18px; }
        .main { margin-left: 278px; width: calc(100% - 278px); padding: 30px 34px 42px; }
        .sidebar, .main { transition: width .9s cubic-bezier(.22,.8,.24,1), margin-left .9s cubic-bezier(.22,.8,.24,1), padding .9s cubic-bezier(.22,.8,.24,1), transform .28s ease; }
        .sidebar-header { position: relative; }
        .sidebar-header .brand { padding-left: 0; padding-right: 44px; }
        .sidebar-collapse-btn { position: absolute; right: 0; top: 7px; margin: 0; width: 34px; height: 34px; border: 1px solid var(--borde); border-radius: 10px; background: var(--blanco); color: var(--texto); cursor: pointer; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; transition: opacity .16s ease, color .2s ease, border-color .2s ease, background .2s ease; }
        .sidebar-collapse-btn:hover { color: var(--dorado); border-color: var(--dorado); background: rgba(201,162,39,.08); }
        .sidebar-collapse-btn svg { width: 17px; height: 17px; fill: none; stroke: currentColor; stroke-width: 2; transition: transform .25s ease; }
        body.sidebar-collapsed .sidebar { width: 84px; padding-left: 14px; padding-right: 14px; }
        body.sidebar-collapsed .main { margin-left: 84px; width: calc(100% - 84px); }
        body.sidebar-collapsed .brand { padding: 0; margin-bottom: 0; justify-content: center; }
        body.sidebar-collapsed .brand-text, body.sidebar-collapsed .menu-text, body.sidebar-collapsed .menu-label { display: none; }
        body.sidebar-collapsed .sidebar-header { display: block; min-height: 92px; }
        body.sidebar-collapsed .sidebar-collapse-btn { top: 57px; left: 50%; right: auto; width: 30px; height: 30px; transform: translateX(-50%); }
        body.sidebar-collapsed .sidebar-collapse-btn svg { transform: rotate(180deg); }
        body.sidebar-collapsed .menu a, body.sidebar-collapsed .logout-button { justify-content: center; padding-left: 12px; padding-right: 12px; gap: 0; }
        body.sidebar-collapsed .menu a:hover { transform: translateY(-1px); }
        .menu-group-toggle { display: none; }
        .menu-group-items { display: contents; }
        body.sidebar-collapsed .sidebar { overflow: visible; }
        body.sidebar-collapsed .menu-group { position: relative; }
        body.sidebar-collapsed .menu-group-toggle { width: 100%; min-height: 44px; margin: 4px 0; padding: 0; border: 0; border-radius: 11px; background: transparent; color: var(--gris); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: .2s ease; }
        body.sidebar-collapsed .menu-group-toggle:hover, body.sidebar-collapsed .menu-group.open .menu-group-toggle, body.sidebar-collapsed .menu-group:focus-within .menu-group-toggle { color: var(--dorado); background: rgba(201,162,39,.13); }
        body.sidebar-collapsed .menu-group-toggle .menu-icon { color: inherit; }
        body.sidebar-collapsed .menu-group-items { display: none; position: absolute; left: calc(100% + 12px); top: 0; width: 224px; padding: 10px; border: 1px solid var(--borde); border-radius: 14px; background: var(--blanco); box-shadow: 0 14px 34px rgba(28,28,28,.14); z-index: 1200; }
        body.sidebar-collapsed .menu-group-items::before { content: ''; position: absolute; right: 100%; top: 0; width: 14px; height: 100%; }
        body.sidebar-collapsed .menu-group:hover .menu-group-items, body.sidebar-collapsed .menu-group.open .menu-group-items, body.sidebar-collapsed .menu-group:focus-within .menu-group-items { display: block; }
        body.sidebar-collapsed .menu-group-items::after { content: attr(data-label); display: block; padding: 4px 10px 9px; color: var(--gris); font-size: 10px; font-weight: 800; letter-spacing: 1.2px; text-transform: uppercase; order: -1; }
        body.sidebar-collapsed .menu-group-items { display: none; }
        body.sidebar-collapsed .menu-group:hover .menu-group-items, body.sidebar-collapsed .menu-group.open .menu-group-items, body.sidebar-collapsed .menu-group:focus-within .menu-group-items { display: flex; flex-direction: column; }
        body.sidebar-collapsed .menu-group-items a { justify-content: flex-start; gap: 11px; padding: 10px 12px; }
        body.sidebar-collapsed .menu-group-items .menu-text { display: block; }
        @keyframes sidebarItemReveal { from { opacity: 0; transform: translateX(-6px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes sidebarCharacterReveal { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes sidebarLogoReveal { from { opacity: .35; transform: scale(.82) rotate(-3deg); } to { opacity: 1; transform: scale(1) rotate(0); } }
        @keyframes sidebarCompactIconReveal { from { opacity: 0; transform: translateX(-5px) scale(.72); } to { opacity: 1; transform: translateX(0) scale(1); } }
        @keyframes sidebarLabelHide { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(-10px); } }
        @keyframes groupMenuReveal { from { opacity: 0; transform: translateX(-9px) scale(.97); } to { opacity: 1; transform: translateX(0) scale(1); } }
        body.sidebar-expanding .brand-logo,
        body.sidebar-expanding .brand-icon { animation: sidebarLogoReveal .9s cubic-bezier(.22,.8,.24,1) both; animation-delay: 80ms; }
        .brand-type-line { white-space: nowrap; }
        .brand-character { display: inline-block; }
        body.sidebar-expanding .brand-type-primary .brand-character { opacity: 0; animation: sidebarCharacterReveal .18s ease forwards; animation-delay: calc(220ms + var(--char-index, 0) * 90ms); }
        body.sidebar-expanding .brand-type-secondary .brand-character { opacity: 0; animation: sidebarCharacterReveal .16s ease forwards; animation-delay: calc(1200ms + var(--char-index, 0) * 55ms); }
        body.sidebar-expanding .menu-text,
        body.sidebar-expanding .menu-label { opacity: 0; animation: sidebarItemReveal .55s cubic-bezier(.22,.8,.24,1) forwards; animation-delay: calc(var(--item-index, 0) * 105ms + 280ms); }
        body.sidebar-collapsing .brand-text,
        body.sidebar-collapsing .menu-text,
        body.sidebar-collapsing .menu-label { animation: sidebarLabelHide .16s ease forwards; }
        body.sidebar-icons-revealing .brand-logo,
        body.sidebar-icons-revealing .brand-icon { animation: sidebarLogoReveal .55s cubic-bezier(.22,.8,.24,1) both; animation-delay: 60ms; }
        body.sidebar-icons-revealing .menu > a .menu-icon,
        body.sidebar-icons-revealing .menu-group-toggle .menu-icon,
        body.sidebar-icons-revealing .menu > form .menu-icon { opacity: 0; animation: sidebarCompactIconReveal .42s cubic-bezier(.22,.8,.24,1) forwards; animation-delay: calc(180ms + var(--collapsed-icon-index, 0) * 130ms); }
        body.sidebar-collapsing .sidebar-collapse-btn,
        body.sidebar-expanding .sidebar-collapse-btn { opacity: 0; pointer-events: none; }
        body.sidebar-collapsed .menu-group:hover .menu-group-items,
        body.sidebar-collapsed .menu-group.open .menu-group-items,
        body.sidebar-collapsed .menu-group:focus-within .menu-group-items { animation: groupMenuReveal .32s cubic-bezier(.22,.8,.24,1) both; }
        @media (prefers-reduced-motion: reduce) {
            body.sidebar-expanding .brand-logo, body.sidebar-expanding .brand-icon, body.sidebar-expanding .brand-character, body.sidebar-expanding .menu-text, body.sidebar-expanding .menu-label,
            body.sidebar-collapsing .brand-text, body.sidebar-collapsing .menu-text, body.sidebar-collapsing .menu-label, body.sidebar-icons-revealing .brand-logo, body.sidebar-icons-revealing .brand-icon, body.sidebar-icons-revealing .menu-icon,
            body.sidebar-collapsed .menu-group-items { animation: none !important; opacity: 1; }
        }
        .topbar { background: transparent; border: 0; border-radius: 0; padding: 0 0 24px; margin-bottom: 8px; box-shadow: none; }
        .topbar h2 { font-size: clamp(24px, 3vw, 32px); letter-spacing: -1px; }
        .page-kicker { color: var(--dorado); font-size: 11px; font-weight: 800; letter-spacing: 1.6px; text-transform: uppercase; margin-bottom: 4px; }
        .topbar-info { min-width: 310px; display: grid; grid-template-columns: 42px minmax(0,1fr); align-items: center; gap: 11px; padding: 10px 13px; border: 1px solid var(--borde); border-radius: 14px; background: var(--blanco); color: var(--texto); text-align: left; text-decoration: none; line-height: 1.4; box-shadow: 0 7px 18px rgba(28,28,28,.04); transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease; }
        .topbar-info:hover { border-color: rgba(201,162,39,.45); box-shadow: 0 10px 22px rgba(28,28,28,.075); transform: translateY(-1px); }
        .topbar-barber-avatar { width: 42px; height: 42px; display: grid; place-items: center; border-radius: 12px; background: linear-gradient(145deg,var(--texto),#3a3832); color: var(--dorado); font-size: 13px; font-weight: 900; letter-spacing: .5px; }
        .topbar-barber-content { min-width: 0; }
        .topbar-barber-heading { display: flex; align-items: center; gap: 7px; min-width: 0; }
        .topbar-barber-heading strong { overflow: hidden; color: var(--texto); font-size: 14px; text-overflow: ellipsis; white-space: nowrap; }
        .topbar-role-chip { flex: 0 0 auto; padding: 3px 7px; border-radius: 999px; background: rgba(201,162,39,.12); color: var(--dorado); font-size: 9px; font-weight: 900; letter-spacing: .5px; text-transform: uppercase; }
        .topbar-next-appointment { display: flex; align-items: center; gap: 5px; margin-top: 3px; color: var(--gris); font-size: 11px; }
        .topbar-next-appointment .ui-icon { flex: 0 0 auto; color: var(--dorado); }
        .topbar-next-appointment strong { color: var(--texto); font-size: 11px; }
        .topbar-appointment-detail { display: block; overflow: hidden; margin-top: 3px; color: var(--gris); font-size: 10px; text-overflow: ellipsis; white-space: nowrap; }
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
        .agenda-hero, .agenda-toolbar, .timeline-card, .agenda-details { background: var(--blanco); border: 1px solid var(--borde); box-shadow: 0 10px 30px rgba(28,28,28,.05); }
        .agenda-hero { display: flex; justify-content: space-between; align-items: center; gap: 24px; padding: 26px 28px; border-radius: 22px; margin-bottom: 16px; background: linear-gradient(115deg, var(--blanco) 58%, rgba(201,162,39,.12)); }
        .agenda-hero h3, .timeline-card-header h3, .agenda-details-heading h3 { margin: 4px 0 0; font-size: 20px; }
        .agenda-hero p { margin: 7px 0 0; color: var(--gris); }
        .agenda-eyebrow { color: var(--dorado); font-size: 10px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; }
        .agenda-toolbar { display: flex; justify-content: space-between; align-items: center; gap: 18px; padding: 12px 14px; border-radius: 16px; margin-bottom: 16px; }
        .agenda-view-selector { display: grid; gap: 6px; }
        .agenda-toolbar .agenda-tabs { margin: 0; }
        .agenda-toolbar .agenda-tab { padding: 9px 16px; border-radius: 9px; }
        .agenda-toolbar .agenda-tab.active { background: var(--texto); }
        .agenda-toolbar .agenda-filter { align-items: end; }
        .agenda-toolbar .agenda-filter input { width: 100%; min-width: 155px; padding: 10px 12px; }
        .agenda-summary-wide { grid-template-columns: repeat(6, minmax(0,1fr)); margin-bottom: 16px; }
        .agenda-summary-wide .agenda-summary-card { padding: 15px; }
        .agenda-summary-wide .agenda-summary-card strong { font-size: 20px; }
        .timeline-card { border-radius: 22px; overflow: hidden; margin-bottom: 18px; }
        .timeline-card-header, .agenda-details-heading { display: flex; justify-content: space-between; align-items: center; gap: 20px; padding: 22px 24px; border-bottom: 1px solid var(--borde); }
        .timeline-legend { display: flex; align-items: center; gap: 16px; color: var(--gris); font-size: 12px; font-weight: 700; }
        .timeline-legend span { display: flex; align-items: center; gap: 6px; }
        .timeline-legend i { width: 8px; height: 8px; border-radius: 50%; }
        .timeline-legend .pending { background: var(--dorado); }
        .timeline-legend .completed { background: var(--verde); }
        .timeline-legend .cancelled { background: var(--rojo); }
        .timeline-header-tools { display:flex;align-items:flex-end;gap:22px; }
        .timeline-jump { display:grid;gap:5px;min-width:145px; }
        .timeline-jump > span { color:var(--gris);font-size:9px;font-weight:800;letter-spacing:1px;text-transform:uppercase; }
        .timeline-jump select { width:100%;padding:8px 30px 8px 10px;border-radius:9px;font-size:11px;font-weight:700; }
        .timeline-scroll { overflow-x: auto; overflow-y:hidden; scrollbar-width:thin; scrollbar-color:rgba(201,162,39,.55) rgba(229,224,214,.35); outline: none; padding-bottom:7px; scroll-behavior:smooth; }
        .timeline-scroll::-webkit-scrollbar { display:block;height:7px; }
        .timeline-scroll::-webkit-scrollbar-track { background:rgba(229,224,214,.35);border-radius:999px;margin:0 12px; }
        .timeline-scroll::-webkit-scrollbar-thumb { background:rgba(201,162,39,.58);border-radius:999px; }
        .timeline-scroll::-webkit-scrollbar-thumb:hover { background:var(--dorado); }
        .gantt { display:grid;grid-template-columns:112px calc(var(--hour-count) * 140px);width:max-content;min-width:100%; }
        .gantt-corner { position:sticky;left:0;z-index:8;padding:17px 20px;color:var(--gris);font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:1px;border-right:1px solid var(--borde);border-bottom:1px solid var(--borde);background:var(--fondo);box-shadow:5px 0 10px rgba(28,28,28,.035); }
        .gantt-hours { position: relative; height: 48px; border-bottom: 1px solid var(--borde); background: var(--fondo); }
        .gantt-hours span { position: absolute; top: 16px; transform: translateX(-50%); color: var(--gris); font-size: 10px; font-weight: 700; }
        .gantt-hours span:first-child { transform: none; }
        .gantt-hours span:last-child { transform: translateX(-100%); }
        .gantt-day { position:sticky;left:0;z-index:7;min-height:calc((var(--lanes) * 58px) + 16px);display:flex;flex-direction:column;justify-content:center;padding:14px 18px;border-right:1px solid var(--borde);border-bottom:1px solid var(--borde);background:var(--blanco);box-shadow:5px 0 10px rgba(28,28,28,.035);transition:min-height .2s ease; }
        .gantt-day strong { text-transform: capitalize; font-size: 13px; }
        .gantt-day span { color: var(--gris); font-size: 11px; margin-top: 3px; }
        .gantt-track { position: relative; min-height: calc((var(--lanes) * 58px) + 16px); border-bottom: 1px solid var(--borde); background-color: var(--blanco); background-image: repeating-linear-gradient(to right, transparent 0, transparent calc((100% / var(--hour-count)) - 1px), var(--borde) calc((100% / var(--hour-count)) - 1px), var(--borde) calc(100% / var(--hour-count))); transition: min-height .2s ease; }
        .gantt-event { position: absolute; top: calc(8px + (var(--lane) * 58px)); height: 48px; min-width: 42px; padding: 8px 9px; border-top: 0; border-right: 0; border-bottom: 0; border-radius: 9px; color: var(--texto); text-decoration: none; text-align: left; cursor: pointer; overflow: hidden; box-shadow: 0 4px 11px rgba(28,28,28,.09); transition: transform .18s ease, z-index .18s; border-left: 4px solid currentColor; }
        .gantt-event:hover { transform: translateY(-2px) scale(1.01); z-index: 5; }
        .gantt-event > i { display: none; }
        .gantt-event strong { display: flex; flex-direction: column; min-width: 0; white-space: nowrap; overflow: hidden; font-size: 10px; line-height: 1.35; }
        .gantt-event strong time, .gantt-event strong span { display: block; overflow: hidden; text-overflow: ellipsis; }
        .gantt-event strong time { color: currentColor; font-size: 9px; }
        .gantt-event strong span { color: var(--texto); }
        .gantt-event.status-pendiente { color: var(--dorado); background: #f8f0d8; }
        .gantt-event.status-completada { color: var(--verde); background: #e4f0e5; }
        .gantt-event.status-cancelada { color: var(--rojo); background: #f5e3e3; opacity: .72; }
        .timeline-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 160px; padding: 30px; color: var(--gris); text-align: center; }
        .timeline-empty strong { color: var(--texto); font-size: 17px; margin-bottom: 5px; }
        .agenda-details { border-radius: 22px; overflow: hidden; }
        .agenda-details-heading > span { color: var(--gris); font-size: 12px; font-weight: 700; }
        .agenda-day-detail { border-bottom: 1px solid var(--borde); }
        .agenda-day-detail:last-child { border-bottom: 0; }
        .agenda-day-detail summary { list-style: none; display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 16px 24px; cursor: pointer; background: var(--blanco); }
        .agenda-day-detail summary::-webkit-details-marker { display: none; }
        .agenda-day-detail summary > span:first-child { display: flex; align-items: baseline; gap: 10px; color: var(--gris); font-size: 12px; text-transform: capitalize; }
        .agenda-day-detail summary strong { color: var(--texto); font-size: 14px; }
        .day-count { color: var(--dorado); background: rgba(201,162,39,.12); border-radius: 999px; padding: 5px 9px; font-size: 10px; font-weight: 800; }
        .agenda-day-detail[open] summary { background: var(--fondo); }
        .agenda-table-wrap { overflow-x: auto; scrollbar-width: none; }
        .agenda-table-wrap::-webkit-scrollbar { display: none; }
        .agenda-table-wrap table { min-width: 780px; }
        .no-action { color: var(--gris); font-size: 11px; }
        body.modal-open { overflow: hidden; }
        .appointment-modal { display: none; position: fixed; inset: 0; z-index: 12000; align-items: center; justify-content: center; padding: 20px; }
        .appointment-modal.open { display: flex; }
        .appointment-modal-backdrop { position: absolute; inset: 0; background: rgba(28,28,28,.55); backdrop-filter: blur(4px); animation: modal-fade .18s ease; }
        .appointment-modal-dialog { position: relative; width: min(600px, 100%); max-height: calc(100vh - 40px); overflow-y: auto; scrollbar-width: none; border: 1px solid var(--borde); border-radius: 24px; background: var(--blanco); box-shadow: 0 30px 90px rgba(0,0,0,.25); animation: modal-rise .2s ease-out; }
        .appointment-modal-dialog::-webkit-scrollbar { display: none; }
        @keyframes modal-fade { from { opacity: 0; } }
        @keyframes modal-rise { from { opacity: 0; transform: translateY(14px) scale(.98); } }
        .appointment-modal-header { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 22px 24px; border-bottom: 1px solid var(--borde); }
        .appointment-modal-header h3 { margin: 4px 0 0; font-size: 19px; }
        .appointment-modal-close { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--borde); border-radius: 11px; background: var(--fondo); color: var(--texto); cursor: pointer; }
        .appointment-modal-close:hover { border-color: var(--dorado); color: var(--dorado); }
        .appointment-modal-body { padding: 24px; }
        .modal-appointment-heading { display: grid; grid-template-columns: 58px 1fr auto; align-items: center; gap: 14px; padding: 16px; border-radius: 16px; background: var(--fondo); border: 1px solid var(--borde); }
        .modal-date-icon { width: 58px; height: 58px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 14px; background: var(--dorado); color: var(--blanco); }
        .modal-date-icon span { font-family: 'Manrope', sans-serif; font-size: 21px; font-weight: 800; line-height: 1; }
        .modal-date-icon small { margin-top: 4px; font-size: 9px; font-weight: 800; text-transform: uppercase; }
        .modal-appointment-heading > div:nth-child(2) strong, .modal-appointment-heading > div:nth-child(2) span { display: block; text-transform: capitalize; }
        .modal-appointment-heading > div:nth-child(2) strong { font-size: 13px; }
        .modal-appointment-heading > div:nth-child(2) span { color: var(--gris); font-size: 12px; margin-top: 4px; }
        .modal-detail-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 12px; margin-top: 16px; }
        .modal-detail { padding: 14px; border: 1px solid var(--borde); border-radius: 13px; }
        .modal-detail span { display: block; color: var(--gris); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .7px; margin-bottom: 5px; }
        .modal-detail strong { font-size: 14px; }
        .modal-notes { grid-column: 1 / -1; }
        .modal-notes p { margin: 0; color: var(--texto); font-size: 13px; line-height: 1.55; white-space: pre-wrap; }
        .appointment-modal-footer { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 16px 24px 22px; border-top: 1px solid var(--borde); }
        .modal-appointment-actions { display: flex; justify-content: flex-end; align-items: center; gap: 8px; flex-wrap: wrap; }
        .modal-appointment-actions form { display: inline-flex; margin: 0; }
        .modal-appointment-actions .btn { gap: 6px; }
        @media (max-width: 800px) {
            .timeline-scroll { overflow: visible; }
            .gantt { grid-template-columns: 78px minmax(0,1fr); min-width: 0; }
            .gantt-corner, .gantt-hours { display: none; }
            .timeline-jump { display:none; }
            .gantt-day { position:static;min-height:auto;justify-content:flex-start;padding:14px 10px;background:var(--fondo);box-shadow:none; }
            .gantt-day strong { font-size: 11px; }
            .gantt-day span { font-size: 9px; }
            .gantt-track { position: static; display: grid; gap: 6px; min-height: auto; padding: 8px; background: var(--blanco); }
            .gantt-event { position: relative; inset: auto !important; width: 100% !important; min-width: 0; height: 42px; display: flex; align-items: center; gap: 8px; padding: 7px 9px; box-shadow: none; }
            .gantt-event > i { display: block; width: 7px; height: 7px; border-radius: 50%; background: currentColor; flex: 0 0 auto; }
            .gantt-event strong { flex: 1; flex-direction: row; align-items: center; gap: 8px; }
            .gantt-event strong time { width: 40px; flex: 0 0 40px; font-size: 10px; }
            .gantt-event strong span { font-size: 11px; }
            .gantt-event:hover { transform: translateX(2px); }
        }
        .appointment-form-card { max-width: 1050px; margin: 0 auto; padding: 30px; }
        .appointment-form-card form > .form-grid { align-items: start; }
        .appointment-date-group, .appointment-time-group { background: var(--fondo); border: 1px solid var(--borde); border-radius: 16px; padding: 16px; }
        .appointment-date-group > input, .appointment-time-group > input { background: var(--blanco); font-size: 16px; font-weight: 700; }
        .date-shortcuts, .time-shortcuts { display: flex; gap: 7px; flex-wrap: wrap; margin-bottom: 10px; }
        .time-shortcuts { margin: 10px 0 0; }
        .date-shortcut, .time-shortcut { border: 1px solid var(--borde); border-radius: 8px; background: var(--blanco); color: var(--texto); padding: 7px 10px; font-size: 11px; font-weight: 700; cursor: pointer; transition: .18s ease; }
        .date-shortcut:hover, .time-shortcut:hover, .date-shortcut:focus, .time-shortcut:focus { border-color: var(--dorado); color: var(--dorado); background: rgba(201,162,39,.08); outline: none; }
        .field-help { display: block; min-height: 17px; margin-top: 7px; color: var(--dorado); font-size: 11px; font-weight: 700; text-transform: capitalize; }
        .appointment-summary { min-height: 178px; display: grid; grid-template-columns: 1fr 1fr; align-content: start; gap: 4px 18px; background: linear-gradient(135deg, var(--texto), #323232); border: 0; color: var(--blanco); padding: 18px; }
        .appointment-summary span { color: rgba(255,255,255,.62); }
        .appointment-summary > strong { color: var(--dorado); font-size: 19px; }
        .appointment-summary br { display: none; }
        .appointment-window { grid-column: 1 / -1; border-top: 1px solid rgba(255,255,255,.14); margin-top: 12px; padding-top: 12px; }
        .appointment-window strong { display: block; margin-top: 4px; color: var(--blanco); font-size: 14px; }
        .appointment-list-filter { max-width: 720px; align-items: center; }
        .appointment-list-card { overflow: visible !important; }
        .appointment-list-filter { max-width: none; flex: 1; flex-wrap: wrap; }
        .appointment-list-filter .custom-date-picker { flex: 1 1 190px; min-width: 175px; }
        .range-separator { color: var(--gris); font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .range-indicator { display: flex; align-items: center; gap: 9px; margin: 2px 0 14px; color: var(--gris); font-size: 11px; }
        .range-indicator span { text-transform: uppercase; letter-spacing: .8px; font-weight: 800; }
        .range-indicator strong { color: var(--dorado); }
        .appointment-table-scroll { overflow-x: auto; margin: 0 -26px -26px; scrollbar-width: none; }
        .appointment-table-scroll::-webkit-scrollbar { display: none; }
        .appointment-table-scroll table { min-width: 850px; }
        .appointment-table-scroll table th:first-child, .appointment-table-scroll table td:first-child { padding-left: 26px; }
        .appointment-table-scroll table th:last-child, .appointment-table-scroll table td:last-child { padding-right: 26px; }
        .appointment-list-filter input[type="date"] { flex: 0 1 170px; min-width: 145px; font-weight: 700; }
        .appointment-list-filter .btn-icon { flex: 0 0 36px; }
        .native-date-enhanced { position: absolute !important; width: 1px !important; height: 1px !important; opacity: 0; pointer-events: none; padding: 0 !important; }
        .custom-date-picker { position: relative; width: 100%; min-width: 155px; }
        .search-form .custom-date-picker { flex: 1 1 170px; width: auto; }
        .appointment-list-filter .custom-date-picker { flex: 0 1 180px; }
        .agenda-filter .custom-date-picker, .month-filter .custom-date-picker { width: 180px; }
        .custom-date-trigger { width: 100%; min-height: 44px; display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 11px 13px; border: 1px solid var(--borde); border-radius: 10px; background: var(--blanco); color: var(--texto); cursor: pointer; font-weight: 700; text-align: left; }
        .custom-date-trigger:hover, .custom-date-trigger[aria-expanded="true"] { border-color: var(--dorado); box-shadow: 0 0 0 3px rgba(201,162,39,.12); }
        .custom-date-trigger svg { color: var(--dorado); flex: 0 0 auto; }
        .custom-calendar { display: none; position: absolute; z-index: 3000; top: calc(100% + 8px); left: 0; width: 310px; padding: 16px; border: 1px solid var(--borde); border-radius: 18px; background: var(--blanco); box-shadow: 0 22px 55px rgba(28,28,28,.18); }
        .custom-calendar.open { display: block; animation: calendar-in .16s ease-out; }
        @keyframes calendar-in { from { opacity: 0; transform: translateY(-5px) scale(.98); } to { opacity: 1; transform: none; } }
        .calendar-head { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 15px; }
        .calendar-head strong { font-family: 'Manrope', sans-serif; font-size: 14px; text-transform: capitalize; }
        .calendar-nav { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--borde); border-radius: 9px; background: var(--blanco); cursor: pointer; color: var(--texto); }
        .calendar-nav:hover { border-color: var(--dorado); color: var(--dorado); }
        .calendar-week, .calendar-days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
        .calendar-week span { padding-bottom: 7px; color: var(--gris); font-size: 9px; font-weight: 800; text-align: center; text-transform: uppercase; }
        .calendar-day { aspect-ratio: 1; border: 0; border-radius: 9px; background: transparent; color: var(--texto); cursor: pointer; font-size: 11px; font-weight: 700; }
        .calendar-day:hover { background: rgba(201,162,39,.12); color: var(--dorado); }
        .calendar-day.outside { color: #bbb5aa; font-weight: 500; }
        .calendar-day.today { box-shadow: inset 0 0 0 1px var(--dorado); color: var(--dorado); }
        .calendar-day.selected { background: var(--dorado); color: var(--blanco); box-shadow: 0 5px 12px rgba(201,162,39,.25); }
        .calendar-day:disabled { opacity: .28; cursor: not-allowed; background: transparent; color: var(--gris); }
        .calendar-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--borde); margin-top: 12px; padding-top: 12px; }
        .calendar-footer button { border: 0; background: transparent; color: var(--dorado); padding: 4px; font-size: 11px; font-weight: 800; cursor: pointer; }
        .calendar-footer span { color: var(--gris); font-size: 10px; }
        .native-time-enhanced { position: absolute !important; width: 1px !important; height: 1px !important; opacity: 0; pointer-events: none; padding: 0 !important; }
        .custom-time-picker { position: relative; width: 100%; }
        .custom-time-trigger { width: 100%; min-height: 44px; display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 11px 13px; border: 1px solid var(--borde); border-radius: 10px; background: var(--blanco); color: var(--texto); cursor: pointer; font-weight: 700; text-align: left; }
        .custom-time-trigger:hover, .custom-time-trigger[aria-expanded="true"] { border-color: var(--dorado); box-shadow: 0 0 0 3px rgba(201,162,39,.12); }
        .custom-time-trigger:disabled { cursor: not-allowed; opacity: .58; background: var(--crema); box-shadow: none; }
        .custom-time-trigger svg { color: var(--dorado); flex: 0 0 auto; }
        .custom-time-panel { display: none; position: absolute; z-index: 3000; top: calc(100% + 8px); left: 0; width: 330px; max-height: 390px; overflow-y: auto; scrollbar-width: none; padding: 16px; border: 1px solid var(--borde); border-radius: 18px; background: var(--blanco); box-shadow: 0 22px 55px rgba(28,28,28,.18); }
        .custom-time-panel::-webkit-scrollbar { display: none; }
        .custom-time-panel.open { display: block; animation: calendar-in .16s ease-out; }
        .time-panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
        .time-panel-head strong { font-family: 'Manrope', sans-serif; font-size: 14px; }
        .time-panel-head span { color: var(--gris); font-size: 10px; }
        .time-period + .time-period { border-top: 1px solid var(--borde); margin-top: 14px; padding-top: 14px; }
        .time-period-label { display: flex; align-items: center; gap: 7px; margin-bottom: 9px; color: var(--gris); font-size: 9px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
        .time-period-label::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: var(--dorado); }
        .time-slots { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; }
        .time-slot { border: 1px solid var(--borde); border-radius: 9px; background: var(--blanco); color: var(--texto); padding: 8px 5px; cursor: pointer; font-size: 10px; font-weight: 700; }
        .time-slot:hover { border-color: var(--dorado); color: var(--dorado); background: rgba(201,162,39,.08); }
        .time-slot.selected { border-color: var(--dorado); background: var(--dorado); color: var(--blanco); box-shadow: 0 5px 12px rgba(201,162,39,.22); }
        .time-slot.now { box-shadow: inset 0 0 0 1px var(--dorado); }
        .custom-time-empty { grid-column: 1 / -1; padding: 12px; border-radius: 9px; background: var(--crema); color: var(--gris); font-size: 10px; font-weight: 700; text-align: center; }
        @media (max-width: 1100px) { .agenda-summary-wide { grid-template-columns: repeat(3,1fr); } }
        @media (max-width: 700px) { .agenda-hero, .agenda-toolbar, .timeline-card-header, .agenda-details-heading { align-items: stretch; flex-direction: column; } .agenda-toolbar .agenda-filter { width: 100%; } .agenda-toolbar .agenda-filter input { flex: 1; min-width: 0; } .agenda-summary-wide { grid-template-columns: repeat(2,1fr); } .timeline-legend { flex-wrap: wrap; } .appointment-form-card { padding: 18px; } .date-shortcut, .time-shortcut { flex: 1; } }
        @media (max-width: 520px) { .appointment-modal { padding: 10px; } .appointment-modal-dialog { max-height: calc(100vh - 20px); border-radius: 19px; } .appointment-modal-header, .appointment-modal-body { padding: 18px; } .modal-appointment-heading { grid-template-columns: 52px 1fr; } .modal-appointment-heading > .badge { grid-column: 2; justify-self: start; } .modal-date-icon { width: 52px; height: 52px; } .modal-detail-grid { grid-template-columns: 1fr; } .modal-notes { grid-column: auto; } .appointment-modal-footer { align-items: stretch; flex-direction: column-reverse; padding: 14px 18px 18px; } .appointment-modal-footer > .btn, .modal-appointment-actions, .modal-appointment-actions .btn, .modal-appointment-actions form { width: 100%; } .modal-appointment-actions { display: grid; grid-template-columns: 1fr 1fr; } .modal-appointment-actions #modalEditLink { grid-column: 1 / -1; } }
        @media (max-width: 600px) { .search-form .custom-date-picker, .agenda-filter .custom-date-picker, .month-filter .custom-date-picker { width: 100%; flex-basis: auto; } }
        @media (max-width: 420px) { .custom-calendar, .custom-time-panel { position: fixed; left: 12px; right: 12px; top: 50%; width: auto; transform: translateY(-50%); } .custom-calendar.open, .custom-time-panel.open { animation: none; } .custom-time-panel { max-height: 80vh; } }
        @media (max-width: 900px) { .sidebar, body.sidebar-collapsed .sidebar { width: 278px; padding: 28px 20px 22px; z-index: 1001; overflow-x: hidden; overflow-y: auto; } .mobile-overlay { z-index: 1000; } .main, body.sidebar-collapsed .main { margin-left: 0; width: 100%; padding: 22px; } .sidebar-collapse-btn, body.sidebar-collapsed .menu-group-toggle { display: none; } .menu-group-items, body.sidebar-collapsed .menu-group-items { display: contents; position: static; } body.sidebar-collapsed .brand-text, body.sidebar-collapsed .menu-text { display: block; } body.sidebar-collapsed .menu-label { display: block; } body.sidebar-collapsed .sidebar-header { display: flex; min-height: 0; justify-content: space-between; flex-wrap: nowrap; } body.sidebar-collapsed .brand { padding: 0 8px; margin-bottom: 26px; justify-content: flex-start; } body.sidebar-collapsed .menu a, body.sidebar-collapsed .logout-button { justify-content: flex-start; padding: 11px 13px; gap: 12px; } }
        @media (max-width: 600px) { .main { padding: 18px 14px 30px; } .topbar { align-items: center; } .topbar-info { display: none; } .content-card { padding: 18px; overflow-x: auto; } .content-card > table { margin-left: -18px; margin-right: -18px; width: calc(100% + 36px); } .content-card > table th:first-child, .content-card > table td:first-child { padding-left: 18px; } .content-card > table th:last-child, .content-card > table td:last-child { padding-right: 18px; } .stat-card { padding: 18px; } .dashboard-intro { align-items: stretch; flex-direction: column; } }
        @media (max-width: 600px) { .topbar-actions { width: 100%; align-items: stretch; flex-direction: column-reverse; } .topbar-info { display: grid; width: 100%; min-width: 0; } .pwa-install-btn { width: 100%; } }
        .idle-timer { position: fixed; right: 22px; bottom: 20px; z-index: 1300; display: flex; align-items: center; gap: 9px; padding: 10px 14px; border: 1px solid rgba(201,162,39,.4); border-radius: 999px; background: rgba(28,28,28,.94); color: var(--blanco); box-shadow: 0 10px 25px rgba(28,28,28,.18); font-size: 12px; font-weight: 700; opacity: 0; visibility: hidden; transform: translateY(8px); transition: .25s ease; pointer-events: none; }
        .idle-timer.visible { opacity: 1; visibility: visible; transform: translateY(0); }
        .idle-timer::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: var(--dorado); box-shadow: 0 0 0 4px rgba(201,162,39,.15); }
        .idle-timer strong { color: var(--dorado); font-variant-numeric: tabular-nums; }
        @media (max-width: 600px) { .idle-timer { right: 14px; bottom: 14px; } }
        .quick-context-menu { position: fixed; z-index: 4000; width: min(280px, calc(100vw - 24px)); padding: 9px; border: 1px solid var(--borde); border-radius: 16px; background: rgba(255,255,255,.98); box-shadow: 0 22px 55px rgba(28,28,28,.2); opacity: 0; visibility: hidden; transform: translateY(7px) scale(.98); transform-origin: top left; transition: opacity .14s ease, transform .14s ease, visibility .14s ease; }
        .quick-context-menu.open { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }
        .quick-context-heading { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 7px 9px 9px; color: var(--gris); font-size: 9px; font-weight: 900; letter-spacing: 1.1px; text-transform: uppercase; }
        .quick-context-heading span:last-child { color: var(--dorado); font-size: 8px; letter-spacing: .6px; }
        .quick-context-list { display: grid; gap: 3px; }
        .quick-context-item { display: grid; grid-template-columns: 36px minmax(0,1fr); align-items: center; gap: 10px; min-height: 50px; padding: 7px 9px; border-radius: 11px; color: var(--texto); text-decoration: none; }
        .quick-context-item:hover, .quick-context-item:focus-visible { outline: 0; background: var(--crema); color: var(--dorado); }
        .quick-context-icon { width: 36px; height: 36px; display: grid; place-items: center; border: 1px solid rgba(201,162,39,.2); border-radius: 10px; background: rgba(201,162,39,.09); color: var(--dorado); }
        .quick-context-icon svg { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .quick-context-copy { min-width: 0; }
        .quick-context-copy strong, .quick-context-copy small { display: block; }
        .quick-context-copy strong { color: inherit; font-size: 12px; }
        .quick-context-copy small { margin-top: 2px; overflow: hidden; color: var(--gris); font-size: 9px; text-overflow: ellipsis; white-space: nowrap; }
        @media (hover: none), (pointer: coarse) { .quick-context-menu { display: none; } }
    </style>
</head>
<body>

<div id="mobileOverlay" class="mobile-overlay" aria-hidden="true"></div>
<div class="idle-timer" id="idleTimer" role="status" aria-live="polite"><span>Sesión por inactividad</span><strong id="idleTimerValue">10:00</strong></div>
<svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
    <symbol id="i-dashboard" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24"><circle cx="9" cy="8" r="4"/><path d="M3 21v-2a6 6 0 0 1 12 0v2M16 4.5a4 4 0 0 1 0 7.5M17 15a6 6 0 0 1 4 5.7"/></symbol>
    <symbol id="i-user-off" viewBox="0 0 24 24"><circle cx="10" cy="8" r="4"/><path d="M3.5 21v-2a6.5 6.5 0 0 1 11-4.7M17 17l4 4M21 17l-4 4"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5 21v-2a7 7 0 0 1 14 0v2"/></symbol>
    <symbol id="i-calendar" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
    <symbol id="i-scissors" viewBox="0 0 24 24"><circle cx="6" cy="7" r="3"/><circle cx="6" cy="17" r="3"/><path d="m8.5 8.5 11 7M8.5 15.5l11-7"/></symbol>
    <symbol id="i-box" viewBox="0 0 24 24"><path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="M3 8v9l9 5 9-5V8M12 13v9"/></symbol>
    <symbol id="i-cart" viewBox="0 0 24 24"><path d="M3 3h2l2.4 11.5a2 2 0 0 0 2 1.5h7.8a2 2 0 0 0 2-1.6L21 7H6"/><circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></symbol>
    <symbol id="i-gift" viewBox="0 0 24 24"><rect x="3" y="8" width="18" height="13" rx="2"/><path d="M12 8v13M3 12h18M7 8C4 8 4 3 7 3s5 5 5 5M17 8c3 0 3-5 0-5s-5 5-5 5"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></symbol>
    <symbol id="i-settings" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19 12a7 7 0 0 0-.1-1l2-1.5-2-3.4-2.5 1a8 8 0 0 0-1.8-1L14.2 3h-4.4l-.4 3.1a8 8 0 0 0-1.8 1l-2.5-1-2 3.4L5.1 11a7 7 0 0 0 0 2l-2 1.5 2 3.4 2.5-1a8 8 0 0 0 1.8 1l.4 3.1h4.4l.4-3.1a8 8 0 0 0 1.8-1l2.5 1 2-3.4-2-1.5a7 7 0 0 0 .1-1Z"/></symbol>
    <symbol id="i-logout" viewBox="0 0 24 24"><path d="M10 17l5-5-5-5M15 12H3M15 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4"/></symbol>
</defs></svg>

<div class="app">
    <aside class="sidebar" id="sidebar" aria-label="Menú principal">
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
                    <div id="connectionStatus" class="connection-status checking" role="status" aria-live="polite" title="Comprobando conexión con BarberCore">
                        <i class="connection-status-dot" aria-hidden="true"></i>
                        <span id="connectionStatusLabel">Comprobando</span>
                    </div>
                </div>
            </div>

            <button
                type="button"
                class="mobile-close-btn"
                id="closeSidebar"
                aria-label="Cerrar menú"
                aria-controls="sidebar"
            >✕</button>
            <button type="button" class="sidebar-collapse-btn" id="collapseSidebar" aria-label="Colapsar menú" aria-expanded="true" title="Colapsar menú"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg></button>
        </div>

        <nav class="menu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard"><span class="menu-icon"><svg><use href="#i-dashboard"/></svg></span><span class="menu-text">Dashboard</span></a>
            <div class="menu-group">
                <span class="menu-label">Principal</span>
                <button type="button" class="menu-group-toggle" aria-expanded="false" aria-label="Mostrar opciones de Principal" title="Principal"><span class="menu-icon"><svg><use href="#i-users"/></svg></span></button>
                <div class="menu-group-items" data-label="Principal">
                    <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.index') ? 'active' : '' }}" title="Clientes"><span class="menu-icon"><svg><use href="#i-users"/></svg></span><span class="menu-text">Clientes</span></a>
                    <a href="{{ route('clientes.inactivos') }}" class="{{ request()->routeIs('clientes.inactivos') ? 'active' : '' }}" title="Clientes inactivos"><span class="menu-icon"><svg><use href="#i-user-off"/></svg></span><span class="menu-text">Clientes inactivos</span></a>
                    @if(auth()->check() && auth()->user()->rol === 'admin')
                        <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}" title="Usuarios"><span class="menu-icon"><svg><use href="#i-user"/></svg></span><span class="menu-text">Usuarios</span></a>
                    @endif
                </div>
            </div>
            <div class="menu-group">
                <span class="menu-label">Operación</span>
                <button type="button" class="menu-group-toggle" aria-expanded="false" aria-label="Mostrar opciones de Operación" title="Operación"><span class="menu-icon"><svg><use href="#i-calendar"/></svg></span></button>
                <div class="menu-group-items" data-label="Operación">
                    <a href="{{ route('citas.index') }}" class="{{ request()->routeIs('citas.*') ? 'active' : '' }}" title="Citas"><span class="menu-icon"><svg><use href="#i-calendar"/></svg></span><span class="menu-text">Citas</span></a>
                    <a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.*') ? 'active' : '' }}" title="Agenda"><span class="menu-icon"><svg><use href="#i-clock"/></svg></span><span class="menu-text">Agenda</span></a>
                    @if(auth()->user()->rol === 'admin')
                    <a href="{{ route('servicios.index') }}" class="{{ request()->routeIs('servicios.*') ? 'active' : '' }}" title="Servicios"><span class="menu-icon"><svg><use href="#i-scissors"/></svg></span><span class="menu-text">Servicios</span></a>
                    <a href="{{ route('productos.index') }}" class="{{ request()->routeIs('productos.*') ? 'active' : '' }}" title="Productos"><span class="menu-icon"><svg><use href="#i-box"/></svg></span><span class="menu-text">Productos</span></a>
                    <a href="{{ route('ventas-productos.index') }}" class="{{ request()->routeIs('ventas-productos.*') ? 'active' : '' }}" title="Ventas"><span class="menu-icon"><svg><use href="#i-cart"/></svg></span><span class="menu-text">Ventas</span></a>
                    <a href="{{ route('recompensas.index') }}" class="{{ request()->routeIs('recompensas.*') ? 'active' : '' }}" title="Recompensas"><span class="menu-icon"><svg><use href="#i-gift"/></svg></span><span class="menu-text">Recompensas</span></a>
                    @endif
                </div>
            </div>
            @if(auth()->user()->rol === 'admin')
            <div class="menu-group">
                <span class="menu-label">Análisis</span>
                <button type="button" class="menu-group-toggle" aria-expanded="false" aria-label="Mostrar opciones de Análisis" title="Análisis"><span class="menu-icon"><svg><use href="#i-chart"/></svg></span></button>
                <div class="menu-group-items" data-label="Análisis">
                    <a href="{{ route('estadisticas.index') }}" class="{{ request()->routeIs('estadisticas.*') ? 'active' : '' }}" title="Reportes"><span class="menu-icon"><svg><use href="#i-chart"/></svg></span><span class="menu-text">Reportes</span></a>
                    <a href="{{ route('configuracion.index') }}" class="{{ request()->routeIs('configuracion.*') ? 'active' : '' }}" title="Configuración"><span class="menu-icon"><svg><use href="#i-settings"/></svg></span><span class="menu-text">Configuración</span></a>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="submit" class="logout-button">
                    <span class="menu-icon"><svg><use href="#i-logout"/></svg></span><span class="menu-text">Cerrar sesión</span>
                </button>
            </form>
        </nav>
    </aside>

    <main class="main">
        <div class="topbar">
            <div class="topbar-left">
                <button
                    type="button"
                    class="mobile-menu-btn"
                    id="openSidebar"
                    aria-label="Abrir menú"
                    aria-controls="sidebar"
                    aria-expanded="false"
                >☰</button>
                <div>
                    <div class="page-kicker">Panel administrativo</div>
                    <h2>@yield('page-title', 'Panel BarberCore')</h2>
                </div>
            </div>

            <div class="topbar-actions">
                <button id="installPwaBtn" class="pwa-install-btn" type="button" style="display: none;">
                    <x-icon name="download" size="16" />
                    <span>Instalar BarberCore</span>
                </button>
                <a href="{{ route('agenda.index') }}" class="topbar-info" title="Abrir mi agenda">
                    <span class="topbar-barber-avatar" aria-hidden="true">{{ $inicialesBarbero ?: 'BC' }}</span>
                    <span class="topbar-barber-content">
                        <span class="topbar-barber-heading">
                            <strong>{{ auth()->user()->nombre ?? auth()->user()->name ?? 'Usuario' }}</strong>
                            <span class="topbar-role-chip">{{ ucfirst(auth()->user()->rol ?? 'barbero') }}</span>
                        </span>
                        @if ($proximaCitaBarbero && $proximaCitaInicio)
                            <span class="topbar-next-appointment">
                                <x-icon name="clock" size="13" />
                                <span>Próxima cita</span>
                                <strong class="js-next-appointment-countdown" data-start="{{ $proximaCitaInicio->toIso8601String() }}">Calculando…</strong>
                            </span>
                            <span class="topbar-appointment-detail">
                                {{ $proximaCitaBarbero->cliente->nombre ?? 'Cliente' }} {{ $proximaCitaBarbero->cliente->apellido ?? '' }} · {{ $proximaCitaBarbero->servicio->nombre ?? 'Servicio' }} · {{ $proximaCitaInicio->format('d/m H:i') }}
                            </span>
                        @else
                            <span class="topbar-next-appointment"><x-icon name="calendar" size="13" /><strong>Sin citas próximas</strong></span>
                            <span class="topbar-appointment-detail">Tu agenda pendiente está libre.</span>
                        @endif
                    </span>
                </a>
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

<nav class="quick-context-menu" id="quickContextMenu" aria-label="Accesos rápidos" aria-hidden="true">
    <div class="quick-context-heading"><span>Accesos rápidos</span><span>BarberCore</span></div>
    <div class="quick-context-list">
        <a class="quick-context-item" href="{{ route('citas.create') }}">
            <span class="quick-context-icon"><svg><use href="#i-calendar"/></svg></span>
            <span class="quick-context-copy"><strong>Nueva cita</strong><small>Registrar una cita rápidamente</small></span>
        </a>
        <a class="quick-context-item" href="{{ route('agenda.index', ['vista' => 'dia', 'fecha' => now()->toDateString()]) }}">
            <span class="quick-context-icon"><svg><use href="#i-clock"/></svg></span>
            <span class="quick-context-copy"><strong>Agenda de hoy</strong><small>Consultar horarios y disponibilidad</small></span>
        </a>
        @if(auth()->user()->rol === 'admin')
        <a class="quick-context-item" href="{{ route('ventas-productos.create') }}">
            <span class="quick-context-icon"><svg><use href="#i-cart"/></svg></span>
            <span class="quick-context-copy"><strong>Registrar venta</strong><small>Crear una venta de productos</small></span>
        </a>
        @endif
        <a class="quick-context-item" href="{{ route('clientes.create') }}">
            <span class="quick-context-icon"><svg><use href="#i-users"/></svg></span>
            <span class="quick-context-copy"><strong>Nuevo cliente</strong><small>Agregar un cliente al sistema</small></span>
        </a>
    </div>
</nav>

<script>
    (function () {
        const menu = document.getElementById('quickContextMenu');
        if (!menu || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

        const links = Array.from(menu.querySelectorAll('a'));

        function closeMenu() {
            menu.classList.remove('open');
            menu.setAttribute('aria-hidden', 'true');
        }

        function openMenu(clientX, clientY) {
            menu.style.left = '0px';
            menu.style.top = '0px';
            menu.classList.add('open');
            menu.setAttribute('aria-hidden', 'false');

            const margin = 12;
            const width = menu.offsetWidth;
            const height = menu.offsetHeight;
            const left = Math.min(Math.max(margin, clientX), window.innerWidth - width - margin);
            const top = Math.min(Math.max(margin, clientY), window.innerHeight - height - margin);

            menu.style.left = `${left}px`;
            menu.style.top = `${top}px`;
        }

        document.addEventListener('contextmenu', function (event) {
            event.preventDefault();
            openMenu(event.clientX, event.clientY);
        });

        document.addEventListener('pointerdown', function (event) {
            if (!menu.contains(event.target)) closeMenu();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeMenu();
                return;
            }

            if (event.shiftKey && event.key === 'F10') {
                event.preventDefault();
                openMenu(window.innerWidth / 2, window.innerHeight / 2);
                links[0]?.focus();
            }
        });

        window.addEventListener('resize', closeMenu);
        window.addEventListener('scroll', closeMenu, true);
    })();

    (function () {
        const TIMEOUT_MS = 10 * 60 * 1000;
        const SHOW_AFTER_MS = 5000;
        const HEARTBEAT_INTERVAL_MS = 30000;
        const ACTIVITY_THROTTLE_MS = 1000;
        const STORAGE_KEY = 'barbercore-last-activity';
        const timer = document.getElementById('idleTimer');
        const timerValue = document.getElementById('idleTimerValue');
        const logoutForm = document.getElementById('logoutForm');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        let lastActivity = Date.now();
        let lastHandledActivity = 0;
        let lastHeartbeat = 0;
        let loggingOut = false;

        function storeActivity(timestamp) {
            lastActivity = timestamp;
            try { localStorage.setItem(STORAGE_KEY, String(timestamp)); } catch (error) {}
        }

        function heartbeat() {
            const now = Date.now();
            if ((now - lastHeartbeat) < HEARTBEAT_INTERVAL_MS) return;
            lastHeartbeat = now;

            fetch(@json(route('session.activity')), {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(function (response) {
                if (response.status === 401 || response.status === 419) {
                    window.location.assign(@json(route('login')));
                }
            }).catch(function () {
                // El siguiente request protegido también validará la expiración.
            });
        }

        function registerActivity() {
            const now = Date.now();
            if ((now - lastHandledActivity) < ACTIVITY_THROTTLE_MS) return;
            lastHandledActivity = now;
            storeActivity(now);
            timer?.classList.remove('visible');
            heartbeat();
        }

        function finishSession() {
            if (loggingOut) return;
            loggingOut = true;
            timer?.classList.add('visible');
            if (timerValue) timerValue.textContent = '00:00';
            try { localStorage.removeItem(STORAGE_KEY); } catch (error) {}

            if (logoutForm) {
                logoutForm.requestSubmit();
            } else {
                window.location.assign(@json(route('login')));
            }
        }

        function updateTimer() {
            const idleTime = Date.now() - lastActivity;
            const remaining = Math.max(0, TIMEOUT_MS - idleTime);

            if (remaining <= 0) {
                finishSession();
                return;
            }

            if (idleTime >= SHOW_AFTER_MS) timer?.classList.add('visible');
            else timer?.classList.remove('visible');

            const totalSeconds = Math.ceil(remaining / 1000);
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            if (timerValue) timerValue.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }

        ['pointerdown', 'pointermove', 'keydown', 'scroll', 'touchstart'].forEach(function (eventName) {
            window.addEventListener(eventName, registerActivity, { passive: true });
        });

        window.addEventListener('storage', function (event) {
            if (event.key !== STORAGE_KEY || !event.newValue) return;
            const sharedActivity = Number(event.newValue);
            if (Number.isFinite(sharedActivity) && sharedActivity > lastActivity) {
                lastActivity = sharedActivity;
                timer?.classList.remove('visible');
            }
        });

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) registerActivity();
        });

        storeActivity(Date.now());
        heartbeat();
        updateTimer();
        window.setInterval(updateTimer, 1000);
    })();
</script>

<script>
    (function () {
        const MOBILE_BREAKPOINT = 900;
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const collapseBtn = document.getElementById('collapseSidebar');
        const menuLinks = sidebar ? sidebar.querySelectorAll('.menu a') : [];
        const menuGroups = sidebar ? sidebar.querySelectorAll('.menu-group') : [];
        const groupToggles = sidebar ? sidebar.querySelectorAll('.menu-group-toggle') : [];
        const menuSequenceItems = sidebar ? sidebar.querySelectorAll('.menu > a, .menu-group > .menu-label, .menu-group-items > a, .menu > form') : [];
        const collapsedIconItems = sidebar ? sidebar.querySelectorAll('.menu > a, .menu-group-toggle, .menu > form') : [];
        const brandLines = sidebar ? sidebar.querySelectorAll('.brand-text strong, .brand-text > span') : [];
        let previousFocus = null;
        let sidebarAnimationBusy = false;

        menuSequenceItems.forEach(function (item, index) {
            item.style.setProperty('--item-index', index);
        });

        collapsedIconItems.forEach(function (item, index) {
            item.style.setProperty('--collapsed-icon-index', index);
        });

        brandLines.forEach(function (line, lineIndex) {
            const text = line.textContent;
            line.textContent = '';
            line.classList.add('brand-type-line', lineIndex === 0 ? 'brand-type-primary' : 'brand-type-secondary');

            Array.from(text).forEach(function (character, characterIndex) {
                const span = document.createElement('span');
                span.className = 'brand-character';
                span.style.setProperty('--char-index', characterIndex);
                span.textContent = character === ' ' ? '\u00a0' : character;
                span.setAttribute('aria-hidden', 'true');
                line.appendChild(span);
            });

            line.setAttribute('aria-label', text);
        });

        function isMobileView() {
            return window.innerWidth <= MOBILE_BREAKPOINT;
        }

        function updateAccessibility(isOpen) {
            if (openBtn) {
                openBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }

            if (overlay) {
                overlay.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            }

            if (sidebar) {
                sidebar.setAttribute('aria-hidden', isMobileView() && !isOpen ? 'true' : 'false');
            }
        }

        function openSidebar() {
            if (!isMobileView() || !sidebar || !overlay) {
                return;
            }

            previousFocus = document.activeElement;
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.classList.add('sidebar-open');
            updateAccessibility(true);

            window.requestAnimationFrame(function () {
                if (closeBtn) {
                    closeBtn.focus();
                }
            });
        }

        function closeSidebar(restoreFocus = true) {
            if (!sidebar || !overlay) {
                return;
            }

            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.classList.remove('sidebar-open');
            updateAccessibility(false);

            if (restoreFocus && previousFocus && typeof previousFocus.focus === 'function') {
                previousFocus.focus();
            }

            previousFocus = null;
        }

        if (openBtn) {
            openBtn.addEventListener('click', openSidebar);
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                closeSidebar();
            });
        }

        function setCollapsed(collapsed) {
            document.body.classList.toggle('sidebar-collapsed', collapsed);
            if (!collapsed) {
                menuGroups.forEach(function (group) { group.classList.remove('open'); });
                groupToggles.forEach(function (toggle) { toggle.setAttribute('aria-expanded', 'false'); });
            }
            if (collapseBtn) {
                collapseBtn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                collapseBtn.setAttribute('aria-label', collapsed ? 'Expandir menú' : 'Colapsar menú');
                collapseBtn.setAttribute('title', collapsed ? 'Expandir menú' : 'Colapsar menú');
            }
        }

        try {
            setCollapsed(localStorage.getItem('barbercore-sidebar-collapsed') === 'true');
        } catch (error) {
            setCollapsed(false);
        }

        if (collapseBtn) {
            collapseBtn.addEventListener('click', function () {
                if (sidebarAnimationBusy || isMobileView()) return;
                sidebarAnimationBusy = true;
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');

                if (isCollapsed) {
                    document.body.classList.add('sidebar-expanding');
                    setCollapsed(false);
                    window.setTimeout(function () {
                        document.body.classList.remove('sidebar-expanding');
                        sidebarAnimationBusy = false;
                    }, 2700);
                } else {
                    document.body.classList.add('sidebar-collapsing');
                    window.setTimeout(function () {
                        setCollapsed(true);
                        document.body.classList.remove('sidebar-collapsing');
                        document.body.classList.add('sidebar-icons-revealing');
                        window.setTimeout(function () {
                            document.body.classList.remove('sidebar-icons-revealing');
                            sidebarAnimationBusy = false;
                        }, 1250);
                    }, 160);
                }

                try {
                    localStorage.setItem('barbercore-sidebar-collapsed', String(!isCollapsed));
                } catch (error) {}
            });
        }

        groupToggles.forEach(function (toggle) {
            toggle.addEventListener('click', function (event) {
                event.stopPropagation();
                const group = toggle.closest('.menu-group');
                const willOpen = group && !group.classList.contains('open');
                menuGroups.forEach(function (item) { item.classList.remove('open'); });
                groupToggles.forEach(function (item) { item.setAttribute('aria-expanded', 'false'); });
                if (group && willOpen) {
                    group.classList.add('open');
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });
        });

        document.addEventListener('click', function (event) {
            if (sidebar && !sidebar.contains(event.target)) {
                menuGroups.forEach(function (group) { group.classList.remove('open'); });
                groupToggles.forEach(function (toggle) { toggle.setAttribute('aria-expanded', 'false'); });
            }
        });

        if (overlay) {
            overlay.addEventListener('click', function () {
                closeSidebar();
            });
        }

        menuLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                if (isMobileView()) {
                    closeSidebar(false);
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && sidebar && sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });

        window.addEventListener('resize', function () {
            if (!isMobileView()) {
                closeSidebar(false);
                updateAccessibility(false);
            } else if (sidebar && !sidebar.classList.contains('open')) {
                updateAccessibility(false);
            }
        });

        updateAccessibility(sidebar ? sidebar.classList.contains('open') : false);
    })();
</script>

<script>
    (function () {
        const months = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
        const weekdays = ['L','M','M','J','V','S','D'];
        const pad = number => String(number).padStart(2, '0');
        const toValue = date => date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
        const fromValue = value => value ? new Date(value + 'T12:00:00') : new Date();
        const formatLabel = value => value ? fromValue(value).toLocaleDateString('es-MX', { day: 'numeric', month: 'long', year: 'numeric' }) : 'Seleccionar fecha';

        document.querySelectorAll('input[type="date"]').forEach(function (input) {
            if (input.dataset.customDateReady) return;
            input.dataset.customDateReady = 'true';
            input.classList.add('native-date-enhanced');

            const picker = document.createElement('div');
            picker.className = 'custom-date-picker';
            picker.innerHTML = `
                <button type="button" class="custom-date-trigger" aria-expanded="false">
                    <span></span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>
                </button>
                <div class="custom-calendar" role="dialog" aria-label="Seleccionar fecha">
                    <div class="calendar-head"><button type="button" class="calendar-nav prev" aria-label="Mes anterior">‹</button><strong></strong><button type="button" class="calendar-nav next" aria-label="Mes siguiente">›</button></div>
                    <div class="calendar-week">${weekdays.map(day => `<span>${day}</span>`).join('')}</div>
                    <div class="calendar-days"></div>
                    <div class="calendar-footer"><button type="button" class="calendar-today">Ir a hoy</button><span>Selecciona un día</span></div>
                </div>`;
            input.insertAdjacentElement('afterend', picker);

            const trigger = picker.querySelector('.custom-date-trigger');
            const triggerLabel = trigger.querySelector('span');
            const calendar = picker.querySelector('.custom-calendar');
            const title = picker.querySelector('.calendar-head strong');
            const days = picker.querySelector('.calendar-days');
            const restrictWeekdays = input.hasAttribute('data-open-weekdays');
            const openWeekdays = (input.dataset.openWeekdays || '').split(',').filter(Boolean).map(Number);
            let cursor = fromValue(input.value);

            function render() {
                triggerLabel.textContent = formatLabel(input.value);
                title.textContent = months[cursor.getMonth()] + ' ' + cursor.getFullYear();
                days.innerHTML = '';
                const first = new Date(cursor.getFullYear(), cursor.getMonth(), 1);
                const mondayOffset = (first.getDay() + 6) % 7;
                const gridStart = new Date(cursor.getFullYear(), cursor.getMonth(), 1 - mondayOffset);
                const today = toValue(new Date());

                for (let index = 0; index < 42; index++) {
                    const date = new Date(gridStart);
                    date.setDate(gridStart.getDate() + index);
                    const value = toValue(date);
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'calendar-day';
                    button.textContent = date.getDate();
                    if (date.getMonth() !== cursor.getMonth()) button.classList.add('outside');
                    if (value === today) button.classList.add('today');
                    if (value === input.value) button.classList.add('selected');
                    if ((input.min && value < input.min) || (input.max && value > input.max)) button.disabled = true;
                    const weekday = (date.getDay() + 6) % 7;
                    if (restrictWeekdays && !openWeekdays.includes(weekday)) button.disabled = true;
                    button.addEventListener('click', function () {
                        input.value = value;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                        cursor = date;
                        calendar.classList.remove('open');
                        trigger.setAttribute('aria-expanded', 'false');
                        render();
                    });
                    days.appendChild(button);
                }
            }

            trigger.addEventListener('click', function () {
                document.querySelectorAll('.custom-calendar.open').forEach(open => { if (open !== calendar) open.classList.remove('open'); });
                calendar.classList.toggle('open');
                trigger.setAttribute('aria-expanded', calendar.classList.contains('open') ? 'true' : 'false');
            });
            picker.querySelector('.prev').addEventListener('click', () => { cursor = new Date(cursor.getFullYear(), cursor.getMonth() - 1, 1); render(); });
            picker.querySelector('.next').addEventListener('click', () => { cursor = new Date(cursor.getFullYear(), cursor.getMonth() + 1, 1); render(); });
            picker.querySelector('.calendar-today').addEventListener('click', () => {
                const today = new Date();
                const value = toValue(today);
                const weekday = (today.getDay() + 6) % 7;
                const isOpen = !restrictWeekdays || openWeekdays.includes(weekday);
                if ((!input.min || value >= input.min) && (!input.max || value <= input.max) && isOpen) {
                    input.value = value;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
                cursor = today;
                render();
            });
            input.addEventListener('change', function () { cursor = fromValue(input.value); render(); });
            render();
        });

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.custom-date-picker')) document.querySelectorAll('.custom-calendar.open').forEach(calendar => calendar.classList.remove('open'));
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') document.querySelectorAll('.custom-calendar.open').forEach(calendar => calendar.classList.remove('open'));
        });
    })();
</script>

<script>
    (function () {
        const pad = number => String(number).padStart(2, '0');
        const toMinutes = value => { const parts = (value || '00:00').split(':'); return (parseInt(parts[0], 10) * 60) + parseInt(parts[1], 10); };
        const toValue = minutes => pad(Math.floor(minutes / 60)) + ':' + pad(minutes % 60);
        const formatTime = value => {
            if (!value) return 'Seleccionar hora';
            const date = new Date('2000-01-01T' + value);
            return date.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });
        };

        document.querySelectorAll('input[type="time"]').forEach(function (input) {
            if (input.dataset.customTimeReady) return;
            input.dataset.customTimeReady = 'true';
            input.classList.add('native-time-enhanced');

            const picker = document.createElement('div');
            picker.className = 'custom-time-picker';
            picker.innerHTML = `
                <button type="button" class="custom-time-trigger" aria-expanded="false">
                    <span></span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                </button>
                <div class="custom-time-panel" role="dialog" aria-label="Seleccionar hora">
                    <div class="time-panel-head"><strong>Selecciona un horario</strong><span>Intervalos de 15 min</span></div>
                    <div class="time-period morning"><div class="time-period-label">Mañana</div><div class="time-slots"></div></div>
                    <div class="time-period afternoon"><div class="time-period-label">Tarde</div><div class="time-slots"></div></div>
                </div>`;
            input.insertAdjacentElement('afterend', picker);

            const trigger = picker.querySelector('.custom-time-trigger');
            const label = trigger.querySelector('span');
            const panel = picker.querySelector('.custom-time-panel');
            const morning = picker.querySelector('.morning .time-slots');
            const afternoon = picker.querySelector('.afternoon .time-slots');
            const afternoonPeriod = picker.querySelector('.afternoon');

            function render() {
                label.textContent = formatTime(input.value);
                morning.innerHTML = '';
                afternoon.innerHTML = '';
                const unavailable = input.dataset.unavailable === 'true';
                trigger.disabled = unavailable;

                if (unavailable) {
                    label.textContent = input.dataset.unavailableLabel || 'Sin horarios disponibles';
                    const message = input.dataset.unavailableMessage || 'El negocio no atiende este día.';
                    const empty = document.createElement('span');
                    empty.className = 'custom-time-empty';
                    empty.textContent = message;
                    morning.appendChild(empty);
                    afternoonPeriod.style.display = 'none';
                    panel.classList.remove('open');
                    trigger.setAttribute('aria-expanded', 'false');
                    return;
                }

                afternoonPeriod.style.display = '';
                const step = Math.max(5, parseInt(input.step || '900', 10) / 60);
                const start = input.min ? toMinutes(input.min) : 8 * 60;
                const end = input.max ? toMinutes(input.max) : 20 * 60;
                const restrictAvailableTimes = input.hasAttribute('data-available-times');
                const availableTimes = (input.dataset.availableTimes || '').split(',').filter(Boolean);
                const now = new Date();
                const nearestNow = Math.round((now.getHours() * 60 + now.getMinutes()) / step) * step;

                for (let minutes = start; minutes <= end; minutes += step) {
                    const value = toValue(minutes);
                    if (restrictAvailableTimes && !availableTimes.includes(value)) continue;
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'time-slot';
                    button.textContent = formatTime(value);
                    if (value === input.value.slice(0, 5)) button.classList.add('selected');
                    if (minutes === nearestNow) button.classList.add('now');
                    button.addEventListener('click', function () {
                        input.value = value;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                        panel.classList.remove('open');
                        trigger.setAttribute('aria-expanded', 'false');
                        render();
                    });
                    (minutes < 12 * 60 ? morning : afternoon).appendChild(button);
                }

                if (!morning.children.length && !afternoon.children.length) {
                    const empty = document.createElement('span');
                    empty.className = 'custom-time-empty';
                    empty.textContent = input.dataset.unavailableMessage || 'No hay horarios disponibles.';
                    morning.appendChild(empty);
                    afternoonPeriod.style.display = 'none';
                }
            }

            trigger.addEventListener('click', function () {
                document.querySelectorAll('.custom-time-panel.open').forEach(open => { if (open !== panel) open.classList.remove('open'); });
                panel.classList.toggle('open');
                trigger.setAttribute('aria-expanded', panel.classList.contains('open') ? 'true' : 'false');
            });
            input.addEventListener('change', render);
            render();
        });

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.custom-time-picker')) document.querySelectorAll('.custom-time-panel.open').forEach(panel => panel.classList.remove('open'));
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') document.querySelectorAll('.custom-time-panel.open').forEach(panel => panel.classList.remove('open'));
        });
    })();
</script>

<script>
    (function () {
        const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]:not([data-skip-auto-preview]), input[type="file"][accept*="jpg"]:not([data-skip-auto-preview]), input[type="file"][accept*="png"]:not([data-skip-auto-preview])');

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

<script>
    (function () {
        const counters = document.querySelectorAll('.js-next-appointment-countdown');
        if (!counters.length) return;

        function updateCounter(counter) {
            const start = new Date(counter.dataset.start);
            const difference = start.getTime() - Date.now();

            if (!Number.isFinite(start.getTime()) || difference <= 60000) {
                counter.textContent = 'por comenzar';
                return;
            }

            const totalMinutes = Math.ceil(difference / 60000);
            if (totalMinutes < 60) {
                counter.textContent = `en ${totalMinutes} min`;
                return;
            }

            const hours = Math.floor(totalMinutes / 60);
            const minutes = totalMinutes % 60;
            if (hours < 24) {
                counter.textContent = `en ${hours} h${minutes ? ` ${minutes} min` : ''}`;
                return;
            }

            const days = Math.floor(hours / 24);
            const remainingHours = hours % 24;
            counter.textContent = `en ${days} ${days === 1 ? 'día' : 'días'}${remainingHours ? ` ${remainingHours} h` : ''}`;
        }

        const updateAll = () => counters.forEach(updateCounter);
        updateAll();
        window.setInterval(updateAll, 30000);
    })();
</script>

<script src="/js/pwa-install.js"></script>
<script src="/js/pwa-status.js"></script>
<script src="/js/barber-dialog.js"></script>

</body>
</html>
