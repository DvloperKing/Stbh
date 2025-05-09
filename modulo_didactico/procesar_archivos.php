<?php
session_start();

$directorioBase = "archivos/";

// Crear carpetas si no existen
if (isset($_SESSION['materias'])) {
    foreach ($_SESSION['materias'] as $materia) {
        if (!is_dir($directorioBase . $materia)) {
            mkdir($directorioBase . $materia, 0777, true);
        }
    }
}

// Subir archivo
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["archivo"], $_POST["materia"])) {
    $materia = $_POST["materia"];

    if (!in_array($materia, $_SESSION['materias'])) {
        echo json_encode(["mensaje" => "No tienes permisos para subir archivos a esta materia", "tipo" => "error"]);
        exit;
    }

    $archivo = $_FILES["archivo"];
    $rutaDestino = $directorioBase . $materia . "/" . basename($archivo["name"]);

    if (move_uploaded_file($archivo["tmp_name"], $rutaDestino)) {
        echo json_encode(["mensaje" => "Archivo subido con éxito", "tipo" => "success"]);
    } else {
        echo json_encode(["mensaje" => "Error al subir el archivo", "tipo" => "error"]);
    }
    exit;
}

// Eliminar archivo
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminar"], $_POST["materia"])) {
    $materia = $_POST["materia"];
    $archivoEliminar = $directorioBase . $materia . "/" . $_POST["eliminar"];

    if (!in_array($materia, $_SESSION['materias'])) {
        echo json_encode(["mensaje" => "No tienes permisos para eliminar archivos de esta materia", "tipo" => "error"]);
        exit;
    }

    if (file_exists($archivoEliminar)) {
        unlink($archivoEliminar);
        echo json_encode(["mensaje" => "Archivo eliminado con éxito", "tipo" => "success"]);
    } else {
        echo json_encode(["mensaje" => "Error: el archivo no existe", "tipo" => "error"]);
    }
    exit;
}
?>
