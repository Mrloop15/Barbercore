<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas - BarberCore PWA</title>

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
        select {
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
            padding: 11px 14px;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        button.danger {
            background: #C62828;
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

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        .cart-table th,
        .cart-table td {
            border-bottom: 1px solid #E5E0D6;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }

        .total-box {
            margin-top: 16px;
            padding: 16px;
            border-radius: 16px;
            background: #FAF8F2;
            border: 1px solid #E5E0D6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 800;
        }

        .total-box strong {
            color: #C9A227;
            font-size: 24px;
        }

        .ventas-grid {
            display: grid;
            gap: 14px;
        }

        .venta-card {
            border: 1px solid #E5E0D6;
            border-radius: 16px;
            padding: 16px;
            background: #FAF8F2;
        }

        .venta-card h3 {
            margin: 0 0 8px;
            font-size: 18px;
        }

        .venta-card p {
            margin: 5px 0;
            color: #6B6B6B;
            font-size: 14px;
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
            <span>Ventas</span>
        </div>

        <a href="/pwa/dashboard" class="back-link">Dashboard</a>
    </header>

    <main class="container">
        <div id="message" class="message"></div>

        <section class="header-section">
            <h1>Venta de productos</h1>
            <p>Registra ventas desde la PWA, descuenta stock y suma puntos al cliente.</p>
        </section>

        <section class="card">
            <h2>Nueva venta</h2>

            <div class="form-grid">
                <div>
                    <label for="id_cliente">Cliente</label>
                    <select id="id_cliente">
                        <option value="">Cliente general</option>
                    </select>
                </div>

                <div>
                    <label for="id_producto">Producto</label>
                    <select id="id_producto">
                        <option value="">Cargando productos...</option>
                    </select>
                </div>

                <div>
                    <label for="cantidad">Cantidad</label>
                    <input type="number" id="cantidad" min="1" value="1">
                </div>
            </div>

            <div class="actions">
                <button id="btnAgregarProducto" type="button">Agregar producto</button>
                <button id="btnGuardarVenta" type="button" class="secondary">Guardar venta</button>
            </div>

            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cant.</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id="carritoBody">
                    <tr>
                        <td colspan="5">No hay productos agregados.</td>
                    </tr>
                </tbody>
            </table>

            <div class="total-box">
                <span>Total</span>
                <strong id="totalVenta">$0.00</strong>
            </div>
        </section>

        <section class="card">
            <h2>Últimas ventas</h2>

            <div id="ventasGrid" class="ventas-grid">
                <div class="empty">Cargando ventas...</div>
            </div>
        </section>
    </main>

    <script src="/js/pwa-ventas.js"></script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
</html>