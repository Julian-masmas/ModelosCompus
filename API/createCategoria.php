<?php
require_once __DIR__ . '/../config/connectDB.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$nombre = trim($data['nombre'] ?? '');
$descripcion = trim($data['descripcion'] ?? '');

if ($nombre === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'El nombre de la categoría es obligatorio']);
    exit;
}

try {
    $stmt = $conn->prepare('INSERT INTO categorias (nombre, descripcion) VALUES (:nombre, :descripcion)');
    $stmt->execute([
        ':nombre' => $nombre,
        ':descripcion' => $descripcion !== '' ? $descripcion : null,
    ]);

    echo json_encode(['success' => true, 'id_categoria' => (int) $conn->lastInsertId()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'No se pudo crear la categoría']);
}
