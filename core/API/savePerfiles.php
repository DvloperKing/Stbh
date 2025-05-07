<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

include_once "../constantes.php";
include_once "../estructura_bd.php";

$MYSQLI = _DB_HDND();

// Validar que existan datos necesarios
if (!isset($_POST['perfilId']) || !isset($_POST['permissions'])) {
    echo json_encode(["code" => 0, "message" => "Faltan datos para guardar"]);
    exit;
}

$id = _clean($_POST['perfilId'], $MYSQLI);
$permissions = $_POST["permissions"];

// Eliminar permisos actuales del perfil
$SQLDelete = "DELETE FROM permissionsxprofile WHERE id_perfil = $id;";
_Q($SQLDelete, $MYSQLI, 0);

// Insertar nuevos permisos
$SQLInsert = "INSERT INTO permissionsxprofile (id_perfil, id_permissions) VALUES ";
$values = [];

foreach ($permissions as $perm) {
    $permClean = _clean($perm, $MYSQLI);
    $values[] = "($id, $permClean)";
}

$SQLInsert .= implode(",", $values) . ";";
$result = _Q($SQLInsert, $MYSQLI, 0);

if ($result) {
    echo json_encode(["code" => 1, "message" => "Permisos guardados correctamente"]);
} else {
    echo json_encode(["code" => 0, "message" => "Error al guardar permisos"]);
}
