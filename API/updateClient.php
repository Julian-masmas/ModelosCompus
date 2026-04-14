<?php
require_once __DIR__ . '/../config/connectDB.php';

// Obtener datos del POST
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id_cliente']) && isset($data['nombre']) && isset($data['email']) && isset($data['celular'])) {
    
    $sql = "UPDATE clients SET name = :nombre, correo = :email, celular = :celular WHERE id_cliente = :id";
    $stmt = $conn->prepare($sql);
    
    $resultado = $stmt->execute([
        ':id' => $data['id_cliente'],
        ':nombre' => $data['nombre'],
        ':email' => $data['email'],
        ':celular' => $data['celular']
    ]);
    
    if ($resultado) {
        echo json_encode(["success" => true, "message" => "Cliente actualizado exitosamente"]);
    } else {
        echo json_encode(["success" => false, "error" => "Error al actualizar el cliente"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
}
?>