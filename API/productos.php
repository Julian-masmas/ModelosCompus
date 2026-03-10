<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Productos</title>
<link rel="stylesheet" href="styles.css">
<style>body{margin:0;font-family:Arial,sans-serif;display:flex;background:#f5f7fb}.sidebar{width:220px;min-height:100vh;background:#1f2937;padding:24px 16px}.sidebar a{display:block;color:#e5e7eb;text-decoration:none;padding:10px;border-radius:8px;margin-bottom:8px}.sidebar a.active,.sidebar a:hover{background:#374151;color:#fff}main{flex:1;padding:24px}.panel{background:#fff;border-radius:12px;box-shadow:0 4px 14px rgba(0,0,0,.08);padding:20px}</style>
</head>
<body>
<aside class="sidebar"><a href="../consultas/index.php">Inicio</a><a href="ventas.php">Ventas</a><a class="active" href="productos.php">Productos</a></aside>
<main><section class="panel"><h1>Listado de Productos</h1><table><thead><tr><th>ID</th><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Descripción</th></tr></thead><tbody id="tbodyProductos"></tbody></table></section></main>
<script>fetch('getProductos.php').then(r=>r.json()).then(rows=>{document.getElementById('tbodyProductos').innerHTML=rows.map(p=>`<tr><td>${p.id_producto}</td><td>${p.nombre}</td><td>${p.categoria}</td><td>$${Number(p.precio).toFixed(2)}</td><td>${p.descripcion||''}</td></tr>`).join('');});</script>
</body></html>
