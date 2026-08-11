<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#171717">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('code') | @yield('title') - BarberCore</title>
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192.png?v=2">
    <style>
        @font-face {
            font-family: "Material Symbols Outlined";
            font-style: normal;
            font-weight: 400;
            font-display: block;
            src: url("/fonts/material-symbols-barbercore.woff2?v=3") format("woff2");
        }

        :root {
            --gold: #c9a227;
            --gold-dark: #8b6a12;
            --ink: #1c1c1c;
            --paper: #faf8f2;
            --white: #fff;
            --muted: #68645d;
            --border: #e5e0d6;
        }

        * { box-sizing: border-box; }

        .material-symbol {
            display: inline-block;
            font-family: "Material Symbols Outlined";
            font-weight: normal;
            font-style: normal;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            white-space: nowrap;
            direction: ltr;
            font-feature-settings: "liga";
            -webkit-font-feature-settings: "liga";
            -webkit-font-smoothing: antialiased;
        }

        body {
            margin: 0;
            min-width: 320px;
            min-height: 100vh;
            min-height: 100dvh;
            display: grid;
            place-items: center;
            padding: 28px;
            background: var(--paper);
            color: var(--ink);
            font-family: Arial, Helvetica, sans-serif;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0 auto 0 0;
            width: 9px;
            background: var(--gold);
        }

        .error-layout {
            width: min(100%, 880px);
            min-height: 500px;
            display: grid;
            grid-template-columns: minmax(280px, .78fr) minmax(360px, 1.22fr);
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--white);
            box-shadow: 0 28px 72px rgba(28, 28, 28, .13);
        }

        .status-panel {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            padding: 34px;
            background: #171717;
            color: var(--white);
        }

        .status-panel::after {
            content: "@yield('code')";
            position: absolute;
            right: -18px;
            bottom: 48px;
            color: rgba(201, 162, 39, .1);
            font-size: 148px;
            font-weight: 900;
            line-height: 1;
        }

        .brand {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .brand img {
            width: 50px;
            height: 50px;
            padding: 4px;
            border-radius: 8px;
            background: var(--white);
            object-fit: contain;
        }

        .brand strong { display: block; font-size: 20px; }
        .brand span {
            display: block;
            margin-top: 3px;
            color: rgba(255, 255, 255, .55);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        .status-code {
            position: relative;
            z-index: 1;
            color: var(--gold);
            font-size: 72px;
            font-weight: 900;
            line-height: 1;
        }

        .status-label {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, .5);
            font-size: 11px;
        }

        .status-label .material-symbol { color: var(--gold); font-size: 15px; }

        .content-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 54px 58px;
        }

        .error-icon {
            width: 58px;
            height: 58px;
            display: grid;
            place-items: center;
            margin-bottom: 26px;
            border: 1px solid rgba(201, 162, 39, .32);
            border-radius: 8px;
            background: #fffaf0;
            color: var(--gold-dark);
        }

        .error-icon .material-symbol { font-size: 29px; }

        .eyebrow {
            margin-bottom: 9px;
            color: var(--gold-dark);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        h1 { margin: 0; font-size: 38px; line-height: 1.08; }
        .message {
            max-width: 430px;
            margin: 15px 0 30px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.65;
        }

        .actions { display: flex; flex-wrap: wrap; gap: 11px; }
        .action {
            min-height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 20px;
            border: 1px solid var(--ink);
            border-radius: 8px;
            background: var(--ink);
            color: var(--white);
            font: inherit;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(28, 28, 28, .16);
            transition: transform .2s, box-shadow .2s, background .2s;
        }

        .action.secondary {
            border-color: var(--border);
            background: var(--white);
            color: var(--ink);
            box-shadow: none;
        }

        .action:hover { transform: translateY(-2px); box-shadow: 0 16px 28px rgba(28, 28, 28, .2); }
        .action.secondary:hover { background: var(--paper); box-shadow: none; }
        .action:focus-visible { outline: 3px solid rgba(201, 162, 39, .35); outline-offset: 3px; }
        .action .material-symbol { color: var(--gold); font-size: 18px; }
        .action.secondary .material-symbol { color: var(--gold-dark); }

        @media (max-width: 720px) {
            body { padding: 18px; }
            .error-layout { width: calc(100vw - 36px); min-height: 0; grid-template-columns: 1fr; }
            .status-panel { min-height: 94px; flex-direction: row; align-items: center; padding: 18px 22px; }
            .status-panel::after, .status-label { display: none; }
            .status-code { font-size: 42px; }
            .content-panel { padding: 38px 34px 42px; }
            h1 { font-size: 33px; }
        }

        @media (max-width: 440px) {
            body { padding: 12px; }
            body::before { width: 5px; }
            .error-layout { width: calc(100vw - 24px); }
            .status-panel { padding: 15px 18px; }
            .brand img { width: 44px; height: 44px; }
            .brand span { display: none; }
            .content-panel { padding: 30px 24px 32px; }
            .error-icon { margin-bottom: 22px; }
            h1 { font-size: 29px; }
            .actions { display: grid; }
            .action { width: 100%; }
        }

        @media (prefers-reduced-motion: reduce) {
            .action { transition: none; }
        }
    </style>
</head>
<body>
    <main class="error-layout">
        <aside class="status-panel" aria-label="Error @yield('code')">
            <div class="brand">
                <img src="/icons/icon-192.png?v=2" alt="">
                <div>
                    <strong>BarberCore</strong>
                    <span>Gestión para barberías</span>
                </div>
            </div>
            <div class="status-code" aria-hidden="true">@yield('code')</div>
            <div class="status-label">
                <span class="material-symbol" aria-hidden="true">security</span>
                Respuesta segura de BarberCore
            </div>
        </aside>

        <section class="content-panel" aria-labelledby="error-title">
            <div class="error-icon" aria-hidden="true">@yield('icon')</div>
            <span class="eyebrow">@yield('eyebrow')</span>
            <h1 id="error-title">@yield('title')</h1>
            <p class="message">@yield('message')</p>
            <div class="actions">@yield('actions')</div>
        </section>
    </main>
</body>
</html>
