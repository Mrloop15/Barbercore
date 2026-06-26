<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - BarberCore PWA</title>

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
            max-width: 1200px;
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
            margin-bottom: 20px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 14px;
            align-items: end;
        }

        label {
            display: block;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #E5E0D6;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 15px;
        }

        button {
            border: none;
            background: #C9A227;
            color: #FFFFFF;
            padding: 12px 14px;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .summary-card,
        .card {
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
        }

        .summary-card span {
            color: #6B6B6B;
            font-size: 14px;
        }

        .summary-card strong {
            display: block;
            margin-top: 8px;
            font-size: 28px;
            color: #C9A227;
        }

        .sections-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 18px;
        }

        .card h2 {
            margin-top: 0;
            font-size: 20px;
        }

        .list {
            display: grid;
            gap: 10px;
        }

        .item {
            background: #FAF8F2;
            border: 1px solid #E5E0D6;
            border-radius: 14px;
            padding: 12px;
        }

        .item strong {
            display: block;
            margin-bottom: 4px;
        }

        .item small {
            color: #6B6B6B;
        }

        .bar-row {
            margin-bottom: 12px;
        }

        .bar-label {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .bar-bg {
            height: 12px;
            border-radius: 999px;
            background: #E5E0D6;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            background: #C9A227;
            width: 0%;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .status-box {
            background: #FAF8F2;
            border: 1px solid #E5E0D6;
            border-radius: 14px;
            padding: 12px;
            text-align: center;
        }

        .status-box strong {
            display: block;
            font-size: 24px;
            color: #C9A227;
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

        .empty {
            padding: 18px;
            text-align: center;
            color: #6B6B6B;
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="brand">
            <div class="logo">BC</div>
            <span>Estadísticas</span>
        </div>

        <a href="/pwa/dashboard" class="back-link">Dashboard</a>
    </header>

    <main class="container">
        <div id="message" class="message"></div>

        <section class="header-section">
            <h1>Estadísticas BarberCore</h1>
            <p>Resumen de ingresos, citas, clientes, servicios y productos desde la API REST.</p>
        </section>

        <section class="filters">
            <div>
                <label for="inicio">Fecha inicio</label>
                <input type="date" id="inicio">
            </div>

            <div>
                <label for="fin">Fecha fin</label>
                <input type="date" id="fin">
            </div>

            <div>
                <button id="btnConsultar">Consultar estadísticas</button>
            </div>
        </section>

        <section class="summary-grid">
            <div class="summary-card">
                <span>Ingresos del día</span>
                <strong id="ingresosDia">$0.00</strong>
            </div>

            <div class="summary-card">
                <span>Ingresos de la semana</span>
                <strong id="ingresosSemana">$0.00</strong>
            </div>

            <div class="summary-card">
                <span>Ingresos del mes</span>
                <strong id="ingresosMes">$0.00</strong>
            </div>

            <div class="summary-card">
                <span>Productos del mes</span>
                <strong id="productosMes">$0.00</strong>
            </div>
        </section>

        <section class="sections-grid">
            <div class="card">
                <h2>Ingresos por día</h2>
                <div id="graficaIngresos">
                    <div class="empty">Cargando gráfica...</div>
                </div>
            </div>

            <div class="card">
                <h2>Citas por estado</h2>
                <div id="citasEstado" class="status-grid">
                    <div class="status-box">
                        <strong>0</strong>
                        <span>Pendientes</span>
                    </div>
                    <div class="status-box">
                        <strong>0</strong>
                        <span>Completadas</span>
                    </div>
                    <div class="status-box">
                        <strong>0</strong>
                        <span>Canceladas</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2>Servicios más solicitados</h2>
                <div id="serviciosList" class="list">
                    <div class="empty">Cargando servicios...</div>
                </div>
            </div>

            <div class="card">
                <h2>Productos más vendidos</h2>
                <div id="productosVendidosList" class="list">
                    <div class="empty">Cargando productos...</div>
                </div>
            </div>

            <div class="card">
                <h2>Productos bajo stock</h2>
                <div id="productosBajoStockList" class="list">
                    <div class="empty">Cargando bajo stock...</div>
                </div>
            </div>

            <div class="card">
                <h2>Clientes frecuentes</h2>
                <div id="clientesFrecuentesList" class="list">
                    <div class="empty">Cargando clientes...</div>
                </div>
            </div>

            <div class="card">
                <h2>Clientes inactivos</h2>
                <div id="clientesInactivosList" class="list">
                    <div class="empty">Cargando clientes inactivos...</div>
                </div>
            </div>
        </section>
    </main>

    <script src="/js/pwa-estadisticas.js?v=1"></script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
</html>