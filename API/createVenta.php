<?php
require_once __DIR__ . '/../config/connectDB.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$idCliente = filter_var($data['id_cliente'] ?? null, FILTER_VALIDATE_INT);
$idProducto = filter_var($data['id_producto'] ?? null, FILTER_VALIDATE_INT);
$cantidad = filter_var($data['cantidad'] ?? null, FILTER_VALIDATE_INT);
$fechaCompra = trim($data['fecha_compra'] ?? '');
$metodoPago = trim($data['metodo_pago'] ?? '');
$observaciones = trim($data['observaciones'] ?? '');

if (!$idCliente || !$idProducto || !$cantidad || $cantidad <= 0 || $fechaCompra === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos inválidos para generar la venta']);
    exit;
}

try {
    $conn->beginTransaction();

    $stmtPrecio = $conn->prepare('SELECT precio FROM productos WHERE id_producto = :id_producto');
    $stmtPrecio->execute([':id_producto' => $idProducto]);
    $producto = $stmtPrecio->fetch();

    if (!$producto) {
        $conn->rollBack();
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Producto no encontrado']);
        exit;
    }

    $montoTotal = (float) $producto['precio'] * $cantidad;

    $stmtVenta = $conn->prepare('INSERT INTO ventas (id_cliente, id_producto, fecha_compra, cantidad, monto_total, metodo_pago, observaciones)
                                 VALUES (:id_cliente, :id_producto, :fecha_compra, :cantidad, :monto_total, :metodo_pago, :observaciones)');
    $stmtVenta->execute([
        ':id_cliente' => $idCliente,
        ':id_producto' => $idProducto,
        ':fecha_compra' => $fechaCompra,
        ':cantidad' => $cantidad,
        ':monto_total' => $montoTotal,
        ':metodo_pago' => $metodoPago !== '' ? $metodoPago : null,
        ':observaciones' => $observaciones !== '' ? $observaciones : null,
    ]);

    $conn->commit();
    echo json_encode(['success' => true, 'id_venta' => (int) $conn->lastInsertId(), 'monto_total' => $montoTotal]);
} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'No se pudo generar la venta']);
}
