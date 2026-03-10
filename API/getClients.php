<?php
require_once __DIR__ . '/connectDB.php';
header('Content-Type: application/json');

try {
    $stmt = $conn->query('SELECT id_cliente, name, correo, celular FROM clients ORDER BY id_cliente DESC');
    echo json_encode($stmt->fetchAll());
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los clientes']);
}
