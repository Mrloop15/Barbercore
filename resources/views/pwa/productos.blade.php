<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - BarberCore PWA</title>

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
            gap: 12px;
            flex-wrap: wrap;
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

        .toolbar {
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 16px;
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

        .productos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 16px;
        }

        .producto-card {
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
        }

        .producto-image-wrap {
            height: 180px;
            background: linear-gradient(135deg, rgba(201,162,39,0.14), rgba(255,255,255,0.08)), #f7f3eb;
            border-bottom: 1px solid #E5E0D6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .producto-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .producto-placeholder {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            background: rgba(201,162,39,0.16);
            color: #C9A227;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 900;
        }

        .producto-body {
            padding: 18px;
        }

        .producto-card h3 {
            margin: 0 0 8px;
            font-size: 20px;
        }

        .producto-card p {
            margin: 5px 0;
            color: #6B6B6B;
            font-size: 14px;
            line-height: 1.5;
        }

        .precio {
            color: #1C1C1C !important;
            font-weight: 800;
            font-size: 15px !important;
        }

        .stock {
            margin-top: 12px;
            display: inline-block;
            padding: 8px 12px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 13px;
            background: #E4F6E8;
            color: #2E7D32;
        }

        .stock.bajo {
            background: #FCE4E4;
            color: #C62828;
        }

        .stock-form {
            margin-top: 14px;
            display: flex;
            gap: 8px;
        }

        .stock-form input {
            margin: 0;
            padding: 10px;
        }

        .stock-form button {
            white-space: nowrap;
            padding: 10px 12px;
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
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 24px;
            text-align: center;
            color: #6B6B6B;
        }

        @media (max-width: 640px) {
            .container {
                padding: 16px;
            }

            .header-section h1 {
                font-size: 22px;
            }

            .stock-form {
                flex-direction: column;
            }

            .stock-form button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="brand">
            <div class="logo">BC</div>
            <span>Productos</span>
        </div>

        <a href="/pwa/dashboard" class="back-link">Dashboard</a>
    </header>

    <main class="container">
        <div id="message" class="message"></div>

        <section class="header-section">
            <h1>Inventario</h1>
            <p>Consulta productos, stock y actualiza existencias desde la API REST.</p>
        </section>

        <section class="toolbar">
            <input type="text" id="buscarProducto" placeholder="Buscar producto por nombre o descripción...">

            <div class="buttons">
                <button id="btnTodos">Todos los productos</button>
                <button id="btnBajoStock" class="secondary">Bajo stock</button>
            </div>
        </section>

        <section id="productosGrid" class="productos-grid">
            <div class="empty">Cargando productos...</div>
        </section>
    </main>

    <script src="/js/pwa-productos.js"></script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
</html>