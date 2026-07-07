<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - BarberCore PWA</title>

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

        .toolbar,
        .form-box {
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 16px;
            margin-bottom: 18px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
        }

        .toolbar {
            display: grid;
            gap: 12px;
        }

        .toolbar-grid,
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        input,
        select {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #E5E0D6;
            border-radius: 12px;
            padding: 13px 14px;
            font-size: 15px;
            background: #FFFFFF;
        }

        .buttons,
        .form-actions,
        .card-actions {
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

        button.danger {
            background: #C62828;
        }

        button.success {
            background: #2E7D32;
        }

        .usuarios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .usuario-card {
            background: #FFFFFF;
            border: 1px solid #E5E0D6;
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
        }

        .usuario-card h3 {
            margin: 0 0 8px;
            font-size: 20px;
        }

        .usuario-card p {
            margin: 5px 0;
            color: #6B6B6B;
            font-size: 14px;
            line-height: 1.5;
        }

        .badge {
            display: inline-block;
            margin-top: 10px;
            margin-right: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 13px;
        }

        .badge-admin {
            background: rgba(201,162,39,0.16);
            color: #C9A227;
        }

        .badge-barbero {
            background: rgba(28,28,28,0.10);
            color: #1C1C1C;
        }

        .badge-activo {
            background: #E4F6E8;
            color: #2E7D32;
        }

        .badge-inactivo {
            background: #FCE4E4;
            color: #C62828;
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

        .hidden {
            display: none !important;
        }

        @media (max-width: 700px) {
            .container {
                padding: 16px;
            }

            .toolbar-grid,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .header-section h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="brand">
            <div class="logo">BC</div>
            <span>Usuarios</span>
        </div>

        <a href="/pwa/dashboard" class="back-link">Dashboard</a>
    </header>

    <main class="container">
        <div id="message" class="message"></div>

        <section class="header-section">
            <h1>Gestión de usuarios</h1>
            <p>Administra usuarios de BarberCore desde la PWA.</p>
        </section>

        <section class="toolbar">
            <div class="toolbar-grid">
                <input type="text" id="buscarUsuario" placeholder="Buscar por nombre o correo...">

                <select id="filtroRol">
                    <option value="">Todos los roles</option>
                    <option value="admin">Admin</option>
                    <option value="barbero">Barbero</option>
                </select>

                <select id="filtroEstado">
                    <option value="">Todos los estados</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
            </div>

            <div class="buttons">
                <button id="btnBuscarUsuarios">Filtrar</button>
                <button id="btnRecargarUsuarios" class="secondary">Recargar</button>
            </div>
        </section>

        <section class="form-box">
            <h2 id="formTitle" style="margin-top:0;">Crear usuario</h2>

            <input type="hidden" id="editandoUsuarioId">

            <div class="form-grid">
                <input type="text" id="nuevoNombre" placeholder="Nombre completo">
                <input type="email" id="nuevoCorreo" placeholder="Correo electrónico">
                <select id="nuevoRol">
                    <option value="">Selecciona un rol</option>
                    <option value="admin">Admin</option>
                    <option value="barbero">Barbero</option>
                </select>
                <input type="password" id="nuevoPassword" placeholder="Contraseña">
                <input type="password" id="nuevoPasswordConfirm" placeholder="Confirmar contraseña">
            </div>

            <div class="form-actions" style="margin-top:12px;">
                <button id="btnGuardarUsuario">Guardar usuario</button>
                <button id="btnCancelarEdicion" class="secondary hidden" type="button">Cancelar edición</button>
            </div>
        </section>

        <section id="usuariosGrid" class="usuarios-grid">
            <div class="empty">Cargando usuarios...</div>
        </section>
    </main>

    <script src="/js/pwa-usuarios.js"></script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
</html>