<?php
require_once __DIR__ . '/connectDB.php';
header('Content-Type: application/json');

$idCliente = filter_input(INPUT_GET, 'id_cliente', FILTER_VALIDATE_INT);
if (!$idCliente) {
    echo json_encode([]);
    exit;
}

$sql = 'SELECT DISTINCT DATE_FORMAT(fecha_compra, "%Y-%m-%d") AS fecha_compra
        FROM ventas
        WHERE id_cliente = :id_cliente
        ORDER BY fecha_compra DESC';
$stmt = $conn->prepare($sql);
$stmt->execute([':id_cliente' => $idCliente]);
echo json_encode($stmt->fetchAll());
