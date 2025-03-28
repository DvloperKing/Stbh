<?php
session_start();

// Definir el rol y materias asignadas (esto debe venir de la BD en producción)
$_SESSION['rol'] = 'profesor'; // Cambiar a 'alumno' para pruebas
$_SESSION['materias'] = ['matematicas', 'historia']; // Materias asignadas al usuario

$directorioBase = "archivos/";

// Crear carpetas por materia si no existen
foreach ($_SESSION['materias'] as $materia) {
    if (!is_dir($directorioBase . $materia)) {
        mkdir($directorioBase . $materia, 0777, true);
    }
}

// Subir archivo según la materia
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

// Eliminar archivo con validación de materia
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo Didáctico | STBH</title>
    <link rel="stylesheet" href="../assets/css/soft-ui-dashboard.css?v=1.0.8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .card-modulo {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px;
        }
        .card-title {
            font-weight: bold;
            color: #0b0146;
        }
        .btn-primary {
            background-color: #0b0146;
            border-color: #0b0146;
        }
        .btn-primary:hover {
            background-color: #1a237e;
        }
        .header-title {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            color: #0b0146;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg" style="background-color: rgba(11, 1, 70, 1);">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">STBH | Módulo Didáctico</a>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mt-4">
        <h2 class="header-title">Módulo Didáctico</h2>

        <!-- FORMULARIO PARA SUBIR ARCHIVOS (SOLO PROFESORES) -->
        <?php if ($_SESSION['rol'] === 'profesor'): ?>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select name="materia" class="form-control" required>
                            <?php foreach ($_SESSION['materias'] as $materia): ?>
                                <option value="<?= $materia; ?>"><?= ucfirst($materia); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="file" name="archivo" class="form-control" accept=".pdf,.docx,.pptx,.mp4,.jpg,.png" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">📤 Subir Archivo</button>
                    </div>
                </div>
            </form>
            <div id="status"></div>
        <?php endif; ?>

        <!-- LISTADO DE ARCHIVOS DISPONIBLES -->
        <h3 class="mt-4">📂 Material Disponible</h3>
        <?php
        foreach ($_SESSION['materias'] as $materia) {
            echo "<h4>$materia</h4>";
            echo '<div class="row">';
            $rutaMateria = $directorioBase . $materia . "/";

            if (is_dir($rutaMateria)) {
                $archivos = array_diff(scandir($rutaMateria), array('..', '.'));

                foreach ($archivos as $archivo) {
                    echo '<div class="col-md-4">
                            <div class="card card-modulo">
                                <div class="card-body">
                                    <h5 class="card-title">📄 ' . $archivo . '</h5>
                                    <a href="' . $rutaMateria . $archivo . '" class="btn btn-success btn-sm" download>⬇️ Descargar</a>';

                    if (preg_match('/\.(jpg|png|pdf)$/i', $archivo)) {
                        echo '<a href="' . $rutaMateria . $archivo . '" class="btn btn-info btn-sm" target="_blank">👁️ Ver</a>';
                    }

                    if ($_SESSION['rol'] === 'profesor') {
                        echo '<button onclick="eliminarArchivo(\'' . $materia . '\', \'' . $archivo . '\')" class="btn btn-danger btn-sm">🗑️ Eliminar</button>';
                    }

                    echo '</div>
                            </div>
                        </div>';
                }
            }
            echo '</div>';
        }
        ?>
    </div>

    <!-- Footer -->
    <footer class="footer py-4 bg-light mt-5">
        <div class="container text-center">
            <p class="mb-0">STBH © <script>document.write(new Date().getFullYear())</script> | Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        $(document).ready(function () {
            $("#uploadForm").on("submit", function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                
                $.ajax({
                    url: "Modulo_Didactico.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        let res = JSON.parse(response);
                        alert(res.mensaje);
                        location.reload();
                    }
                });
            });
        });

        function eliminarArchivo(materia, nombreArchivo) {
            if (confirm("¿Seguro que quieres eliminar este archivo?")) {
                $.post("Modulo_Didactico.php", { eliminar: nombreArchivo, materia: materia }, function(response) {
                    let res = JSON.parse(response);
                    alert(res.mensaje);
                    location.reload();
                });
            }
        }
    </script>

</body>
</html>