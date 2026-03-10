<?php
require_once __DIR__ . '/connectDB.php';
header('Content-Type: application/json');

$sql = 'SELECT v.id_venta,
               DATE_FORMAT(v.fecha_compra, "%Y-%m-%d") AS fecha_compra,
               cl.name AS cliente,
               p.nombre AS producto,
               v.cantidad,
               (v.cantidad * p.precio) AS total_linea
        FROM ventas v
        INNER JOIN clients cl ON cl.id_cliente = v.id_cliente
        INNER JOIN productos p ON p.id_producto = v.id_producto
        ORDER BY v.fecha_compra DESC, v.id_venta DESC';
$stmt = $conn->query($sql);
echo json_encode($stmt->fetchAll());
