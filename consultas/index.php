<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Clientes</title>
    <link rel="stylesheet" href="../API/styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            background: #f5f7fb;
        }

        .sidebar {
            width: 220px;
            background: #1f2937;
            color: #fff;
            padding: 24px 16px;
        }

        .sidebar h2 {
            margin-top: 0;
            font-size: 1.2rem;
            text-align: center;
        }

        .sidebar a {
            display: block;
            color: #e5e7eb;
            text-decoration: none;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #374151;
            color: #fff;
        }

        .content {
            flex: 1;
            padding: 24px;
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 24px;
        }

        .panel {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            padding: 20px;
        }

        .search-container {
            margin-bottom: 20px;
        }

        #searchInput {
            padding: 8px;
            width: 280px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-agregar {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        .venta-select {
            width: 100%;
            min-width: 130px;
            padding: 6px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: min(500px, 90%);
            border-radius: 10px;
        }

        .close {
            float: right;
            font-size: 28px;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
        }

        @media (max-width: 1000px) {
            .content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <h2>Panel</h2>
        <a class="active" href="index.php">Inicio</a>
        <a href="../API/ventas.php">Ventas</a>
        <a href="../API/productos.php">Productos</a>
    </aside>

    <main class="content">
        <section class="panel">
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Buscar clientes...">
                <button class="btn-agregar" onclick="abrirModalAgregar()">Agregar Cliente</button>
            </div>

            <table>
                <caption>Clientes</caption>
                <thead>
                    <tr>
                        <th>ID Cliente</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Celular</th>
                        <th>Ventas</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="tablaClientes"></tbody>
            </table>
        </section>

        <section class="panel" id="panelDetalleCompra">
            <h2>Detalle de compra</h2>
            <p>Selecciona una fecha de compra en la tabla para ver el detalle completo.</p>
        </section>
    </main>

    <div id="modalVer" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('modalVer')">&times;</span>
            <h2>Detalles del Cliente</h2>
            <div id="detallesCliente"></div>
        </div>
    </div>

    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('modalEditar')">&times;</span>
            <h2 id="modalTitulo">Editar Cliente</h2>
            <form id="formCliente">
                <input type="hidden" id="clienteId" name="id_cliente">
                <div class="form-group"><label>Nombre:</label><input type="text" id="nombre" required></div>
                <div class="form-group"><label>Email:</label><input type="email" id="email" required></div>
                <div class="form-group"><label>Celular:</label><input type="tel" id="celular" required></div>
                <button type="submit">Guardar</button>
                <button type="button" onclick="cerrarModal('modalEditar')">Cancelar</button>
            </form>
        </div>
    </div>

    <script>
        const API_BASE = '../API';

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
            const data = await response.json();
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

        document.getElementById('searchInput').addEventListener('keyup', function() {
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
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id_cliente: id})
            }).then(() => cargarClientes());
        }

        document.getElementById('formCliente').addEventListener('submit', function(e) {
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
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(formData)
            }).then(() => {
                cerrarModal('modalEditar');
                cargarClientes();
            });
        });

        document.addEventListener('DOMContentLoaded', cargarClientes);
    </script>
</body>

</html>
