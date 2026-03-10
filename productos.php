<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="assets/css/productos.css">
</head>

<body>
    <aside class="sidebar">
        <a href="index.php">Inicio</a>
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

    <script src="assets/js/productos.js"></script>
</body>
