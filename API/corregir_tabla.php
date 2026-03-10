<?php
require_once __DIR__ . "/connectDB.php";

echo "<h2>⚠️ RESETEANDO TABLA CLIENTS</h2>";
echo "<p style='color:red;'>Esto eliminará todos los datos existentes</p>";

try {
    // Eliminar la tabla si existe
    $sql_drop = "DROP TABLE IF EXISTS clients";
    $conn->exec($sql_drop);
    echo "✅ Tabla eliminada<br>";
    
    // Crear la tabla nuevamente con la estructura correcta
    $sql_create = "CREATE TABLE clients (
        id_cliente INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        correo VARCHAR(100) NOT NULL UNIQUE,
        celular VARCHAR(20) NOT NULL,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $conn->exec($sql_create);
    echo "✅ Tabla creada correctamente<br>";
    
    // Insertar los datos de ejemplo
    $sql_insert = "INSERT INTO clients (name, correo, celular) VALUES 
        ('Julian', 'fj@gmail.com', '3214563421'),
        ('Pepe', 'pepe@gmail.com', '123456901'),
        ('Angie', 'angie@gmail.com', '3016748921'),
        ('Linus Torvalds', 'linuxthegoat@linux.com', '5554443331')";
    
    $conn->exec($sql_insert);
    echo "✅ Datos de ejemplo insertados<br>";
    
    echo "<br>🎉 Tabla reseteada exitosamente!";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>