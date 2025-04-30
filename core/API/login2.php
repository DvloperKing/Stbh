<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "../Constantes.php";
include_once "../estructura_bd.php";

$MYSQLI = _DB_HDND();
$email = _clean($_POST['email'], $MYSQLI);
$pass  = _clean($_POST['pass'], $MYSQLI);
$tipo  = (int)_clean($_POST['tipo'], $MYSQLI);

session_start();

$response = ["code" => 0, "response" => null];

if ($tipo === 1) {
    $SQL = "SELECT u.*, p.name_perfil as perfil
            FROM users u
            INNER JOIN perfil p ON u.id_perfil = p.id
            WHERE u.email = '$email' AND u.pass = '$pass'
            LIMIT 1";
    
    $result = $MYSQLI->query($SQL);
    $usuario = $result ? $result->fetch_assoc() : false;

    if ($usuario && (int)$usuario['id_perfil'] === 1) {
        $SQLP = "SELECT id_permissions FROM permissionsxprofile WHERE id_perfil = 1";
        $permisos_result = $MYSQLI->query($SQLP);
        $permisos = [];
        if ($permisos_result) {
            while ($row = $permisos_result->fetch_assoc()) {
                $permisos[] = $row;
            }
        }

        $_SESSION['users']    = $usuario;
        $_SESSION['permisos'] = $permisos;

        $response = [
            "code"     => 1,
            "response" => $usuario,
            "permisos" => $permisos
        ];
    }

} elseif ($tipo === 2) {
    $SQL = "SELECT u.*, t.*
            FROM users u
            INNER JOIN teaching t ON u.id = t.id_user
            WHERE u.email = '$email' AND u.pass = '$pass'
            LIMIT 1";

    $result = $MYSQLI->query($SQL);
    $docente = $result ? $result->fetch_assoc() : false;

    if ($docente && (int)$docente['id_perfil'] === 2) {
        $_SESSION['docentes'] = $docente;
        $response = [
            "code" => 2,
            "response" => $docente
        ];
    }

} elseif ($tipo === 3) {
    $SQL = "SELECT u.*, s.*
            FROM users u
            INNER JOIN students s ON u.id = s.id_user
            WHERE u.email = '$email' AND u.pass = '$pass'
            LIMIT 1";

    $result = $MYSQLI->query($SQL);
    $alumno = $result ? $result->fetch_assoc() : false;

    if ($alumno && (int)$alumno['id_perfil'] === 3) {
        $_SESSION['alumnos'] = $alumno;
        $response = [
            "code" => 3,
            "response" => $alumno
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
