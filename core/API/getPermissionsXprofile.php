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
    
    $SQL="SELECT * FROM permissions;";
    $registros = false;
    $RESULT = _Q($SQL, $MYSQLI, 2);
    
    if($RESULT){
        $SQLP="SELECT id_permissions FROM permissionsxprofile WHERE id_perfil=".$id;
        $RESULT_P = _Q($SQLP, $MYSQLI, 2);
        $permissionsxprofile = [];
        if($RESULT_P){
            foreach ($RESULT_P as $key => $value) {
                $permissionsxprofile[] = $value['id_permissions'];
            }
        }
        
        $response =["code"=>1,"permissions"=>$RESULT,"permisosAsignados"=>$permissionsxprofile];
    }else{
        $response =["code"=>0];
    }
    echo json_encode($response,JSON_UNESCAPED_UNICODE);
    