<?php

    include_once "../Constantes.php";
    include_once "../estructura_bd.php";
   
    $MYSQLI = _DB_HDND();

    $email      = _clean($_POST['email'], $MYSQLI);
    $pass       = _clean($_POST['pass'], $MYSQLI);
    $tipo       = _clean($_POST['tipo'], $MYSQLI);
    // var_dump($_POST);
    if($tipo==1){
        $SQL = "SELECT u.*,p.name_perfil as perfil from users u inner join perfil p on u.id_perfil = p.id;";
        $registros = false;
        $RESULT = _Q($SQL, $MYSQLI, 2);
        foreach ($RESULT as $key => $value) {
            if ($value['email'] == $email && $value['pass'] == $pass) {
                $registros=$value;
            }
        }

        if ($registros) {
            session_start();
            $SQLP = "SELECT id_permissions FROM permissionsxprofile WHERE id_perfil = " . $registros['id_perfil'];
            
            $RESULT_P = _Q($SQLP, $MYSQLI, 2);
            $_SESSION   ['users']    = $registros;
            $_SESSION   ['permisos'] = $RESULT_P;
            $response = ["code" =>1, "response"=>$registros, "permisos"=>$RESULT_P];
        }
        else{
            $response = ["code"=>0, "response"=>$registros];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
}else if ($tipo==2){
    $SQL = "SELECT u.*,s.* FROM users u INNER JOIN students s ON u.id=s.id_user;";
    $registros = false;
    $RESULT = _Q($SQL, $MYSQLI, 2);
    foreach ($RESULT as $key => $value) {
        if($value['email'] == $email && $value['pass'] == $pass){
                $registros=$value;
        }
    }

    if($registros){
        session_start();
        $_SESSION['alumnos'] = $registros;
        $response = ["code"=>2, "response"=>$registros];
    }else{
        $response = ["code"=>0, "response"=>$registros];
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}