<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

include_once "../constantes.php";
include_once "../estructura_bd.php";

$MYSQLI = _DB_HDND();

// Validar que exista el parámetro 'id'
if (!isset($_POST['id'])) {
    echo json_encode(["code" => 0, "message" => "ID de perfil no proporcionado"]);
    exit;
}

$id = _clean($_POST['id'], $MYSQLI);

// Obtener todos los permisos
$SQL = "SELECT * FROM permissions ORDER BY id;";
$allPermissions = _Q($SQL, $MYSQLI, 2);

// Obtener los permisos asignados al perfil
$SQLP = "SELECT id_permissions FROM permissionsxprofile WHERE id_perfil = $id;";
$assigned = _Q($SQLP, $MYSQLI, 2);

$assignedIds = array_column($assigned, 'id_permissions');

if ($allPermissions !== false) {
    echo json_encode([
        "code" => 1,
        "permissions" => $allPermissions,
        "permisosAsignados" => $assignedIds
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["code" => 0, "message" => "Error al obtener permisos"]);
}
