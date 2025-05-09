<?php
// Incluir la conexión a la base de datos
include('conexion_db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Suponiendo que el usuario ya está autenticado y su ID está en la sesión
$user_id = $_SESSION['user_id'] ?? null; // Debes tener un sistema de login donde este valor sea guardado

if ($user_id) {
    // Buscar el rol del usuario en la base de datos
    $query = $pdo->prepare("SELECT rol FROM users WHERE id = :user_id");
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    
    // Comprobar si el usuario existe
    if ($query->rowCount() > 0) {
        $user = $query->fetch(PDO::FETCH_ASSOC);
        $_SESSION['rol'] = $user['rol']; // Guardar el rol en la sesión
    } else {
        // Si no se encuentra el usuario, redirigir o mostrar un mensaje de error
        $_SESSION['rol'] = 'estudiante'; // Asignar rol por defecto si no se encuentra el usuario
    }
} else {
    // Si no hay ID de usuario en la sesión, asignar rol por defecto
    $_SESSION['rol'] = 'estudiante';
}
?>
