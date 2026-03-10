<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; display: flex; background: #f5f7fb; }
        .sidebar { width: 220px; min-height: 100vh; background: #1f2937; padding: 24px 16px; }
        .sidebar a { display: block; color: #e5e7eb; text-decoration: none; padding: 10px; border-radius: 8px; margin-bottom: 8px; }
        .sidebar a.active, .sidebar a:hover { background: #374151; color: #fff; }
        main { flex: 1; padding: 24px; }
        .panel { background: #fff; border-radius: 12px; box-shadow: 0 4px 14px rgba(0,0,0,.08); padding: 20px; margin-bottom: 16px; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(200px, 1fr)); gap: 12px; }
        input, select, textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 8px 12px; cursor: pointer; }
    </style>
</head>

<body>
    <aside class="sidebar">
        <a href="../consultas/index.php">Inicio</a>
        <a href="ventas.php">Ventas</a>
        <a class="active" href="productos.php">Productos</a>
    </aside>

    <main>
        <section class="panel">
            <h2>Agregar categoría</h2>
            <form id="formCategoria" class="grid">
                <div><label>Nombre</label><input id="categoriaNombre" required></div>
                <div><label>Descripción</label><input id="categoriaDescripcion"></div>
                <div><button type="submit">Crear categoría</button></div>
            </form>
            <p id="msgCategoria"></p>
        </section>

        <section class="panel">
            <h2>Agregar producto</h2>
            <form id="formProducto" class="grid">
                <div><label>Nombre</label><input id="productoNombre" required></div>
                <div><label>Precio</label><input id="productoPrecio" type="number" min="0.01" step="0.01" required></div>
                <div><label>Categoría</label><select id="productoCategoria" required></select></div>
                <div><label>Descripción</label><textarea id="productoDescripcion"></textarea></div>
                <div><button type="submit">Guardar producto</button></div>
            </form>
            <p id="msgProducto"></p>
        </section>

        <section class="panel">
            <h1>Listado de Productos</h1>
            <table>
                <thead>
                    <tr><th>ID</th><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Descripción</th></tr>
                </thead>
                <tbody id="tbodyProductos"></tbody>
            </table>
        </section>
    </main>

    <script>
        async function cargarCategorias() {
            const res = await fetch('getCategorias.php');
            const categorias = await res.json();
            const select = document.getElementById('productoCategoria');
            select.innerHTML = categorias.map(c => `<option value="${c.id_categoria}">${c.nombre}</option>`).join('');
            if (!categorias.length) {
                select.innerHTML = '<option value="">No hay categorías</option>';
            }
        }

        async function cargarProductos() {
            const res = await fetch('getProductos.php');
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
            const res = await fetch('createProducto.php', {
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
    </script>
</body>

</html>
