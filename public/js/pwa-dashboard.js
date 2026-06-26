const token = localStorage.getItem("barbercore_token");
const message = document.getElementById("message");

if (!token) {
    window.location.href = "/pwa/login";
}

function mostrarMensaje(texto) {
    message.textContent = texto;
    message.style.display = "block";
}

function formatoMoneda(valor) {
    const numero = Number(valor || 0);

    return numero.toLocaleString("es-MX", {
        style: "currency",
        currency: "MXN",
    });
}

async function apiGet(url) {
    const response = await fetch(url, {
        headers: {
            Accept: "application/json",
            Authorization: "Bearer " + token,
        },
    });

    const data = await response.json();

    if (response.status === 401) {
        localStorage.removeItem("barbercore_token");
        localStorage.removeItem("barbercore_user");
        window.location.href = "/pwa/login";
        return;
    }

    if (!response.ok) {
        throw new Error(data.message || "Error al consultar la API.");
    }

    return data;
}

async function cargarUsuario() {
    const data = await apiGet("/api/me");

    const usuario = data.usuario || data.user || data.data || data;

    if (usuario && usuario.nombre) {
        document.getElementById("welcomeTitle").textContent =
            "Hola, " + usuario.nombre;
    }
}

async function cargarDashboard() {
    const data = await apiGet("/api/dashboard/resumen");

    const resumen = data.data || data.resumen || data;

    document.getElementById("totalClientes").textContent =
        resumen.total_clientes ??
        resumen.clientes ??
        resumen.clientes_registrados ??
        0;

    document.getElementById("citasHoy").textContent =
        resumen.citas_hoy ?? resumen.citas_del_dia ?? 0;

    document.getElementById("citasPendientes").textContent =
        resumen.citas_pendientes ?? 0;

    document.getElementById("citasCompletadas").textContent =
        resumen.citas_completadas ?? 0;

    document.getElementById("ingresosDia").textContent = formatoMoneda(
        resumen.ingresos_dia ?? 0,
    );

    document.getElementById("ingresosMes").textContent = formatoMoneda(
        resumen.ingresos_mes ?? 0,
    );

    pintarProximasCitas(resumen.proximas_citas || resumen.proximasCitas || []);
}

function pintarProximasCitas(citas) {
    const contenedor = document.getElementById("proximasCitas");
    contenedor.innerHTML = "";

    if (!citas || citas.length === 0) {
        contenedor.innerHTML =
            '<div class="item">No hay próximas citas registradas.</div>';
        return;
    }

    citas.forEach((cita) => {
        const cliente = cita.cliente
            ? `${cita.cliente.nombre ?? ""} ${cita.cliente.apellido ?? ""}`.trim()
            : "Cliente no disponible";

        const servicio = cita.servicio
            ? cita.servicio.nombre
            : "Servicio no disponible";

        const item = document.createElement("div");
        item.className = "item";

        item.innerHTML = `
            <strong>${cliente}</strong>
            <small>${cita.fecha ?? ""} ${cita.hora_inicio ?? ""} · ${servicio}</small>
        `;

        contenedor.appendChild(item);
    });
}

document.getElementById("logoutBtn").addEventListener("click", async () => {
    try {
        await fetch("/api/logout", {
            method: "POST",
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + token,
            },
        });
    } catch (error) {
        console.log("No se pudo cerrar sesión en API, se limpiará localmente.");
    }

    localStorage.removeItem("barbercore_token");
    localStorage.removeItem("barbercore_user");

    window.location.href = "/pwa/login";
});

async function iniciarDashboard() {
    try {
        await cargarUsuario();
        await cargarDashboard();
    } catch (error) {
        mostrarMensaje(error.message || "No se pudo cargar el dashboard.");
    }
}

iniciarDashboard();
