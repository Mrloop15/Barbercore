const loginForm = document.getElementById("pwaLoginForm");
const loginMessage = document.getElementById("loginMessage");

const tokenExistente = localStorage.getItem("barbercore_token");

if (tokenExistente) {
    window.location.href = "/pwa/dashboard";
}

loginForm.addEventListener("submit", async function (event) {
    event.preventDefault();

    loginMessage.style.display = "none";
    loginMessage.textContent = "";

    const correo = document.getElementById("correo").value;
    const password = document.getElementById("password").value;

    try {
        const response = await fetch("/api/login", {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                correo: correo,
                password: password,
            }),
        });

        const data = await response.json();

        if (!response.ok || !data.token) {
            loginMessage.textContent =
                data.message || "No se pudo iniciar sesión.";
            loginMessage.style.display = "block";
            return;
        }

        localStorage.setItem("barbercore_token", data.token);
        localStorage.setItem(
            "barbercore_user",
            JSON.stringify(data.usuario || data.user || null),
        );

        window.location.href = "/pwa/dashboard";
    } catch (error) {
        loginMessage.textContent = "No hay conexión con la API.";
        loginMessage.style.display = "block";
    }
});
