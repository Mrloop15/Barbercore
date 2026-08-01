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
            width: max-content;
            max-width: calc(100vw - 32px);
            padding: 12px 20px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.25;
            text-align: center;
            white-space: normal;
            pointer-events: none;
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
        .report-builder{margin-bottom:16px}.report-filter{display:grid;grid-template-columns:repeat(3,minmax(150px,1fr)) auto;gap:14px;align-items:end}.report-filter .form-group{margin:0}.report-filter-actions{display:flex;gap:8px;padding-bottom:1px}.report-summary{grid-template-columns:repeat(5,1fr);margin-bottom:16px}.report-preview{overflow-x:auto}
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

            .pwa-status-bar {
                top: auto;
                bottom: calc(16px + env(safe-area-inset-bottom));
                max-width: calc(100vw - 24px);
                padding: 10px 14px;
                border-radius: 16px;
                font-size: 13px;
            }

            .pwa-install-btn {
                right: max(12px, env(safe-area-inset-right));
                bottom: calc(12px + env(safe-area-inset-bottom));
                max-width: calc(100vw - 24px);
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
        .agenda-hero, .agenda-toolbar, .timeline-card, .agenda-details { background: var(--blanco); border: 1px solid var(--borde); box-shadow: 0 10px 30px rgba(28,28,28,.05); }
        .agenda-hero { display: flex; justify-content: space-between; align-items: center; gap: 24px; padding: 26px 28px; border-radius: 22px; margin-bottom: 16px; background: linear-gradient(115deg, var(--blanco) 58%, rgba(201,162,39,.12)); }
        .agenda-hero h3, .timeline-card-header h3, .agenda-details-heading h3 { margin: 4px 0 0; font-size: 20px; }
        .agenda-hero p { margin: 7px 0 0; color: var(--gris); }
        .agenda-eyebrow { color: var(--dorado); font-size: 10px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; }
        .agenda-toolbar { display: flex; justify-content: space-between; align-items: center; gap: 18px; padding: 12px 14px; border-radius: 16px; margin-bottom: 16px; }
        .agenda-toolbar .agenda-tabs { margin: 0; }
        .agenda-toolbar .agenda-tab { padding: 9px 16px; border-radius: 9px; }
        .agenda-toolbar .agenda-tab.active { background: var(--texto); }
        .agenda-toolbar .agenda-filter input { width: auto; min-width: 155px; padding: 10px 12px; }
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
        .appointment-modal-footer { display: flex; justify-content: flex-end; gap: 9px; padding: 16px 24px 22px; border-top: 1px solid var(--borde); }
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
        @media (max-width: 520px) { .appointment-modal { padding: 10px; } .appointment-modal-dialog { max-height: calc(100vh - 20px); border-radius: 19px; } .appointment-modal-header, .appointment-modal-body { padding: 18px; } .modal-appointment-heading { grid-template-columns: 52px 1fr; } .modal-appointment-heading > .badge { grid-column: 2; justify-self: start; } .modal-date-icon { width: 52px; height: 52px; } .modal-detail-grid { grid-template-columns: 1fr; } .modal-notes { grid-column: auto; } .appointment-modal-footer { padding: 14px 18px 18px; } }
        @media (max-width: 600px) { .search-form .custom-date-picker, .agenda-filter .custom-date-picker, .month-filter .custom-date-picker { width: 100%; flex-basis: auto; } }
        @media (max-width: 420px) { .custom-calendar, .custom-time-panel { position: fixed; left: 12px; right: 12px; top: 50%; width: auto; transform: translateY(-50%); } .custom-calendar.open, .custom-time-panel.open { animation: none; } .custom-time-panel { max-height: 80vh; } }
        @media (max-width: 900px) { .sidebar { width: 278px; z-index: 1001; } .mobile-overlay { z-index: 1000; } .main { margin-left: 0; width: 100%; padding: 22px; } }
        @media (max-width: 600px) { .main { padding: 18px 14px 30px; } .topbar { align-items: center; } .topbar-info { display: none; } .content-card { padding: 18px; overflow-x: auto; } .content-card > table { margin-left: -18px; margin-right: -18px; width: calc(100% + 36px); } .content-card > table th:first-child, .content-card > table td:first-child { padding-left: 18px; } .content-card > table th:last-child, .content-card > table td:last-child { padding-right: 18px; } .stat-card { padding: 18px; } .dashboard-intro { align-items: stretch; flex-direction: column; } }
    </style>
</head>
<body>

<div id="pwaStatusBar" class="pwa-status-bar" style="display: none;"></div>
<div id="mobileOverlay" class="mobile-overlay" aria-hidden="true"></div>

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
                </div>
            </div>

            <button
                type="button"
                class="mobile-close-btn"
                id="closeSidebar"
                aria-label="Cerrar menú"
                aria-controls="sidebar"
            >✕</button>
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
            <a href="{{ route('estadisticas.index') }}" class="{{ request()->routeIs('estadisticas.*') ? 'active' : '' }}">Reportes</a>
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
        const MOBILE_BREAKPOINT = 900;
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const menuLinks = sidebar ? sidebar.querySelectorAll('.menu a') : [];
        let previousFocus = null;

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
                    label.textContent = 'Día sin horarios disponibles';
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
                const now = new Date();
                const nearestNow = Math.round((now.getHours() * 60 + now.getMinutes()) / step) * step;

                for (let minutes = start; minutes <= end; minutes += step) {
                    const value = toValue(minutes);
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

<button id="installPwaBtn" class="pwa-install-btn" style="display: none;">
    Instalar BarberCore
</button>

<script src="/js/pwa-install.js"></script>
<script src="/js/pwa-status.js"></script>

</body>
</html>
