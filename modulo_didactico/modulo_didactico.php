<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir la conexión a la base de datos
include('conexion_db.php');

// Incluir el archivo para detectar el rol
include('detectar_rol.php');

// Incluir detección de materias solo si es profesor
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'profesor') {
    include('detectar_materias_profesor.php');
}

$directorioBase = "archivos/";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STBH || Módulo Didáctico</title>
    <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<?php include 'partials/header.php'; ?>

<div class="container d-flex justify-content-center mt-4">
    <div class="card p-4 w-100" style="max-width: 900px;">
        <h2 class="text-center mb-4">MÓDULO DIDÁCTICO </h2>

        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'profesor'): ?>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <select name="materia" class="form-control" required>
                            <?php 
                            if (isset($_SESSION['materias']) && is_array($_SESSION['materias'])):
                                foreach ($_SESSION['materias'] as $materia): ?>
                                    <option value="<?= htmlspecialchars($materia); ?>"><?= ucfirst($materia); ?></option>
                                <?php endforeach;
                            endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="file" name="archivo" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">📤 Subir archivo</button>
                    </div>
                </div>
            </form>

            <h4 class="mt-4" style="color: #0b0146;"><i class="bi bi-folder-fill text-warning"></i> Material Disponible</h4>
            <?php
            if (isset($_SESSION['materias']) && is_array($_SESSION['materias'])):
                foreach ($_SESSION['materias'] as $materia) {
                    echo "<strong>" . ucfirst($materia) . "</strong><br>";
                    $ruta = $directorioBase . $materia . "/";
                    if (is_dir($ruta)) {
                        $archivos = array_diff(scandir($ruta), ['.', '..']);
                        echo "<ul>";
                        foreach ($archivos as $archivo) {
                            echo "<li>📄 " . htmlspecialchars($archivo) . " 
                                <a href='$ruta$archivo' class='btn btn-sm btn-success' download>Descargar</a>";
                            echo " <button class='btn btn-sm btn-danger' onclick=\"eliminarArchivo('$materia', '$archivo')\">Eliminar</button>";
                            echo "</li>";
                        }
                        echo "</ul>";
                    }
                }
            else:
                echo "<p>No hay materias asignadas o no se pudieron cargar correctamente.</p>";
            endif;
            ?>

        <?php elseif ($_SESSION['rol'] === 'estudiante'): ?>
            <h4 class="mt-4" style="color: #0b0146;"><i class="bi bi-folder-fill text-warning"></i> Material Disponible</h4>
            <?php
            if (isset($_SESSION['materias']) && is_array($_SESSION['materias'])):
                foreach ($_SESSION['materias'] as $materia) {
                    echo "<strong>" . ucfirst($materia) . "</strong><br>";
                    $ruta = $directorioBase . $materia . "/";
                    if (is_dir($ruta)) {
                        $archivos = array_diff(scandir($ruta), ['.', '..']);
                        if (count($archivos) > 0) {
                            echo "<ul>";
                            foreach ($archivos as $archivo) {
                                echo "<li>📄 " . htmlspecialchars($archivo) . " 
                                    <a href='$ruta$archivo' class='btn btn-sm btn-success' download>Descargar</a>
                                </li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>No hay archivos disponibles para esta materia.</p>";
                        }
                    }
                }
            else:
                echo "<p>No hay materias asignadas o no se pudieron cargar correctamente.</p>";
            endif;
            ?>

        <?php elseif ($_SESSION['rol'] === 'admin'): ?>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <select name="materia" class="form-control" required>
                            <?php 
                            if (isset($_SESSION['materias']) && is_array($_SESSION['materias'])):
                                foreach ($_SESSION['materias'] as $materia): ?>
                                    <option value="<?= htmlspecialchars($materia); ?>"><?= ucfirst($materia); ?></option>
                                <?php endforeach;
                            endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="file" name="archivo" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">📤 Subir archivo</button>
                    </div>
                </div>
            </form>

            <h4 class="mt-4" style="color: #0b0146;"><i class="bi bi-folder-fill text-warning"></i> Material Disponible</h4>
            <?php
            if (isset($_SESSION['materias']) && is_array($_SESSION['materias'])):
                foreach ($_SESSION['materias'] as $materia) {
                    echo "<strong>" . ucfirst($materia) . "</strong><br>";
                    $ruta = $directorioBase . $materia . "/";
                    if (is_dir($ruta)) {
                        $archivos = array_diff(scandir($ruta), ['.', '..']);
                        echo "<ul>";
                        foreach ($archivos as $archivo) {
                            echo "<li>📄 " . htmlspecialchars($archivo) . " 
                                <a href='$ruta$archivo' class='btn btn-sm btn-success' download>Descargar</a>";
                            echo " <button class='btn btn-sm btn-danger' onclick=\"eliminarArchivo('$materia', '$archivo')\">Eliminar</button>";
                            echo "</li>";
                        }
                        echo "</ul>";
                    }
                }
            else:
                echo "<p>No hay materias asignadas o no se pudieron cargar correctamente.</p>";
            endif;
            ?>

        <?php endif; ?>
    </div>
</div>

<?php include("partials/footer.php"); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="script.js"></script>

</body>
</html>
