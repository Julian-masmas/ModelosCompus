<?php
require_once "connectDB.php";

// Obtener datos del POST
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id_cliente'])) {
    
    $sql = "DELETE FROM clients WHERE id_cliente = :id";
    $stmt = $conn->prepare($sql);
    
    $resultado = $stmt->execute([':id' => $data['id_cliente']]);
    
    if ($resultado) {
        echo json_encode(["success" => true, "message" => "Cliente eliminado exitosamente"]);
    } else {
        echo json_encode(["success" => false, "error" => "Error al eliminar el cliente"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "ID no proporcionado"]);
}
?>