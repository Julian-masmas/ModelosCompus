<?php
require_once "connectDB.php";

header('Content-Type: application/json');

try {
    $stmt = $conn->query("SELECT * FROM clients");
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($clients);

} catch (PDOException $e) {
    echo json_encode([
        "error" => "Error al obtener los clientes"
    ]);
}