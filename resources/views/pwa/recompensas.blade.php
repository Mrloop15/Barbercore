<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recompensas - BarberCore PWA</title>

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

        .card {
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 20px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
        }

        .card h2 {
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

        .actions {
            margin-top: 14px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .recompensas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 16px;
        }

        .recompensa-card {
            border: 1px solid #E5E0D6;
            border-radius: 16px;
            padding: 16px;
            background: #FAF8F2;
        }

        .recompensa-card h3 {
            margin: 0 0 8px;
            font-size: 20px;
        }

        .recompensa-card p {
            margin: 5px 0;
            color: #6B6B6B;
            font-size: 14px;
        }

        .points {
            margin-top: 12px;
            display: inline-block;
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            color: #C9A227;
            padding: 8px 12px;
            border-radius: 999px;
            font-weight: 800;
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
            <span>Recompensas</span>
        </div>

        <a href="/pwa/dashboard" class="back-link">Dashboard</a>
    </header>

    <main class="container">
        <div id="message" class="message"></div>

        <section class="header-section">
            <h1>Programa de recompensas</h1>
            <p>Consulta recompensas, crea nuevas opciones y canjea puntos desde la PWA.</p>
        </section>

        <section class="card">
            <h2>Nueva recompensa</h2>

            <form id="recompensaForm">
                <div class="form-grid">
                    <div>
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" required placeholder="Ej. Descuento de $50">
                    </div>

                    <div>
                        <label for="puntos_requeridos">Puntos requeridos</label>
                        <input type="number" id="puntos_requeridos" min="1" required>
                    </div>

                    <div>
                        <label for="tipo">Tipo</label>
                        <select id="tipo" required>
                            <option value="descuento">Descuento</option>
                            <option value="servicio">Servicio</option>
                            <option value="producto">Producto</option>
                            <option value="premium">Premium</option>
                        </select>
                    </div>

                    <div>
                        <label for="valor">Valor</label>
                        <input type="number" id="valor" min="0" step="0.01" placeholder="Ej. 50">
                    </div>
                </div>

                <div style="margin-top: 14px;">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" placeholder="Describe brevemente la recompensa..."></textarea>
                </div>

                <div class="actions">
                    <button type="submit">Guardar recompensa</button>
                </div>
            </form>
        </section>

        <section class="card">
            <h2>Canjear recompensa</h2>

            <div class="form-grid">
                <div>
                    <label for="id_cliente">Cliente</label>
                    <select id="id_cliente">
                        <option value="">Cargando clientes...</option>
                    </select>
                </div>

                <div>
                    <label for="id_recompensa">Recompensa</label>
                    <select id="id_recompensa">
                        <option value="">Cargando recompensas...</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <button id="btnCanjear" type="button" class="secondary">Canjear puntos</button>
            </div>
        </section>

        <section class="card">
            <h2>Recompensas disponibles</h2>

            <div id="recompensasGrid" class="recompensas-grid">
                <div class="empty">Cargando recompensas...</div>
            </div>
        </section>
    </main>

    <script src="/js/pwa-recompensas.js"></script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
</html>