<?php
 
/* FUNCTIONS FOR THE DB */
function ver(){
    echo "funciona";
}
 
#RETURNS A SINGLE DB CONNECTIONS
function db($_host, $_user, $_password, $_database) {
    try {
        $mysqli = new mysqli($_host, $_user, $_password, $_database, MYSQL_PORT);
        if ($mysqli->connect_errno) {
            $_ERROR = $mysqli->connect_errno . " " . $mysqli->connect_error . " " . $mysqli->host_info;
            error_log($_ERROR);
            return FALSE;
        } else {
            return $mysqli;
        }
    } catch (Exception $e) {
        $_ERROR = var_export($e, true);
        error_log($_ERROR);
        return FALSE;
    }
}
 
#CLEANS VARIABLES FOR QUERY INPUT
function _clean($VARS, $MYSQLI) { #vars
    if(!function_exists("get_magic_quotes_gpc")) {
        function get_magic_quotes_gpc() { return 0; }
    }
    
    if (get_magic_quotes_gpc()) {
        $VARS = stripslashes($VARS);
    }
    $VARS = $MYSQLI->real_escape_string($VARS);
    return $VARS;
}
 
#KILLS MYSQLI CONNECTION
function UNDB($MYSQLI) {
    if($MYSQLI)
        mysqli_close($MYSQLI);#$MYSQLI->close; #true or false
}
 
#CONNECTS TO VARIOUS MYSQL INSTANCES
function _DB_HDND() {
    $mysqli = db(HDND_HOST, HDND_USER, HDND_PASS, HDND_DB_NAME);
    if (!$mysqli)
        $mysqli = db(HDND_HOST, HDND_USER, HDND_PASS, HDND_DB_NAME);#Si tienes un servidor de backup aqui lo puedes configurar, por si no funciona la conexion con uno entra con otro
    return $mysqli;
}
 
 
#QUERYS ANY DATABASE
function _Q($SQL, $MYSQLI, $TIPO = 2) {
    if($SQL == "")
        return FALSE;
    $RETURN = FALSE;
    $MYSQLI->query("SET NAMES 'utf8'");
    try{
        switch ($TIPO) {
            case 0:     #INSERT
                if ($MYSQLI->query($SQL))
                    $RETURN = $MYSQLI->insert_id;
                else
                    $RETURN = FALSE;
                break;
            case 1:     #UPDATE
                if ($MYSQLI->query($SQL))
                    $RETURN = TRUE;
                else
                    $RETURN = FALSE;
                break;
            case 2:     #SELECT
                if ($RESULT = $MYSQLI->query($SQL)) {
                    while ($row = $RESULT->fetch_assoc()) {
                        $RETURN[] = $row;
                    }
                    #$RESULT->free;
                }else{
                    $RETURN = FALSE;
                }
                break;
            case 3:     #SELECT
                if ($RESULT = $MYSQLI->query($SQL)) {
                    while ($row = $RESULT->fetch_row()) {
                        $RETURN[] = $row;
                    }
                    $RESULT->free;
                }else{
                    $RETURN = FALSE;
                }
                break;
        }
    }catch(Exception $E){
        error_log(var_export($GLOBALS,true).var_export($E,true));
    }    
    return $RETURN;
}