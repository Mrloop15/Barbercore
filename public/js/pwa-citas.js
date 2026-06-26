const token = localStorage.getItem("barbercore_token");

const message = document.getElementById("message");
const citaForm = document.getElementById("citaForm");
const citasGrid = document.getElementById("citasGrid");

const selectCliente = document.getElementById("id_cliente");
const selectServicio = document.getElementById("id_servicio");
const fechaInput = document.getElementById("fecha");
const horaInput = document.getElementById("hora_inicio");
const observacionesInput = document.getElementById("observaciones");

const btnTodas = document.getElementById("btnTodas");
const btnPendientes = document.getElementById("btnPendientes");
const btnCompletadas = document.getElementById("btnCompletadas");
const btnCanceladas = document.getElementById("btnCanceladas");

let citasOriginales = [];

if (!token) {
    window.location.href = "/pwa/login";
}

function fechaActual() {
    const hoy = new Date();
    const year = hoy.getFullYear();
    const month = String(hoy.getMonth() + 1).padStart(2, "0");
    const day = String(hoy.getDate()).padStart(2, "0");

    return `${year}-${month}-${day}`;
}

fechaInput.value = fechaActual();

function mostrarMensaje(texto, tipo = "error") {
    message.textContent = texto;
    message.className = tipo === "success" ? "message success" : "message";
    message.style.display = "block";

    setTimeout(() => {
        message.style.display = "none";
    }, 3500);
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

function extraerLista(data) {
    if (Array.isArray(data)) return data;

    if (Array.isArray(data.data)) return data.data;

    if (data.data && Array.isArray(data.data.data)) return data.data.data;

    return [];
}

async function cargarClientes() {
    const data = await apiGet("/api/clientes");
    const clientes = extraerLista(data);

    selectCliente.innerHTML = '<option value="">Selecciona un cliente</option>';

    clientes.forEach((cliente) => {
        const option = document.createElement("option");
        option.value = cliente.id_cliente;
        option.textContent =
            `${cliente.nombre ?? ""} ${cliente.apellido ?? ""}`.trim();
        selectCliente.appendChild(option);
    });
}

async function cargarServicios() {
    const data = await apiGet("/api/servicios");
    const servicios = extraerLista(data);

    selectServicio.innerHTML =
        '<option value="">Selecciona un servicio</option>';

    servicios.forEach((servicio) => {
        const option = document.createElement("option");
        option.value = servicio.id_servicio;
        option.textContent = `${servicio.nombre ?? "Servicio"} - $${Number(servicio.precio || 0).toFixed(2)}`;
        selectServicio.appendChild(option);
    });
}

async function cargarCitas() {
    try {
        citasGrid.innerHTML = '<div class="empty">Cargando citas...</div>';

        const data = await apiGet("/api/citas");

        citasOriginales = extraerLista(data);
        pintarCitas(citasOriginales);
    } catch (error) {
        mostrarMensaje(error.message || "No se pudieron cargar las citas.");
        citasGrid.innerHTML =
            '<div class="empty">No se pudieron cargar las citas.</div>';
    }
}

function pintarCitas(citas) {
    citasGrid.innerHTML = "";

    if (!citas || citas.length === 0) {
        citasGrid.innerHTML =
            '<div class="empty">No hay citas para mostrar.</div>';
        return;
    }

    citas.forEach((cita) => {
        const cliente = cita.cliente
            ? `${cita.cliente.nombre ?? ""} ${cita.cliente.apellido ?? ""}`.trim()
            : "Cliente no disponible";

        const servicio = cita.servicio
            ? cita.servicio.nombre
            : "Servicio no disponible";

        const estado = cita.estado || "pendiente";

        const card = document.createElement("article");
        card.className = "cita-card";

        let acciones = "";

        if (estado === "pendiente") {
            acciones = `
                <div class="card-actions">
                    <button class="success" onclick="completarCita(${cita.id_cita})">Completar</button>
                    <button class="danger" onclick="cancelarCita(${cita.id_cita})">Cancelar</button>
                </div>
            `;
        }

        card.innerHTML = `
            <h3>${cliente || "Cliente sin nombre"}</h3>
            <p><strong>Servicio:</strong> ${servicio}</p>
            <p><strong>Fecha:</strong> ${cita.fecha ?? "Sin fecha"}</p>
            <p><strong>Horario:</strong> ${cita.hora_inicio ?? "--"} - ${cita.hora_fin ?? "--"}</p>
            <p><strong>Precio:</strong> $${Number(cita.precio || 0).toFixed(2)}</p>
            <span class="badge ${estado}">${estado}</span>
            ${acciones}
        `;

        citasGrid.appendChild(card);
    });
}

citaForm.addEventListener("submit", async function (event) {
    event.preventDefault();

    try {
        const response = await fetch("/api/citas", {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                Authorization: "Bearer " + token,
            },
            body: JSON.stringify({
                id_cliente: selectCliente.value,
                id_servicio: selectServicio.value,
                fecha: fechaInput.value,
                hora_inicio: horaInput.value,
                observaciones: observacionesInput.value,
            }),
        });

        const data = await response.json();

        if (response.status === 401) {
            localStorage.removeItem("barbercore_token");
            localStorage.removeItem("barbercore_user");
            window.location.href = "/pwa/login";
            return;
        }

        if (!response.ok) {
            throw new Error(data.message || "No se pudo registrar la cita.");
        }

        mostrarMensaje("Cita registrada correctamente.", "success");
        citaForm.reset();
        fechaInput.value = fechaActual();

        await cargarCitas();
    } catch (error) {
        mostrarMensaje(error.message || "Error al registrar la cita.");
    }
});

async function cancelarCita(idCita) {
    const confirmar = confirm("¿Seguro que quieres cancelar esta cita?");

    if (!confirmar) return;

    try {
        const response = await fetch(`/api/citas/${idCita}/cancelar`, {
            method: "PUT",
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + token,
            },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || "No se pudo cancelar la cita.");
        }

        mostrarMensaje("Cita cancelada correctamente.", "success");
        await cargarCitas();
    } catch (error) {
        mostrarMensaje(error.message || "Error al cancelar la cita.");
    }
}

async function completarCita(idCita) {
    const confirmar = confirm("¿Seguro que quieres completar esta cita?");

    if (!confirmar) return;

    try {
        const response = await fetch(`/api/citas/${idCita}/completar`, {
            method: "PUT",
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + token,
            },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || "No se pudo completar la cita.");
        }

        mostrarMensaje("Cita completada correctamente.", "success");
        await cargarCitas();
    } catch (error) {
        mostrarMensaje(error.message || "Error al completar la cita.");
    }
}

btnTodas.addEventListener("click", () => pintarCitas(citasOriginales));
btnPendientes.addEventListener("click", () =>
    pintarCitas(citasOriginales.filter((cita) => cita.estado === "pendiente")),
);
btnCompletadas.addEventListener("click", () =>
    pintarCitas(citasOriginales.filter((cita) => cita.estado === "completada")),
);
btnCanceladas.addEventListener("click", () =>
    pintarCitas(citasOriginales.filter((cita) => cita.estado === "cancelada")),
);

async function iniciar() {
    try {
        await cargarClientes();
        await cargarServicios();
        await cargarCitas();
    } catch (error) {
        mostrarMensaje(
            error.message || "No se pudo iniciar el módulo de citas.",
        );
    }
}

iniciar();
