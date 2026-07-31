const token = localStorage.getItem("barbercore_token");
const usuarioGuardado = JSON.parse(
    localStorage.getItem("barbercore_user") || "null",
);

const message = document.getElementById("message");
const usuariosGrid = document.getElementById("usuariosGrid");
const buscarUsuario = document.getElementById("buscarUsuario");
const filtroRol = document.getElementById("filtroRol");
const filtroEstado = document.getElementById("filtroEstado");
const btnBuscarUsuarios = document.getElementById("btnBuscarUsuarios");
const btnRecargarUsuarios = document.getElementById("btnRecargarUsuarios");
const btnGuardarUsuario = document.getElementById("btnGuardarUsuario");
const btnCancelarEdicion = document.getElementById("btnCancelarEdicion");

const formTitle = document.getElementById("formTitle");
const editandoUsuarioId = document.getElementById("editandoUsuarioId");

const nuevoNombre = document.getElementById("nuevoNombre");
const nuevoCorreo = document.getElementById("nuevoCorreo");
const nuevoRol = document.getElementById("nuevoRol");
const nuevoPassword = document.getElementById("nuevoPassword");
const nuevoPasswordConfirm = document.getElementById("nuevoPasswordConfirm");

if (!token) {
    window.location.href = "/pwa/login";
}

if (usuarioGuardado && usuarioGuardado.rol !== "admin") {
    window.location.href = "/pwa/dashboard";
}

function mostrarMensaje(texto, tipo = "error") {
    message.textContent = texto;
    message.className = tipo === "success" ? "message success" : "message";
    message.style.display = "block";

    setTimeout(() => {
        message.style.display = "none";
    }, 3500);
}

