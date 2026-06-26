<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - BarberCore PWA</title>

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

        .back-link {
            text-decoration: none;
            background: #C9A227;
            color: #FFFFFF;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 700;
        }

        .container {
            padding: 22px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .header-section {
            margin-bottom: 20px;
        }

        .header-section h1 {
            margin: 0 0 6px;
            font-size: 26px;
        }

        .header-section p {
            margin: 0;
            color: #6B6B6B;
        }

        .filters {
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 18px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
            display: grid;
            gap: 12px;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #E5E0D6;
            border-radius: 12px;
            padding: 13px 14px;
            font-size: 15px;
        }

        .buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        button {
            border: none;
            background: #C9A227;
            color: #FFFFFF;
            padding: 11px 14px;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        button.secondary {
            background: #1C1C1C;
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

        .summary {
            margin-bottom: 16px;
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 16px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
        }

        .summary strong {
            color: #C9A227;
        }

        .agenda-grid {
            display: grid;
            gap: 14px;
        }

        .cita-card {
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
        }

        .cita-card h3 {
            margin: 0 0 8px;
            font-size: 20px;
        }

        .cita-card p {
            margin: 5px 0;
            color: #6B6B6B;
            font-size: 14px;
        }

        .badge {
            margin-top: 12px;
            display: inline-block;
            padding: 8px 12px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 13px;
        }

        .pendiente {
            background: #FFF6D8;
            color: #A47C00;
        }

        .completada {
            background: #E4F6E8;
            color: #2E7D32;
        }

        .cancelada {
            background: #FCE4E4;
            color: #C62828;
        }

        .empty {
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 24px;
            text-align: center;
            color: #6B6B6B;
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="brand">
            <div class="logo">BC</div>
            <span>Agenda</span>
        </div>

        <a href="/pwa/dashboard" class="back-link">Dashboard</a>
    </header>

    <main class="container">
        <div id="message" class="message"></div>

        <section class="header-section">
            <h1>Agenda BarberCore</h1>
            <p>Consulta citas por día, semana o mes usando la API REST.</p>
        </section>

        <section class="filters">
            <input type="date" id="fechaAgenda">

            <div class="buttons">
                <button id="btnDia">Ver día</button>
                <button id="btnSemana">Ver semana</button>
                <button id="btnMes">Ver mes</button>
                <button id="btnHoy" class="secondary">Hoy</button>
            </div>
        </section>

        <section id="summary" class="summary">
            Selecciona una fecha y un tipo de vista.
        </section>

        <section id="agendaGrid" class="agenda-grid">
            <div class="empty">No se han cargado citas todavía.</div>
        </section>
    </main>

    <script src="/js/pwa-agenda.js"></script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
</html>