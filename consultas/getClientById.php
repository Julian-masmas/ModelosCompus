<?php
require_once "connectDB.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT id_cliente FROM clients WHERE id_cliente = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    if ($cliente = $stmt->fetch()) {
        echo json_encode($cliente);
    } else {
        echo json_encode(["error" => "Cliente no encontrado"]);
    }
} else {
    echo json_encode(["error" => "ID no proporcionado"]);
}
?>