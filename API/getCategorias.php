<?php
require_once __DIR__ . '/connectDB.php';
header('Content-Type: application/json');

try {
    $stmt = $conn->query('SELECT id_categoria, nombre, descripcion FROM categorias ORDER BY nombre ASC');
    echo json_encode($stmt->fetchAll());
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener categorías']);
}
