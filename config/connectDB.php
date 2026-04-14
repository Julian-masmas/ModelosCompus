<?php
/* ========= CONFIGURACIÓN ========= */
ini_set('display_errors', 1);
error_reporting(E_ALL);

$hostDB = getenv('DB_HOST');
$nameDB = getenv('DB_NAME');
$userDB = getenv('DB_USER');
$pwDB   = getenv('DB_PASSWORD');

/* ========= CONEXIÓN ========= */
try {
    $dsn = "mysql:host=$hostDB;dbname=$nameDB;charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Mostrar errores como excepciones
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Resultados como array asociativo
        PDO::ATTR_EMULATE_PREPARES   => false,                   // Preparados reales (más seguro)
    ];

    $conn = new PDO($dsn, $userDB, $pwDB, $options);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
