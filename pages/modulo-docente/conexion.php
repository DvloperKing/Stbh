<?php
function _DB_HDND() {
    $host = 'localhost:3307';
    $db = 'stbh';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
        exit;
    }
}
?>