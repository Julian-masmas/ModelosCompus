<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <link rel="stylesheet" href="assets/css/ventas.css">
</head>

<body>
    <aside class="sidebar">
        <a href="index.php">Inicio</a>
        <a class="active" href="ventas.php">Ventas</a>
        <a href="productos.php">Productos</a>
    </aside>

    <main>
        <section class="panel">
            <h2>Generar venta</h2>
            <form id="formVenta" class="grid">
                <div><label>Cliente</label><select id="ventaCliente" required></select></div>
                <div><label>Producto</label><select id="ventaProducto" required></select></div>
                <div><label>Cantidad</label><input id="ventaCantidad" type="number" min="1" value="1" required></div>
                <div><label>Fecha compra</label><input id="ventaFecha" type="datetime-local" required></div>
                <div><label>Método de pago</label><input id="ventaMetodo" placeholder="Tarjeta, Efectivo..."></div>
                <div><label>Observaciones</label><textarea id="ventaObs"></textarea></div>
                <div><button type="submit">Registrar venta</button></div>
            </form>
            <p id="msgVenta"></p>
        </section>

        <section class="panel">
            <h1>Listado de Ventas</h1>
            <table>
                <thead><tr><th>ID</th><th>Fecha</th><th>Cliente</th><th>Producto</th><th>Cantidad</th><th>Total</th></tr></thead>
                <tbody id="tbodyVentas"></tbody>
            </table>
        </section>
    </main>
    <script src="assets/js/ventas.js"></script>
</body>

