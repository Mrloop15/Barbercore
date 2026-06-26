const token = localStorage.getItem("barbercore_token");
const message = document.getElementById("message");
const clientesGrid = document.getElementById("clientesGrid");
const buscarCliente = document.getElementById("buscarCliente");

let clientesOriginales = [];

if (!token) {
    window.location.href = "/pwa/login";
}

function mostrarMensaje(texto) {
    message.textContent = texto;
    message.style.display = "block";
}

async function cargarClientes() {
    try {
        const response = await fetch("/api/clientes", {
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
            throw new Error(
                data.message || "No se pudieron cargar los clientes.",
            );
        }

        clientesOriginales = extraerClientes(data);
        pintarClientes(clientesOriginales);
    } catch (error) {
        mostrarMensaje(error.message || "Error al consultar la API.");
        clientesGrid.innerHTML =
            '<div class="empty">No se pudieron cargar los clientes.</div>';
    }
}

function extraerClientes(data) {
    if (Array.isArray(data)) {
        return data;
    }

    if (Array.isArray(data.data)) {
        return data.data;
    }

    if (data.data && Array.isArray(data.data.data)) {
        return data.data.data;
    }

    if (data.clientes && Array.isArray(data.clientes)) {
        return data.clientes;
    }

    return [];
}

function pintarClientes(clientes) {
    clientesGrid.innerHTML = "";

    if (!clientes || clientes.length === 0) {
        clientesGrid.innerHTML =
            '<div class="empty">No hay clientes registrados.</div>';
        return;
    }

    clientes.forEach((cliente) => {
        const nombreCompleto =
            `${cliente.nombre ?? ""} ${cliente.apellido ?? ""}`.trim();

        const card = document.createElement("article");
        card.className = "cliente-card";

        card.innerHTML = `
            <h3>${nombreCompleto || "Cliente sin nombre"}</h3>
            <p><strong>Teléfono:</strong> ${cliente.telefono ?? "No registrado"}</p>
            <p><strong>Cumpleaños:</strong> ${cliente.cumpleanos ?? "No registrado"}</p>
            <p><strong>Última visita:</strong> ${cliente.ultima_visita ?? "Sin visitas"}</p>
            <span class="points">${cliente.puntos ?? 0} puntos</span>
        `;

        clientesGrid.appendChild(card);
    });
}

buscarCliente.addEventListener("input", function () {
    const texto = this.value.toLowerCase().trim();

    const filtrados = clientesOriginales.filter((cliente) => {
        const nombre =
            `${cliente.nombre ?? ""} ${cliente.apellido ?? ""}`.toLowerCase();
        const telefono = `${cliente.telefono ?? ""}`.toLowerCase();

        return nombre.includes(texto) || telefono.includes(texto);
    });

    pintarClientes(filtrados);
});

cargarClientes();
