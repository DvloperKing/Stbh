<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Access-Control-Allow-Origin: *");

    include_once "../constantes.php";
    include_once "../estructura_bd.php";
    
    //var_dump($_POST);
    $MYSQLI = _DB_HDND();
    
    $id    =   _clean($_POST['id'],$MYSQLI);

    $SQL="DELETE FROM permissionsxprofile WHERE id_perfil = ".$id.";";
    $registros = false;
    $RESULT = _Q($SQL, $MYSQLI, 0);


$SQLInsert = 'INSERT INTO permissionsxprofile (id_perfil,id_permissions) VALUES ';
foreach ($_POST["permissions"] as $key => $value) {
    if(end($_POST["permissions"])==$value){
        $SQLInsert .= "(".$id.",".$value.");";
    }else{
        $SQLInsert .= "(".$id.",".$value."),";
    }
    
}
    $RESULT = _Q($SQLInsert, $MYSQLI, 0);
    
    if($RESULT){
        $response =["code"=>1,"permissions"=>$RESULT];
    }else{
        $response =["code"=>0,"respuesta"=>$RESULT];
    }
    echo json_encode($response,JSON_UNESCAPED_UNICODE);
    