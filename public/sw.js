const CACHE_NAME = "barbercore-web-v4";
const STATIC_ASSETS = [
    "/offline.html",
    "/manifest.json",
    "/icons/icon-192.png",
    "/icons/icon-512.png",
];

self.addEventListener("install", (event) => {
    event.waitUntil(caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS)));
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((keys) => Promise.all(
            keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key)),
        )),
    );
    self.clients.claim();
});

self.addEventListener("fetch", (event) => {
    const request = event.request;
    const url = new URL(request.url);

    if (request.method !== "GET") {
        return;
    }

    if (url.pathname.startsWith("/api/")) {
        event.respondWith(
            fetch(request).catch(() => new Response(
                JSON.stringify({ ok: false, message: "Sin conexion con la API." }),
                { status: 503, headers: { "Content-Type": "application/json" } },
            )),
        );
        return;
    }

    // No guarda vistas autenticadas ni tokens CSRF de sesiones anteriores.
    if (request.mode === "navigate") {
        event.respondWith(fetch(request).catch(() => caches.match("/offline.html")));
        return;
    }

    // Los recursos estaticos compartidos conservan el mismo aspecto de la web.
    event.respondWith(
        caches.match(request).then((cached) => {
            const network = fetch(request).then((response) => {
                if (response.ok && (url.origin === self.location.origin || response.type === "cors")) {
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, response.clone()));
                }
                return response;
            });

            return cached || network;
        }),
    );
});
