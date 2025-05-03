<?php
// Parámetros de conexión
$host = 'localhost';   // Nombre del host
$dbname = 'stbh';      // Nombre de la base de datos
$username = 'root';    // Usuario por defecto en XAMPP
$password = 'eduardo2001';        // Contraseña vacía por defecto en XAMPP

// Crear la conexión con la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Establecer el modo de error para lanzar excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Manejo de errores
    die("Conexión fallida: " . $e->getMessage());
}
?>
