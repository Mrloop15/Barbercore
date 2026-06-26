const token = localStorage.getItem("barbercore_token");

const message = document.getElementById("message");
const productosGrid = document.getElementById("productosGrid");
const buscarProducto = document.getElementById("buscarProducto");
const btnTodos = document.getElementById("btnTodos");
const btnBajoStock = document.getElementById("btnBajoStock");

let productosOriginales = [];

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

function extraerProductos(data) {
    if (Array.isArray(data)) return data;

    if (Array.isArray(data.data)) return data.data;

    if (data.data && Array.isArray(data.data.data)) return data.data.data;

    if (data.productos && Array.isArray(data.productos)) return data.productos;

    return [];
}

async function cargarProductos() {
    try {
        productosGrid.innerHTML =
            '<div class="empty">Cargando productos...</div>';

        const data = await apiGet("/api/productos");

        productosOriginales = extraerProductos(data);
        pintarProductos(productosOriginales);
    } catch (error) {
        mostrarMensaje(error.message || "No se pudieron cargar los productos.");
        productosGrid.innerHTML =
            '<div class="empty">No se pudieron cargar los productos.</div>';
    }
}

async function cargarBajoStock() {
    try {
        productosGrid.innerHTML =
            '<div class="empty">Cargando productos con bajo stock...</div>';

        const data = await apiGet("/api/productos/bajo-stock");

        productosOriginales = extraerProductos(data);
        pintarProductos(productosOriginales);
    } catch (error) {
        mostrarMensaje(
            error.message ||
                "No se pudieron cargar los productos con bajo stock.",
        );
        productosGrid.innerHTML =
            '<div class="empty">No se pudieron cargar los productos con bajo stock.</div>';
    }
}

function pintarProductos(productos) {
    productosGrid.innerHTML = "";

    if (!productos || productos.length === 0) {
        productosGrid.innerHTML =
            '<div class="empty">No hay productos para mostrar.</div>';
        return;
    }

    productos.forEach((producto) => {
        const stock = Number(producto.stock || 0);
        const stockMinimo = Number(producto.stock_minimo || 0);
        const esBajo = stock <= stockMinimo;

        const card = document.createElement("article");
        card.className = "producto-card";

        card.innerHTML = `
            <h3>${producto.nombre ?? "Producto sin nombre"}</h3>
            <p><strong>Descripción:</strong> ${producto.descripcion ?? "Sin descripción"}</p>
            <p><strong>Precio compra:</strong> ${formatoMoneda(producto.precio_compra)}</p>
            <p><strong>Precio venta:</strong> ${formatoMoneda(producto.precio_venta)}</p>
            <p><strong>Stock mínimo:</strong> ${stockMinimo}</p>

            <span class="stock ${esBajo ? "bajo" : ""}">
                Stock actual: ${stock}
            </span>

            <div class="stock-form">
                <input type="number" min="0" value="${stock}" id="stock-${producto.id_producto}">
                <button onclick="actualizarStock(${producto.id_producto})">Guardar</button>
            </div>
        `;

        productosGrid.appendChild(card);
    });
}

async function actualizarStock(idProducto) {
    const input = document.getElementById("stock-" + idProducto);
    const nuevoStock = Number(input.value);

    if (nuevoStock < 0 || Number.isNaN(nuevoStock)) {
        mostrarMensaje("El stock debe ser un número válido.");
        return;
    }

    try {
        const response = await fetch(`/api/productos/${idProducto}/stock`, {
            method: "PATCH",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                Authorization: "Bearer " + token,
            },
            body: JSON.stringify({
                stock: nuevoStock,
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
            throw new Error(data.message || "No se pudo actualizar el stock.");
        }

        mostrarMensaje("Stock actualizado correctamente.", "success");
        cargarProductos();
    } catch (error) {
        mostrarMensaje(error.message || "Error al actualizar stock.");
    }
}

buscarProducto.addEventListener("input", function () {
    const texto = this.value.toLowerCase().trim();

    const filtrados = productosOriginales.filter((producto) => {
        const nombre = `${producto.nombre ?? ""}`.toLowerCase();
        const descripcion = `${producto.descripcion ?? ""}`.toLowerCase();

        return nombre.includes(texto) || descripcion.includes(texto);
    });

    pintarProductos(filtrados);
});

btnTodos.addEventListener("click", cargarProductos);
btnBajoStock.addEventListener("click", cargarBajoStock);

cargarProductos();
