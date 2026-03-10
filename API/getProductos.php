<?php
require_once __DIR__ . '/connectDB.php';
header('Content-Type: application/json');

$sql = 'SELECT p.id_producto, p.nombre, p.precio, p.descripcion, c.nombre AS categoria
        FROM productos p
        LEFT JOIN categorias c ON c.id_categoria = p.id_categoria
        ORDER BY p.id_producto DESC';
$stmt = $conn->query($sql);
echo json_encode($stmt->fetchAll());
