const statusChip = document.getElementById("connectionStatus");
const statusLabel = document.getElementById("connectionStatusLabel");

function mostrarEstado(mensaje, tipo) {
    if (!statusChip || !statusLabel) return;

    statusChip.className = "connection-status " + tipo;
    statusChip.title = mensaje;
    statusLabel.textContent = tipo === "online" ? "En línea" : "Sin conexión";
}

async function verificarApi() {
    try {
        const response = await fetch("/api/status", {
            headers: {
                Accept: "application/json",
            },
        });

        const data = await response.json();

        if (response.ok && data.ok) {
            mostrarEstado("BarberCore disponible", "online");
        } else {
            mostrarEstado("La API no respondió correctamente", "offline");
        }
    } catch (error) {
        mostrarEstado("Sin conexión con BarberCore", "offline");
    }
}

window.addEventListener("online", () => {
    verificarApi();
});

window.addEventListener("offline", () => {
    mostrarEstado("Sin conexión a internet", "offline");
});

window.addEventListener("load", () => {
    if (!navigator.onLine) {
        mostrarEstado("Sin conexión a internet", "offline");
    } else {
        verificarApi();
    }
});
