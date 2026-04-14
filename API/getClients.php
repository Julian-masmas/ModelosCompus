<?php

require_once __DIR__ . '/../config/connectDB.php';
header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(["error" => "No hay conexión"]);
    exit;
}

try {
    $stmt = $conn->query('SELECT id_cliente, name, correo, celular FROM clients ORDER BY id_cliente DESC');
    echo json_encode($stmt->fetchAll());
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
