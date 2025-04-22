<?php
session_start();

function verificar_acceso($tipo_requerido) {
    if (!isset($_SESSION['usuario']) || $_SESSION['tipo_usuario'] != $tipo_requerido) {
        header("Location: ../login.php");
        exit;
    }
}
