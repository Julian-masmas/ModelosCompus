<?php
require_once __DIR__ . "/connectDB.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data["name"]) && !empty($data["correo"]) && !empty($data["celular"])) {

    $stmt = $conn->prepare("INSERT INTO clients (name, correo, celular) VALUES (?, ?, ?)");
    $stmt->execute([
        $data["name"],
        $data["correo"],
        $data["celular"]
    ]);

    echo json_encode(["success" => true]);

} else {
    echo json_encode(["success" => false]);
}