const statusBar = document.getElementById("pwaStatusBar");

function mostrarEstado(mensaje, tipo) {
    if (!statusBar) return;

    statusBar.textContent = mensaje;
    statusBar.className = "pwa-status-bar " + tipo;
    statusBar.style.display = "block";

    if (tipo === "online") {
        setTimeout(() => {
            statusBar.style.display = "none";
        }, 3000);
    }
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
            mostrarEstado("Conexión con BarberCore activa", "online");
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
