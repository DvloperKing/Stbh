<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
 
define('HDND_HOST', 'localhost');
define('HDND_USER', 'root');
define('HDND_PASS', '12345');
define('HDND_DB_NAME', 'stbh');
define("MYSQL_PORT", 3307); 
session_start();
if(!isset($_SESSION['token'])){
    $u= uniqid(rand());
    $token= trim(md5($u));
    $_SESSION['token']=$token;
}
 
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');