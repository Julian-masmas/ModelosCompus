<?php
require_once __DIR__ . '/../config/connectDB.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$idCategoria = filter_var($data['id_categoria'] ?? null, FILTER_VALIDATE_INT);
$nombre = trim($data['nombre'] ?? '');
$descripcion = trim($data['descripcion'] ?? '');
$precio = filter_var($data['precio'] ?? null, FILTER_VALIDATE_FLOAT);

if (!$idCategoria || $nombre === '' || $precio === false || $precio <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos inválidos para crear el producto']);
    exit;
}

try {
    $stmt = $conn->prepare('INSERT INTO productos (id_categoria, nombre, descripcion, precio) VALUES (:id_categoria, :nombre, :descripcion, :precio)');
    $stmt->execute([
        ':id_categoria' => $idCategoria,
        ':nombre' => $nombre,
        ':descripcion' => $descripcion !== '' ? $descripcion : null,
        ':precio' => $precio,
    ]);

    echo json_encode(['success' => true, 'id_producto' => (int) $conn->lastInsertId()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'No se pudo crear el producto']);
}
