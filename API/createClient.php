<?php
require_once __DIR__ . "/connectDB.php";

// Obtener datos del POST
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['nombre']) && isset($data['email']) && isset($data['celular'])) {
    
    $sql = "INSERT INTO clients (name, correo, celular) VALUES (:nombre, :email, :celular)";
    $stmt = $conn->prepare($sql);
    
    $resultado = $stmt->execute([
        ':nombre' => $data['nombre'],
        ':email' => $data['email'],
        ':celular' => $data['celular']
    ]);
    
    if ($resultado) {
        // Obtener el ID del último registro insertado
        $nuevoId = $conn->lastInsertId();
        
        echo json_encode([
            "success" => true, 
            "message" => "Cliente creado exitosamente",
            "id_cliente" => $nuevoId  // Devolvemos el ID generado
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Error al crear el cliente"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
}
?>