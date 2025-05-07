<?php
session_start();

// Simular rol y materias asignadas (esto vendría de la BD)
$_SESSION['rol'] = $_SESSION['rol'] ?? 'profesor';
$_SESSION['materias'] = $_SESSION['materias'] ?? ['matematicas', 'historia'];
$directorioBase = "archivos/";

// Crear carpetas si no existen
foreach ($_SESSION['materias'] as $materia) {
    if (!is_dir($directorioBase . $materia)) {
        mkdir($directorioBase . $materia, 0777, true);
    }
}

// Procesar subida de archivo
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

// Procesar eliminación de archivo
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
    <title>STBH | Módulo Didáctico</title>
    <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .logos-container {
            background-color: white;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
            text-align: center;
        }
        .logo-img {
            max-height: 60px;
            margin: 0 10px;
        }
        .btn-primary {
            background-color: #0b0146;
            border-color: #0b0146;
        }
        .btn-primary:hover {
            background-color: #1a237e;
            border-color: #1a237e;
        }
        .card-title {
            font-weight: bold;
            color: #0b0146;
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

<!-- Logos -->
<div class="logos-container">
    <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../assets/img/CRBH3.png" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="Marca" class="logo-img">
</div>

<!-- Contenido -->
<div class="container d-flex justify-content-center mt-4">
    <div class="card p-4 w-100" style="max-width: 900px; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 15px;">
        <h2 class="text-center mb-4">MÓDULO DIDÁCTICO</h2>

        <?php if ($_SESSION['rol'] === 'profesor'): ?>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <select name="materia" class="form-control" required>
                            <?php foreach ($_SESSION['materias'] as $materia): ?>
                                <option value="<?= $materia; ?>"><?= ucfirst($materia); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="file" name="archivo" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">📤 SUBIR ARCHIVO</button>
                    </div>
                </div>
            </form>
        <?php endif; ?>

        <!-- Material disponible -->
        <h4 class="mt-4" style="color: #0b0146;"><i class="bi bi-folder-fill text-warning"></i> Material Disponible</h4>
        <?php
        foreach ($_SESSION['materias'] as $materia) {
            echo "<strong>" . ucfirst($materia) . "</strong><br>";
            $ruta = $directorioBase . $materia . "/";
            if (is_dir($ruta)) {
                $archivos = array_diff(scandir($ruta), ['.', '..']);
                echo "<ul>";
                foreach ($archivos as $archivo) {
                    echo "<li>📄 $archivo 
                        <a href='$ruta$archivo' class='btn btn-sm btn-success' download>Descargar</a>";
                    if ($_SESSION['rol'] === 'profesor') {
                        echo " <button class='btn btn-sm btn-danger' onclick=\"eliminarArchivo('$materia', '$archivo')\">Eliminar</button>";
                    }
                    echo "</li>";
                }
                echo "</ul>";
            }
        }
        ?>
    </div>
</div>

<!-- Footer -->
<footer class="footer py-4 bg-white mt-5 border-top">
    <div class="container text-center">
        <p class="mb-0 text-muted">STBH © <?= date('Y'); ?> | Todos los derechos Reservados</p>
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    try {
                        let res = JSON.parse(response);
                        alert(res.mensaje);
                        location.reload();
                    } catch {
                        alert("Error inesperado.");
                    }
                },
                error: function () {
                    alert("Error al enviar los datos.");
                }
            });
        });
    });

    function eliminarArchivo(materia, archivo) {
        if (confirm("¿Seguro que quieres eliminar este archivo?")) {
            $.post("Modulo_Didactico.php", { materia: materia, eliminar: archivo }, function (response) {
                try {
                    let res = JSON.parse(response);
                    alert(res.mensaje);
                    location.reload();
                } catch {
                    alert("Error inesperado al eliminar.");
                }
            });
        }
    }
</script>
</body>
</html>
