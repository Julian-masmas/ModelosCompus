async function cargarCategorias() {
            const res = await fetch('API/getCategorias.php');
            const categorias = await res.json();
            const select = document.getElementById('productoCategoria');
            select.innerHTML = categorias.map(c => `<option value="${c.id_categoria}">${c.nombre}</option>`).join('');
            if (!categorias.length) {
                select.innerHTML = '<option value="">No hay categorías</option>';
            }
        }

        async function cargarProductos() {
            const res = await fetch('API/getProductos.php');
            const rows = await res.json();
            document.getElementById('tbodyProductos').innerHTML = rows.map(p => `
                <tr>
                    <td>${p.id_producto}</td>
                    <td>${p.nombre}</td>
                    <td>${p.categoria ?? ''}</td>
                    <td>$${Number(p.precio).toFixed(2)}</td>
                    <td>${p.descripcion ?? ''}</td>
                </tr>
            `).join('');
        }

        document.getElementById('formCategoria').addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = {
                nombre: document.getElementById('categoriaNombre').value,
                descripcion: document.getElementById('categoriaDescripcion').value,
            };
            const res = await fetch('createCategoria.php', {
                method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(payload)
            });
            const result = await res.json();
            document.getElementById('msgCategoria').textContent = result.success ? 'Categoría creada' : (result.error || 'Error');
            if (result.success) {
                e.target.reset();
                cargarCategorias();
            }
        });

        document.getElementById('formProducto').addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = {
                nombre: document.getElementById('productoNombre').value,
                precio: document.getElementById('productoPrecio').value,
                id_categoria: document.getElementById('productoCategoria').value,
                descripcion: document.getElementById('productoDescripcion').value,
            };
            const res = await fetch('API/createProducto.php', {
                method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(payload)
            });
            const result = await res.json();
            document.getElementById('msgProducto').textContent = result.success ? 'Producto agregado' : (result.error || 'Error');
            if (result.success) {
                e.target.reset();
                cargarProductos();
            }
        });

        Promise.all([cargarCategorias(), cargarProductos()]);