<?php
header("Access-Control-Allow-Origin: *");
include_once __DIR__ . '/Constantes.php';
include_once __DIR__ . '/estructura_bd.php';


    
    $MYSQLI = _DB_HDND();
    $SQL="SELECT * FROM perfil";
    $RESULT = _Q($SQL, $MYSQLI, 2);
 
    echo json_encode($RESULT,JSON_UNESCAPED_UNICODE);