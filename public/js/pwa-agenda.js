const token = localStorage.getItem("barbercore_token");

const message = document.getElementById("message");
const fechaAgenda = document.getElementById("fechaAgenda");
const agendaGrid = document.getElementById("agendaGrid");
const summary = document.getElementById("summary");

const btnDia = document.getElementById("btnDia");
const btnSemana = document.getElementById("btnSemana");
const btnMes = document.getElementById("btnMes");
const btnHoy = document.getElementById("btnHoy");

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

fechaAgenda.value = fechaActual();

function mostrarMensaje(texto) {
    message.textContent = texto;
    message.style.display = "block";
}

function limpiarMensaje() {
    message.textContent = "";
    message.style.display = "none";
}

async function consultarAgenda(tipo) {
    limpiarMensaje();

    const fecha = fechaAgenda.value || fechaActual();

    try {
        agendaGrid.innerHTML = '<div class="empty">Cargando citas...</div>';

        const response = await fetch(`/api/agenda/${tipo}?fecha=${fecha}`, {
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
            throw new Error(data.message || "No se pudo cargar la agenda.");
        }

        pintarResumen(data);
        pintarCitas(data.data || []);
    } catch (error) {
        mostrarMensaje(error.message || "Error al consultar la API.");
        agendaGrid.innerHTML =
            '<div class="empty">No se pudieron cargar las citas.</div>';
    }
}

function pintarResumen(data) {
    if (data.tipo === "dia") {
        summary.innerHTML = `
            Vista: <strong>Día</strong><br>
            Fecha: <strong>${data.fecha}</strong><br>
            Total de citas: <strong>${data.total}</strong>
        `;
        return;
    }

    if (data.tipo === "semana") {
        summary.innerHTML = `
            Vista: <strong>Semana</strong><br>
            Del <strong>${data.inicio}</strong> al <strong>${data.fin}</strong><br>
            Total de citas: <strong>${data.total}</strong>
        `;
        return;
    }

    if (data.tipo === "mes") {
        summary.innerHTML = `
            Vista: <strong>Mes</strong><br>
            Del <strong>${data.inicio}</strong> al <strong>${data.fin}</strong><br>
            Total de citas: <strong>${data.total}</strong>
        `;
        return;
    }

    summary.innerHTML = "Agenda cargada.";
}

function pintarCitas(citas) {
    agendaGrid.innerHTML = "";

    if (!citas || citas.length === 0) {
        agendaGrid.innerHTML =
            '<div class="empty">No hay citas en este rango.</div>';
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

        card.innerHTML = `
            <h3>${cliente || "Cliente sin nombre"}</h3>
            <p><strong>Servicio:</strong> ${servicio}</p>
            <p><strong>Fecha:</strong> ${cita.fecha ?? "Sin fecha"}</p>
            <p><strong>Horario:</strong> ${cita.hora_inicio ?? "--"} - ${cita.hora_fin ?? "--"}</p>
            <p><strong>Precio:</strong> $${Number(cita.precio || 0).toFixed(2)}</p>
            <span class="badge ${estado}">${estado}</span>
        `;

        agendaGrid.appendChild(card);
    });
}

btnDia.addEventListener("click", () => consultarAgenda("dia"));
btnSemana.addEventListener("click", () => consultarAgenda("semana"));
btnMes.addEventListener("click", () => consultarAgenda("mes"));

btnHoy.addEventListener("click", () => {
    fechaAgenda.value = fechaActual();
    consultarAgenda("dia");
});

consultarAgenda("dia");
