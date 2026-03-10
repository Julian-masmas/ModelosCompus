<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Clientes</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <aside class="sidebar">
        <h2>Panel</h2>
        <a class="active" href="index.php">Inicio</a>
        <a href="ventas.php">Ventas</a>
        <a href="productos.php">Productos</a>
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

    <script src="assets/js/main.js"></script>
</body>

</html>