function escaparHtml(texto) {
    return String(texto ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

async function apiRequest(url, options = {}) {
    const response = await fetch(url, {
        ...options,
        headers: {
            Accept: "application/json",
            Authorization: "Bearer " + token,
            ...(options.headers || {}),
        },
    });

    const data = await response.json();

    if (response.status === 401) {
        localStorage.removeItem("barbercore_token");
        localStorage.removeItem("barbercore_user");
        window.location.href = "/pwa/login";
        return;
    }

    if (response.status === 403) {
        window.location.href = "/pwa/dashboard";
        return;
    }

    if (!response.ok) {
        throw new Error(data.message || "Error al consultar la API.");
    }

    return data;
}

function construirUrlUsuarios() {
    const params = new URLSearchParams();

    if (buscarUsuario.value.trim()) {
        params.append("buscar", buscarUsuario.value.trim());
    }

    if (filtroRol.value) {
        params.append("rol", filtroRol.value);
    }

    if (filtroEstado.value !== "") {
        params.append("estado", filtroEstado.value);
    }

    const query = params.toString();
    return query ? `/api/usuarios?${query}` : "/api/usuarios";
}

async function cargarUsuarios() {
    try {
        usuariosGrid.innerHTML =
            '<div class="empty">Cargando usuarios...</div>';

        const data = await apiRequest(construirUrlUsuarios());
        if (!data) return;

        const usuarios = Array.isArray(data.data) ? data.data : [];
        pintarUsuarios(usuarios);
    } catch (error) {
        mostrarMensaje(error.message || "No se pudieron cargar los usuarios.");
        usuariosGrid.innerHTML =
            '<div class="empty">No se pudieron cargar los usuarios.</div>';
    }
}

function pintarUsuarios(usuarios) {
    usuariosGrid.innerHTML = "";

    if (!usuarios.length) {
        usuariosGrid.innerHTML =
            '<div class="empty">No hay usuarios para mostrar.</div>';
        return;
    }

    usuarios.forEach((usuario) => {
        const esAdmin = usuario.rol === "admin";
        const activo = Number(usuario.activo) === 1;

        const card = document.createElement("article");
        card.className = "usuario-card";

        card.innerHTML = `
            <h3>${escaparHtml(usuario.nombre)}</h3>
            <p>${escaparHtml(usuario.correo)}</p>

            <span class="badge ${esAdmin ? "badge-admin" : "badge-barbero"}">
                ${esAdmin ? "Admin" : "Barbero"}
            </span>

            <span class="badge ${activo ? "badge-activo" : "badge-inactivo"}">
                ${activo ? "Activo" : "Inactivo"}
            </span>

            <div class="card-actions" style="margin-top:14px;">
                <button type="button" onclick="editarUsuario(${usuario.id_usuario})">
                    Editar
                </button>

                <button type="button" onclick="toggleEstado(${usuario.id_usuario})" class="${activo ? "danger" : "success"}">
                    ${activo ? "Desactivar" : "Activar"}
                </button>
            </div>
        `;

        usuariosGrid.appendChild(card);
    });
}

function limpiarFormulario() {
    editandoUsuarioId.value = "";
    nuevoNombre.value = "";
    nuevoCorreo.value = "";
    nuevoRol.value = "";
    nuevoPassword.value = "";
    nuevoPasswordConfirm.value = "";

    formTitle.textContent = "Crear usuario";
    btnGuardarUsuario.textContent = "Guardar usuario";
    btnCancelarEdicion.classList.add("hidden");
}

async function editarUsuario(idUsuario) {
    try {
        const data = await apiRequest(`/api/usuarios/${idUsuario}`);
        if (!data) return;

        const usuario = data.data || data.usuario || data;

        editandoUsuarioId.value = usuario.id_usuario;
        nuevoNombre.value = usuario.nombre ?? "";
        nuevoCorreo.value = usuario.correo ?? "";
        nuevoRol.value = usuario.rol ?? "";
        nuevoPassword.value = "";
        nuevoPasswordConfirm.value = "";

        formTitle.textContent = "Editar usuario";
        btnGuardarUsuario.textContent = "Actualizar usuario";
        btnCancelarEdicion.classList.remove("hidden");

        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) {
        mostrarMensaje(error.message || "No se pudo cargar el usuario.");
    }
}

async function guardarUsuario() {
    const idUsuario = editandoUsuarioId.value.trim();
    const nombre = nuevoNombre.value.trim();
    const correo = nuevoCorreo.value.trim();
    const rol = nuevoRol.value;
    const password = nuevoPassword.value;
    const password_confirmation = nuevoPasswordConfirm.value;

    if (!nombre || !correo || !rol) {
        mostrarMensaje("Completa nombre, correo y rol.");
        return;
    }

    try {
        if (idUsuario) {
            const payload = {
                nombre,
                correo,
                rol,
            };

            if (password || password_confirmation) {
                payload.password = password;
                payload.password_confirmation = password_confirmation;
            }

            await apiRequest(`/api/usuarios/${idUsuario}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(payload),
            });

            mostrarMensaje("Usuario actualizado correctamente.", "success");
        } else {
            if (!password || !password_confirmation) {
                mostrarMensaje("Debes capturar y confirmar la contraseña.");
                return;
            }

            await apiRequest("/api/usuarios", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    nombre,
                    correo,
                    rol,
                    password,
                    password_confirmation,
                }),
            });

            mostrarMensaje("Usuario creado correctamente.", "success");
        }

        limpiarFormulario();
        cargarUsuarios();
    } catch (error) {
        mostrarMensaje(error.message || "No se pudo guardar el usuario.");
    }
}

async function toggleEstado(idUsuario) {
    try {
        await apiRequest(`/api/usuarios/${idUsuario}/estado`, {
            method: "PATCH",
        });

        mostrarMensaje(
            "Estado del usuario actualizado correctamente.",
            "success",
        );
        cargarUsuarios();
    } catch (error) {
        mostrarMensaje(error.message || "No se pudo actualizar el estado.");
    }
}

btnBuscarUsuarios.addEventListener("click", cargarUsuarios);
btnRecargarUsuarios.addEventListener("click", cargarUsuarios);
btnGuardarUsuario.addEventListener("click", guardarUsuario);
btnCancelarEdicion.addEventListener("click", limpiarFormulario);

cargarUsuarios();
