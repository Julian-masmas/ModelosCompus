async function cargarClientes() {
            const res = await fetch('API/getClients.php');
            const rows = await res.json();
            document.getElementById('ventaCliente').innerHTML = rows.map(c => `<option value="${c.id_cliente}">${c.name}</option>`).join('');
        }

        async function cargarProductos() {
            const res = await fetch('API/getProductos.php');
            const rows = await res.json();
            document.getElementById('ventaProducto').innerHTML = rows.map(p => `<option value="${p.id_producto}">${p.nombre} ($${Number(p.precio).toFixed(2)})</option>`).join('');
        }

        async function cargarVentas() {
            const res = await fetch('API/getVentas.php');
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

            const res = await fetch('API/createVenta.php', {
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