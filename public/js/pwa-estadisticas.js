const token = localStorage.getItem("barbercore_token");

const message = document.getElementById("message");
const inicioInput = document.getElementById("inicio");
const finInput = document.getElementById("fin");
const btnConsultar = document.getElementById("btnConsultar");

const ingresosDia = document.getElementById("ingresosDia");
const ingresosSemana = document.getElementById("ingresosSemana");
const ingresosMes = document.getElementById("ingresosMes");
const productosMes = document.getElementById("productosMes");

const graficaIngresos = document.getElementById("graficaIngresos");
const citasEstado = document.getElementById("citasEstado");
const serviciosList = document.getElementById("serviciosList");
const productosVendidosList = document.getElementById("productosVendidosList");
const productosBajoStockList = document.getElementById(
    "productosBajoStockList",
);
const clientesFrecuentesList = document.getElementById(
    "clientesFrecuentesList",
);
const clientesInactivosList = document.getElementById("clientesInactivosList");

if (!token) {
    window.location.href = "/pwa/login";
}

function formatoFecha(fecha) {
    const year = fecha.getFullYear();
    const month = String(fecha.getMonth() + 1).padStart(2, "0");
    const day = String(fecha.getDate()).padStart(2, "0");

    return `${year}-${month}-${day}`;
}

function configurarFechasIniciales() {
    const hoy = new Date();
    const inicioMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);

    inicioInput.value = formatoFecha(inicioMes);
    finInput.value = formatoFecha(hoy);
}

