const token = localStorage.getItem("barbercore_token");

const message = document.getElementById("message");
const selectCliente = document.getElementById("id_cliente");
const selectProducto = document.getElementById("id_producto");
const cantidadInput = document.getElementById("cantidad");
const carritoBody = document.getElementById("carritoBody");
const totalVenta = document.getElementById("totalVenta");
const ventasGrid = document.getElementById("ventasGrid");

const btnAgregarProducto = document.getElementById("btnAgregarProducto");
const btnGuardarVenta = document.getElementById("btnGuardarVenta");

let productos = [];
let carrito = [];

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

    selectCliente.innerHTML = '<option value="">Cliente general</option>';

    clientes.forEach((cliente) => {
        const option = document.createElement("option");
        option.value = cliente.id_cliente;
        option.textContent =
            `${cliente.nombre ?? ""} ${cliente.apellido ?? ""}`.trim();
        selectCliente.appendChild(option);
    });
}

async function cargarProductos() {
    const data = await apiGet("/api/productos");
    productos = extraerLista(data);

    selectProducto.innerHTML =
        '<option value="">Selecciona un producto</option>';

    productos.forEach((producto) => {
        const option = document.createElement("option");
        option.value = producto.id_producto;
        option.textContent = `${producto.nombre} - ${formatoMoneda(producto.precio_venta)} | Stock: ${producto.stock}`;
        selectProducto.appendChild(option);
    });
}

function pintarCarrito() {
    carritoBody.innerHTML = "";

    if (carrito.length === 0) {
        carritoBody.innerHTML = `
            <tr>
                <td colspan="5">No hay productos agregados.</td>
            </tr>
        `;
        totalVenta.textContent = formatoMoneda(0);
        return;
    }

    let total = 0;

    carrito.forEach((item, index) => {
        const subtotal = item.precio_venta * item.cantidad;
        total += subtotal;

        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>${item.nombre}</td>
            <td>${item.cantidad}</td>
            <td>${formatoMoneda(item.precio_venta)}</td>
            <td>${formatoMoneda(subtotal)}</td>
            <td>
                <button class="danger" onclick="eliminarDelCarrito(${index})">Quitar</button>
            </td>
        `;

        carritoBody.appendChild(tr);
    });

    totalVenta.textContent = formatoMoneda(total);
}

function agregarProducto() {
    const idProducto = Number(selectProducto.value);
    const cantidad = Number(cantidadInput.value);

    if (!idProducto) {
        mostrarMensaje("Selecciona un producto.");
        return;
    }

    if (!cantidad || cantidad < 1) {
        mostrarMensaje("La cantidad debe ser mayor a 0.");
        return;
    }

    const producto = productos.find(
        (item) => Number(item.id_producto) === idProducto,
    );

    if (!producto) {
        mostrarMensaje("Producto no encontrado.");
        return;
    }

    if (cantidad > Number(producto.stock)) {
        mostrarMensaje("No hay stock suficiente para este producto.");
        return;
    }

    const existente = carrito.find(
        (item) => Number(item.id_producto) === idProducto,
    );

    if (existente) {
        const nuevaCantidad = existente.cantidad + cantidad;

        if (nuevaCantidad > Number(producto.stock)) {
            mostrarMensaje("No hay stock suficiente para sumar esa cantidad.");
            return;
        }

        existente.cantidad = nuevaCantidad;
    } else {
        carrito.push({
            id_producto: producto.id_producto,
            nombre: producto.nombre,
            precio_venta: Number(producto.precio_venta),
            stock: Number(producto.stock),
            cantidad: cantidad,
        });
    }

    cantidadInput.value = 1;
    pintarCarrito();
}

function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    pintarCarrito();
}

async function guardarVenta() {
    if (carrito.length === 0) {
        mostrarMensaje("Agrega al menos un producto a la venta.");
        return;
    }

    const productosVenta = carrito.map((item) => ({
        id_producto: item.id_producto,
        cantidad: item.cantidad,
    }));

    const payload = {
        id_cliente: selectCliente.value || null,
        productos: productosVenta,
    };

    try {
        const response = await fetch("/api/ventas-productos", {
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
            throw new Error(data.message || "No se pudo registrar la venta.");
        }

        mostrarMensaje("Venta registrada correctamente.", "success");

        carrito = [];
        pintarCarrito();

        await cargarProductos();
        await cargarVentas();
    } catch (error) {
        mostrarMensaje(error.message || "Error al registrar la venta.");
    }
}

async function cargarVentas() {
    try {
        const data = await apiGet("/api/ventas-productos");
        const ventas = extraerLista(data);

        pintarVentas(ventas);
    } catch (error) {
        ventasGrid.innerHTML =
            '<div class="empty">No se pudieron cargar las ventas.</div>';
    }
}

function pintarVentas(ventas) {
    ventasGrid.innerHTML = "";

    if (!ventas || ventas.length === 0) {
        ventasGrid.innerHTML =
            '<div class="empty">No hay ventas registradas.</div>';
        return;
    }

    ventas.forEach((venta) => {
        const cliente = venta.cliente
            ? `${venta.cliente.nombre ?? ""} ${venta.cliente.apellido ?? ""}`.trim()
            : "Cliente general";

        let detalles = "";

        if (venta.detalles && venta.detalles.length > 0) {
            detalles = venta.detalles
                .map((detalle) => {
                    const nombreProducto = detalle.producto
                        ? detalle.producto.nombre
                        : "Producto no disponible";

                    return `${nombreProducto} x${detalle.cantidad}`;
                })
                .join(", ");
        } else {
            detalles = "Sin detalle disponible";
        }

        const card = document.createElement("article");
        card.className = "venta-card";

        card.innerHTML = `
            <h3>${cliente}</h3>
            <p><strong>Total:</strong> ${formatoMoneda(venta.total)}</p>
            <p><strong>Fecha:</strong> ${venta.fecha_venta ?? venta.created_at ?? "Sin fecha"}</p>
            <p><strong>Productos:</strong> ${detalles}</p>
        `;

        ventasGrid.appendChild(card);
    });
}

btnAgregarProducto.addEventListener("click", agregarProducto);
btnGuardarVenta.addEventListener("click", guardarVenta);

async function iniciar() {
    try {
        await cargarClientes();
        await cargarProductos();
        await cargarVentas();
        pintarCarrito();
    } catch (error) {
        mostrarMensaje(
            error.message || "No se pudo iniciar el módulo de ventas.",
        );
    }
}

iniciar();
