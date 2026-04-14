<?php
require_once __DIR__ . '/../config/connectDB.php';
header('Content-Type: application/json');

$idCliente = filter_input(INPUT_GET, 'id_cliente', FILTER_VALIDATE_INT);
$fechaCompra = filter_input(INPUT_GET, 'fecha_compra', FILTER_UNSAFE_RAW);

if (!$idCliente || !$fechaCompra) {
    http_response_code(400);
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

$stmtCliente = $conn->prepare('SELECT id_cliente, name, correo FROM clients WHERE id_cliente = :id_cliente');
$stmtCliente->execute([':id_cliente' => $idCliente]);
$cliente = $stmtCliente->fetch();

$sql = 'SELECT
            v.id_venta,
            DATE_FORMAT(v.fecha_compra, "%Y-%m-%d") AS fecha_compra,
            v.cantidad,
            p.nombre AS producto,
            c.nombre AS categoria,
            p.precio AS precio_unitario,
            (v.cantidad * p.precio) AS subtotal
        FROM ventas v
        INNER JOIN productos p ON p.id_producto = v.id_producto
        LEFT JOIN categorias c ON c.id_categoria = p.id_categoria
        WHERE v.id_cliente = :id_cliente AND DATE(v.fecha_compra) = :fecha_compra
        ORDER BY v.id_venta ASC';
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id_cliente' => $idCliente,
    ':fecha_compra' => $fechaCompra,
]);
$items = $stmt->fetchAll();
$total = array_reduce($items, fn($acc, $item) => $acc + (float) $item['subtotal'], 0);

echo json_encode([
    'cliente' => $cliente ?: ['name' => 'No encontrado', 'correo' => ''],
    'items' => $items,
    'total' => $total,
]);
