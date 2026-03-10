<?php
/* ========= CONFIGURACIÓN ========= */
$hostDB = '127.0.0.1';
$nameDB = 'julian_db';
$userDB = 'julian';
$pwDB   = '12345';

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
