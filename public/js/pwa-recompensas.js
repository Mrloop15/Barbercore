const token = localStorage.getItem("barbercore_token");

const message = document.getElementById("message");
const recompensaForm = document.getElementById("recompensaForm");
const recompensasGrid = document.getElementById("recompensasGrid");

const selectCliente = document.getElementById("id_cliente");
const selectRecompensa = document.getElementById("id_recompensa");
const btnCanjear = document.getElementById("btnCanjear");

const nombreInput = document.getElementById("nombre");
const descripcionInput = document.getElementById("descripcion");
const puntosInput = document.getElementById("puntos_requeridos");
const tipoInput = document.getElementById("tipo");
const valorInput = document.getElementById("valor");

let recompensasOriginales = [];

if (!token) {
    window.location.href = "/pwa/login";
}

function mostrarMensaje(texto, tipo = "error") {
    message.textContent = texto;
    message.className = tipo === "success" ? "message success" : "message";
    message.style.display = "block";

    setTimeout(() => {
        message.style.display = "none";
    }, 3500);
}

function formatoMoneda(valor) {
    return Number(valor || 0).toLocaleString("es-MX", {
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
        option.textContent = `${cliente.nombre ?? ""} ${cliente.apellido ?? ""} - ${cliente.puntos ?? 0} pts`;
        selectCliente.appendChild(option);
    });
}

async function cargarRecompensas() {
    const data = await apiGet("/api/recompensas");

    recompensasOriginales = extraerLista(data);

    pintarRecompensas(recompensasOriginales);
    llenarSelectRecompensas(recompensasOriginales);
}

function llenarSelectRecompensas(recompensas) {
    selectRecompensa.innerHTML =
        '<option value="">Selecciona una recompensa</option>';

    recompensas.forEach((recompensa) => {
        const option = document.createElement("option");
        option.value = recompensa.id_recompensa;
        option.textContent = `${recompensa.nombre} - ${recompensa.puntos_requeridos} pts`;
        selectRecompensa.appendChild(option);
    });
}

function pintarRecompensas(recompensas) {
    recompensasGrid.innerHTML = "";

    if (!recompensas || recompensas.length === 0) {
        recompensasGrid.innerHTML =
            '<div class="empty">No hay recompensas disponibles.</div>';
        return;
    }

    recompensas.forEach((recompensa) => {
        const card = document.createElement("article");
        card.className = "recompensa-card";

        let valorTexto = "Sin valor definido";

        if (recompensa.valor !== null && recompensa.valor !== undefined) {
            if (recompensa.tipo === "descuento") {
                valorTexto = formatoMoneda(recompensa.valor);
            } else {
                valorTexto = recompensa.valor;
            }
        }

        card.innerHTML = `
            <h3>${recompensa.nombre ?? "Recompensa sin nombre"}</h3>
            <p><strong>Descripción:</strong> ${recompensa.descripcion ?? "Sin descripción"}</p>
            <p><strong>Tipo:</strong> ${recompensa.tipo ?? "No definido"}</p>
            <p><strong>Valor:</strong> ${valorTexto}</p>
            <span class="points">${recompensa.puntos_requeridos ?? 0} puntos</span>
        `;

        recompensasGrid.appendChild(card);
    });
}

recompensaForm.addEventListener("submit", async function (event) {
    event.preventDefault();

    try {
        const payload = {
            nombre: nombreInput.value,
            descripcion: descripcionInput.value,
            puntos_requeridos: Number(puntosInput.value),
            tipo: tipoInput.value,
            valor: valorInput.value === "" ? null : Number(valorInput.value),
        };

        const response = await fetch("/api/recompensas", {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                Authorization: "Bearer " + token,
            },
            body: JSON.stringify(payload),
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
                data.message || "No se pudo guardar la recompensa.",
            );
        }

        mostrarMensaje("Recompensa registrada correctamente.", "success");

        recompensaForm.reset();

        await cargarRecompensas();
    } catch (error) {
        mostrarMensaje(error.message || "Error al registrar recompensa.");
    }
});

btnCanjear.addEventListener("click", async function () {
    const idCliente = selectCliente.value;
    const idRecompensa = selectRecompensa.value;

    if (!idCliente) {
        mostrarMensaje("Selecciona un cliente.");
        return;
    }

    if (!idRecompensa) {
        mostrarMensaje("Selecciona una recompensa.");
        return;
    }

    const confirmar = confirm("¿Seguro que quieres canjear esta recompensa?");

    if (!confirmar) return;

    try {
        const response = await fetch("/api/recompensas/canjear", {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                Authorization: "Bearer " + token,
            },
            body: JSON.stringify({
                id_cliente: idCliente,
                id_recompensa: idRecompensa,
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
            throw new Error(
                data.message || "No se pudo canjear la recompensa.",
            );
        }

        mostrarMensaje("Recompensa canjeada correctamente.", "success");

        await cargarClientes();
        await cargarRecompensas();
    } catch (error) {
        mostrarMensaje(error.message || "Error al canjear recompensa.");
    }
});

async function iniciar() {
    try {
        await cargarClientes();
        await cargarRecompensas();
    } catch (error) {
        mostrarMensaje(
            error.message || "No se pudo iniciar el módulo de recompensas.",
        );
        recompensasGrid.innerHTML =
            '<div class="empty">No se pudieron cargar las recompensas.</div>';
    }
}

iniciar();
