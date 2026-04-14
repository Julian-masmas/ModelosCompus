const API_BASE = '/API';
console.log('API_BASE:', API_BASE);

function escapeHtml(str = '') {
    return String(str)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');
}

async function cargarFechasVentas(idCliente) {
    const res = await fetch(`${API_BASE}/getClientPurchases.php?id_cliente=${encodeURIComponent(idCliente)}`);
    return res.json();
}

async function cargarClientes() {
    const response = await fetch(`${API_BASE}/getClients.php`);
    const text = await response.text();
    // const data = await response.json();
    console.log(text);

    let data;
    try {
        data = JSON.parse(text);
    } catch (e) {
        console.error("Error JSON:", text);
        return;
    }

    // const response = await fetch(`${API_BASE}/getClients.php`);
    const tabla = document.getElementById('tablaClientes');
    tabla.innerHTML = '';

    for (const client of data) {
        const ventas = await cargarFechasVentas(client.id_cliente);
        const opciones = ventas.length ?
            ventas.map(v => `<option value="${v.fecha_compra}">${v.fecha_compra}</option>`).join('') :
            '<option value="">Sin compras</option>';

        const fila = document.createElement('tr');
        fila.innerHTML = `
                    <td>${client.id_cliente}</td>
                    <td>${escapeHtml(client.name)}</td>
                    <td>${escapeHtml(client.correo)}</td>
                    <td>${escapeHtml(client.celular)}</td>
                    <td>
                        <select class="venta-select" onchange="verDetalleCompra(${client.id_cliente}, this.value)">
                            <option value="">Seleccione fecha</option>
                            ${opciones}
                        </select>
                    </td>
                    <td>
                        <button title="Editar" onclick="abrirModalEditar(${client.id_cliente}, '${escapeHtml(client.name)}', '${escapeHtml(client.correo)}', '${escapeHtml(client.celular)}')">✏</button>
                        <button title="Ver" onclick="verCliente(${client.id_cliente})">👁</button>
                        <button title="Eliminar" onclick="eliminarCliente(${client.id_cliente})">🗑</button>
                    </td>
                `;
        tabla.appendChild(fila);
    }
}

async function verDetalleCompra(idCliente, fechaCompra) {
    if (!fechaCompra) return;
    const response = await fetch(`${API_BASE}/getPurchaseDetails.php?id_cliente=${encodeURIComponent(idCliente)}&fecha_compra=${encodeURIComponent(fechaCompra)}`);
    const result = await response.json();
    const panel = document.getElementById('panelDetalleCompra');

    if (!result.items || !result.items.length) {
        panel.innerHTML = '<h2>Detalle de compra</h2><p>No hay detalles para esta fecha.</p>';
        return;
    }

    panel.innerHTML = `
                <h2>Detalle de compra (${escapeHtml(fechaCompra)})</h2>
                <p><strong>Cliente:</strong> ${escapeHtml(result.cliente.name)} (${escapeHtml(result.cliente.correo)})</p>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${result.items.map(item => `
                            <tr>
                                <td>${escapeHtml(item.producto)}</td>
                                <td>${escapeHtml(item.categoria)}</td>
                                <td>${item.cantidad}</td>
                                <td>$${Number(item.precio_unitario).toFixed(2)}</td>
                                <td>$${Number(item.subtotal).toFixed(2)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
                <p><strong>Monto total:</strong> $${Number(result.total).toFixed(2)}</p>
            `;
}

document.getElementById('searchInput').addEventListener('keyup', function () {
    const searchText = this.value.toLowerCase();
    document.querySelectorAll('#tablaClientes tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(searchText) ? '' : 'none';
    });
});

function verCliente(id) {
    fetch(`${API_BASE}/getClientById.php?id=${id}`)
        .then(response => response.json())
        .then(client => {
            document.getElementById('detallesCliente').innerHTML = `
                        <p><strong>ID:</strong> ${client.id_cliente}</p>
                        <p><strong>Nombre:</strong> ${escapeHtml(client.name)}</p>
                        <p><strong>Email:</strong> ${escapeHtml(client.correo)}</p>
                        <p><strong>Celular:</strong> ${escapeHtml(client.celular)}</p>`;
            document.getElementById('modalVer').style.display = 'block';
        });
}

function abrirModalAgregar() {
    document.getElementById('modalTitulo').textContent = 'Agregar Cliente';
    document.getElementById('clienteId').value = '';
    document.getElementById('nombre').value = '';
    document.getElementById('email').value = '';
    document.getElementById('celular').value = '';
    document.getElementById('modalEditar').style.display = 'block';
}

function abrirModalEditar(id, nombre, email, celular) {
    document.getElementById('modalTitulo').textContent = 'Editar Cliente';
    document.getElementById('clienteId').value = id;
    document.getElementById('nombre').value = nombre;
    document.getElementById('email').value = email;
    document.getElementById('celular').value = celular;
    document.getElementById('modalEditar').style.display = 'block';
}

function cerrarModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function eliminarCliente(id) {
    if (!confirm('¿Estás seguro de que quieres eliminar este cliente?')) return;
    fetch(`${API_BASE}/deleteClient.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_cliente: id })
    }).then(() => cargarClientes());
}

document.getElementById('formCliente').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = {
        id_cliente: document.getElementById('clienteId').value,
        nombre: document.getElementById('nombre').value,
        email: document.getElementById('email').value,
        celular: document.getElementById('celular').value
    };
    const url = formData.id_cliente ? `${API_BASE}/updateClient.php` : `${API_BASE}/createClient.php`;
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    }).then(() => {
        cerrarModal('modalEditar');
        cargarClientes();
    });
});

document.addEventListener('DOMContentLoaded', cargarClientes);