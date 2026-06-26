let deferredPrompt = null;

const installButton = document.getElementById("installPwaBtn");

window.addEventListener("beforeinstallprompt", (event) => {
    event.preventDefault();

    deferredPrompt = event;

    if (installButton) {
        installButton.style.display = "inline-flex";
    }
});

if (installButton) {
    installButton.addEventListener("click", async () => {
        if (!deferredPrompt) {
            return;
        }

        deferredPrompt.prompt();

        const choiceResult = await deferredPrompt.userChoice;

        if (choiceResult.outcome === "accepted") {
            console.log("El usuario aceptó instalar BarberCore.");
        } else {
            console.log("El usuario canceló la instalación.");
        }

        deferredPrompt = null;
        installButton.style.display = "none";
    });
}

window.addEventListener("appinstalled", () => {
    console.log("BarberCore fue instalado como PWA.");

    if (installButton) {
        installButton.style.display = "none";
    }

    deferredPrompt = null;
});
