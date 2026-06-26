<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas - BarberCore PWA</title>

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

        .form-card,
        .list-card {
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 20px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
        }

        .form-card h2,
        .list-card h2 {
            margin-top: 0;
            font-size: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 14px;
        }

        label {
            display: block;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 6px;
        }

        input,
        select,
        textarea {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #E5E0D6;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 15px;
            font-family: Arial, sans-serif;
        }

        textarea {
            resize: vertical;
            min-height: 76px;
        }

        .form-actions {
            margin-top: 14px;
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

        button.danger {
            background: #C62828;
        }

        button.success {
            background: #2E7D32;
        }

        button.secondary {
            background: #1C1C1C;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .citas-grid {
            display: grid;
            gap: 14px;
        }

        .cita-card {
            border: 1px solid #E5E0D6;
            border-radius: 16px;
            padding: 16px;
            background: #FAF8F2;
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
            margin-top: 10px;
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

        .card-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
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

        .message.success {
            background: #2E7D32;
        }

        .empty {
            padding: 20px;
            text-align: center;
            color: #6B6B6B;
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="brand">
            <div class="logo">BC</div>
            <span>Citas</span>
        </div>

        <a href="/pwa/dashboard" class="back-link">Dashboard</a>
    </header>

    <main class="container">
        <div id="message" class="message"></div>

        <section class="header-section">
            <h1>Gestión de citas</h1>
            <p>Consulta, registra, completa o cancela citas desde la API REST.</p>
        </section>

        <section class="form-card">
            <h2>Nueva cita</h2>

            <form id="citaForm">
                <div class="form-grid">
                    <div>
                        <label for="id_cliente">Cliente</label>
                        <select id="id_cliente" required>
                            <option value="">Cargando clientes...</option>
                        </select>
                    </div>

                    <div>
                        <label for="id_servicio">Servicio</label>
                        <select id="id_servicio" required>
                            <option value="">Cargando servicios...</option>
                        </select>
                    </div>

                    <div>
                        <label for="fecha">Fecha</label>
                        <input type="date" id="fecha" required>
                    </div>

                    <div>
                        <label for="hora_inicio">Hora de inicio</label>
                        <input type="time" id="hora_inicio" required>
                    </div>
                </div>

                <div style="margin-top: 14px;">
                    <label for="observaciones">Observaciones</label>
                    <textarea id="observaciones" placeholder="Notas opcionales para la cita..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit">Guardar cita</button>
                </div>
            </form>
        </section>

        <section class="list-card">
            <h2>Citas registradas</h2>

            <div class="filters">
                <button id="btnTodas">Todas</button>
                <button id="btnPendientes" class="secondary">Pendientes</button>
                <button id="btnCompletadas" class="success">Completadas</button>
                <button id="btnCanceladas" class="danger">Canceladas</button>
            </div>

            <div id="citasGrid" class="citas-grid">
                <div class="empty">Cargando citas...</div>
            </div>
        </section>
    </main>

    <script src="/js/pwa-citas.js"></script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
</html>