function mostrarMensaje(texto) {
    message.textContent = texto;
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

async function cargarEstadisticas() {
    const inicio = inicioInput.value;
    const fin = finInput.value;

    if (!inicio || !fin) {
        mostrarMensaje("Selecciona fecha de inicio y fecha de fin.");
        return;
    }

    try {
        graficaIngresos.innerHTML =
            '<div class="empty">Cargando ingresos...</div>';
        serviciosList.innerHTML =
            '<div class="empty">Cargando servicios...</div>';
        productosVendidosList.innerHTML =
            '<div class="empty">Cargando productos...</div>';
        productosBajoStockList.innerHTML =
            '<div class="empty">Cargando bajo stock...</div>';
        clientesFrecuentesList.innerHTML =
            '<div class="empty">Cargando clientes...</div>';
        clientesInactivosList.innerHTML =
            '<div class="empty">Cargando clientes inactivos...</div>';

        const query = `?inicio=${inicio}&fin=${fin}`;

        const [ingresosData, serviciosData, clientesData, productosData] =
            await Promise.all([
                apiGet("/api/estadisticas/ingresos" + query),
                apiGet("/api/estadisticas/servicios" + query),
                apiGet("/api/estadisticas/clientes" + query),
                apiGet("/api/estadisticas/productos" + query),
            ]);

        pintarIngresos(ingresosData);
        pintarServicios(serviciosData.data || []);
        pintarClientes(clientesData);
        pintarProductos(productosData);
    } catch (error) {
        mostrarMensaje(
            error.message || "No se pudieron cargar las estadísticas.",
        );
    }
}

function pintarIngresos(data) {
    const resumen = data.resumen || {};

    ingresosDia.textContent = formatoMoneda(resumen.ingresos_dia || 0);
    ingresosSemana.textContent = formatoMoneda(resumen.ingresos_semana || 0);
    ingresosMes.textContent = formatoMoneda(resumen.ingresos_mes || 0);
    productosMes.textContent = formatoMoneda(resumen.productos_mes || 0);

    pintarGraficaIngresos(data.grafica || []);
}

function pintarGraficaIngresos(grafica) {
    graficaIngresos.innerHTML = "";

    if (!grafica || grafica.length === 0) {
        graficaIngresos.innerHTML =
            '<div class="empty">No hay ingresos en este rango.</div>';
        return;
    }

    const maximo = Math.max(
        ...grafica.map((item) => Number(item.total || 0)),
        1,
    );

    grafica.forEach((item) => {
        const total = Number(item.total || 0);
        const porcentaje = Math.min((total / maximo) * 100, 100);

        const row = document.createElement("div");
        row.className = "bar-row";

        row.innerHTML = `
            <div class="bar-label">
                <span>${item.fecha}</span>
                <strong>${formatoMoneda(total)}</strong>
            </div>
            <div class="bar-bg">
                <div class="bar-fill" style="width: ${porcentaje}%"></div>
            </div>
        `;

        graficaIngresos.appendChild(row);
    });
}

function pintarServicios(servicios) {
    serviciosList.innerHTML = "";

    if (!servicios || servicios.length === 0) {
        serviciosList.innerHTML =
            '<div class="empty">No hay servicios registrados en este rango.</div>';
        return;
    }

    servicios.forEach((servicio) => {
        const item = document.createElement("div");
        item.className = "item";

        item.innerHTML = `
            <strong>${servicio.nombre ?? "Servicio sin nombre"}</strong>
            <small>
                ${servicio.total_servicios ?? 0} servicios · 
                ${formatoMoneda(servicio.ingresos_generados)}
            </small>
        `;

        serviciosList.appendChild(item);
    });
}

function pintarClientes(data) {
    const frecuentes = data.clientes_frecuentes || [];
    const inactivos = data.clientes_inactivos || [];
    const citas = data.citas || {};

    citasEstado.innerHTML = `
        <div class="status-box">
            <strong>${citas.pendientes ?? 0}</strong>
            <span>Pendientes</span>
        </div>
        <div class="status-box">
            <strong>${citas.completadas ?? 0}</strong>
            <span>Completadas</span>
        </div>
        <div class="status-box">
            <strong>${citas.canceladas ?? 0}</strong>
            <span>Canceladas</span>
        </div>
    `;

    clientesFrecuentesList.innerHTML = "";

    if (frecuentes.length === 0) {
        clientesFrecuentesList.innerHTML =
            '<div class="empty">No hay clientes frecuentes en este rango.</div>';
    } else {
        frecuentes.forEach((cliente) => {
            const item = document.createElement("div");
            item.className = "item";

            item.innerHTML = `
                <strong>${cliente.nombre ?? ""} ${cliente.apellido ?? ""}</strong>
                <small>
                    ${cliente.total_visitas ?? 0} visitas · 
                    ${formatoMoneda(cliente.total_gastado_servicios)}
                </small>
            `;

            clientesFrecuentesList.appendChild(item);
        });
    }

    clientesInactivosList.innerHTML = "";

    if (inactivos.length === 0) {
        clientesInactivosList.innerHTML =
            '<div class="empty">No hay clientes inactivos.</div>';
    } else {
        inactivos.forEach((cliente) => {
            const item = document.createElement("div");
            item.className = "item";

            item.innerHTML = `
                <strong>${cliente.nombre ?? ""} ${cliente.apellido ?? ""}</strong>
                <small>
                    Tel: ${cliente.telefono ?? "Sin teléfono"} · 
                    Última visita: ${cliente.ultima_visita ?? "Sin fecha"}
                </small>
            `;

            clientesInactivosList.appendChild(item);
        });
    }
}

function pintarProductos(data) {
    const vendidos = data.productos_vendidos || [];
    const bajoStock = data.productos_bajo_stock || [];

    productosVendidosList.innerHTML = "";

    if (vendidos.length === 0) {
        productosVendidosList.innerHTML =
            '<div class="empty">No hay productos vendidos en este rango.</div>';
    } else {
        vendidos.forEach((producto) => {
            const item = document.createElement("div");
            item.className = "item";

            item.innerHTML = `
                <strong>${producto.nombre ?? "Producto sin nombre"}</strong>
                <small>
                    Vendidos: ${producto.total_vendido ?? 0} · 
                    ${formatoMoneda(producto.ingresos_generados)}
                </small>
            `;

            productosVendidosList.appendChild(item);
        });
    }

    productosBajoStockList.innerHTML = "";

    if (bajoStock.length === 0) {
        productosBajoStockList.innerHTML =
            '<div class="empty">No hay productos con bajo stock.</div>';
    } else {
        bajoStock.forEach((producto) => {
            const item = document.createElement("div");
            item.className = "item";

            item.innerHTML = `
                <strong>${producto.nombre ?? "Producto sin nombre"}</strong>
                <small>
                    Stock: ${producto.stock ?? 0} · 
                    Mínimo: ${producto.stock_minimo ?? 0}
                </small>
            `;

            productosBajoStockList.appendChild(item);
        });
    }
}

btnConsultar.addEventListener("click", cargarEstadisticas);

configurarFechasIniciales();
cargarEstadisticas();
