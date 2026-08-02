const statusChip = document.getElementById("connectionStatus");
const statusLabel = document.getElementById("connectionStatusLabel");
const RETRY_INTERVAL = 5000;
const REQUEST_TIMEOUT = 4000;
let retryTimer = null;
let checkingConnection = false;

function mostrarEstado(mensaje, tipo) {
    if (!statusChip || !statusLabel) return;

    statusChip.className = "connection-status " + tipo;
    statusChip.title = mensaje;
    statusLabel.textContent = tipo === "online"
        ? "En línea"
        : (tipo === "checking" ? "Comprobando" : "Sin conexión");
}

function iniciarReintentos() {
    if (retryTimer) return;
    retryTimer = setInterval(verificarConexion, RETRY_INTERVAL);
}

function detenerReintentos() {
    if (!retryTimer) return;
    clearInterval(retryTimer);
    retryTimer = null;
}

function marcarSinConexion(mensaje = "Sin conexión. Intentando reconectar…") {
    mostrarEstado(mensaje, "offline");
    iniciarReintentos();
}

async function verificarConexion() {
    if (checkingConnection) return;

    if (!navigator.onLine) {
        marcarSinConexion("Sin conexión a internet. Intentando reconectar…");
        return;
    }

    checkingConnection = true;
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), REQUEST_TIMEOUT);

    try {
        const response = await fetch("/api/status?connection_check=" + Date.now(), {
            cache: "no-store",
            signal: controller.signal,
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        });
        const data = await response.json();

        if (response.ok && data.ok) {
            detenerReintentos();
            mostrarEstado("BarberCore disponible", "online");
        } else {
            marcarSinConexion("BarberCore no está disponible. Reconectando…");
        }
    } catch (error) {
        marcarSinConexion(navigator.onLine
            ? "No se puede conectar con BarberCore. Reconectando…"
            : "Sin conexión a internet. Intentando reconectar…");
    } finally {
        clearTimeout(timeout);
        checkingConnection = false;
    }
}

window.addEventListener("online", () => {
    mostrarEstado("Comprobando conexión con BarberCore…", "checking");
    verificarConexion();
});

window.addEventListener("offline", () => {
    marcarSinConexion("Sin conexión a internet. Intentando reconectar…");
});

window.addEventListener("load", () => {
    if (!navigator.onLine) {
        marcarSinConexion("Sin conexión a internet. Intentando reconectar…");
    } else {
        mostrarEstado("Comprobando conexión con BarberCore…", "checking");
        verificarConexion();
    }
});
