<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; display: flex; background: #f5f7fb; }
        .sidebar { width: 220px; min-height: 100vh; background: #1f2937; padding: 24px 16px; }
        .sidebar a { display: block; color: #e5e7eb; text-decoration: none; padding: 10px; border-radius: 8px; margin-bottom: 8px; }
        .sidebar a.active, .sidebar a:hover { background: #374151; color: #fff; }
        main { flex: 1; padding: 24px; }
        .panel { background: #fff; border-radius: 12px; box-shadow: 0 4px 14px rgba(0,0,0,.08); padding: 20px; margin-bottom: 16px; }
        .grid { display: grid; grid-template-columns: repeat(3, minmax(180px, 1fr)); gap: 12px; }
        input, select, textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 8px 12px; cursor: pointer; }
    </style>
</head>

<body>
    <aside class="sidebar">
        <a href="../consultas/index.php">Inicio</a>
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

    <script>
        async function cargarClientes() {
            const res = await fetch('getClients.php');
            const rows = await res.json();
            document.getElementById('ventaCliente').innerHTML = rows.map(c => `<option value="${c.id_cliente}">${c.name}</option>`).join('');
        }

        async function cargarProductos() {
            const res = await fetch('getProductos.php');
            const rows = await res.json();
            document.getElementById('ventaProducto').innerHTML = rows.map(p => `<option value="${p.id_producto}">${p.nombre} ($${Number(p.precio).toFixed(2)})</option>`).join('');
        }

        async function cargarVentas() {
            const res = await fetch('getVentas.php');
            const rows = await res.json();
            document.getElementById('tbodyVentas').innerHTML = rows.map(v => `
                <tr>
                    <td>${v.id_venta}</td>
                    <td>${v.fecha_compra}</td>
                    <td>${v.cliente}</td>
                    <td>${v.producto}</td>
                    <td>${v.cantidad}</td>
                    <td>$${Number(v.total_linea).toFixed(2)}</td>
                </tr>
            `).join('');
        }

        document.getElementById('formVenta').addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = {
                id_cliente: document.getElementById('ventaCliente').value,
                id_producto: document.getElementById('ventaProducto').value,
                cantidad: document.getElementById('ventaCantidad').value,
                fecha_compra: document.getElementById('ventaFecha').value.replace('T', ' '),
                metodo_pago: document.getElementById('ventaMetodo').value,
                observaciones: document.getElementById('ventaObs').value,
            };

            const res = await fetch('createVenta.php', {
                method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(payload)
            });
            const result = await res.json();
            document.getElementById('msgVenta').textContent = result.success
                ? `Venta registrada. Total: $${Number(result.monto_total).toFixed(2)}`
                : (result.error || 'Error al registrar venta');

            if (result.success) {
                e.target.reset();
                cargarVentas();
            }
        });

        document.getElementById('ventaFecha').value = new Date().toISOString().slice(0, 16);
        Promise.all([cargarClientes(), cargarProductos(), cargarVentas()]);
    </script>
</body>

</html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Ventas</title>
<link rel="stylesheet" href="styles.css">
<style>body{margin:0;font-family:Arial,sans-serif;display:flex;background:#f5f7fb}.sidebar{width:220px;min-height:100vh;background:#1f2937;padding:24px 16px}.sidebar a{display:block;color:#e5e7eb;text-decoration:none;padding:10px;border-radius:8px;margin-bottom:8px}.sidebar a.active,.sidebar a:hover{background:#374151;color:#fff}main{flex:1;padding:24px}.panel{background:#fff;border-radius:12px;box-shadow:0 4px 14px rgba(0,0,0,.08);padding:20px}</style>
</head>
<body>
<aside class="sidebar"><a href="../consultas/index.php">Inicio</a><a class="active" href="ventas.php">Ventas</a><a href="productos.php">Productos</a></aside>
<main><section class="panel"><h1>Listado de Ventas</h1><table><thead><tr><th>ID</th><th>Fecha</th><th>Cliente</th><th>Producto</th><th>Cantidad</th><th>Total</th></tr></thead><tbody id="tbodyVentas"></tbody></table></section></main>
<script>fetch('getVentas.php').then(r=>r.json()).then(rows=>{document.getElementById('tbodyVentas').innerHTML=rows.map(v=>`<tr><td>${v.id_venta}</td><td>${v.fecha_compra}</td><td>${v.cliente}</td><td>${v.producto}</td><td>${v.cantidad}</td><td>$${Number(v.total_linea).toFixed(2)}</td></tr>`).join('');});</script>
</body></html>
