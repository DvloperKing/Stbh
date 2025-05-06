<?php
$host = 'localhost';
$port = 3307;
$db = 'stbh';
$user = 'root';
$pass = '12345';
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>
