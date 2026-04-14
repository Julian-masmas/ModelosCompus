<?php
require_once __DIR__ . '/../config/connectDB.php';
header('Content-Type: application/json');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

$stmt = $conn->prepare('SELECT id_cliente, name, correo, celular FROM clients WHERE id_cliente = :id');
$stmt->execute([':id' => $id]);
$cliente = $stmt->fetch();

echo json_encode($cliente ?: ['error' => 'Cliente no encontrado']);
