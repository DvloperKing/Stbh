<?php
//session_start(); // Primera línea del archivo
define('JSON_FILE', './calificaciones.json');
$tab_activa = isset($_GET['tab']) ? $_GET['tab'] : 'calificaciones';

// Inicializar variable de modo edición correctamente
$modo_edicion = isset($_SESSION['modo_edicion']) ? $_SESSION['modo_edicion'] : false;

// Funciones para manejar datos
function cargarDatos() {
    if (!file_exists(JSON_FILE)) {
        file_put_contents(JSON_FILE, json_encode(['estudiantes' => []]));
    }
    return json_decode(file_get_contents(JSON_FILE), true);
}

function guardarDatos($datos) {
    file_put_contents(JSON_FILE, json_encode($datos, JSON_PRETTY_PRINT));
}

function clamp($value) {
    $num = (int) $value;
    return max(0, min(100, $num));
}

function calcularPromedio($estudiante) {
    $suma = array_sum([
        $estudiante['unidad1'], $estudiante['unidad2'], $estudiante['unidad3'],
        $estudiante['unidad4'], $estudiante['unidad5']
    ]);
    return number_format($suma / 5, 1);
}

// Cargar datos
$datos = cargarDatos();
$estudiantes = array_column($datos['estudiantes'], null, 'id');

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['editar'])) {
            $_SESSION['modo_edicion'] = true;
            $modo_edicion = true;
        } elseif (isset($_POST['guardar'])) {
            foreach ($_POST['calificaciones'] as $id => $califs) {
                if (isset($estudiantes[$id])) {
                    $estudiantes[$id]['unidad1'] = clamp($califs['unidad1']);
                    $estudiantes[$id]['unidad2'] = clamp($califs['unidad2']);
                    $estudiantes[$id]['unidad3'] = clamp($califs['unidad3']);
                    $estudiantes[$id]['unidad4'] = clamp($califs['unidad4']);
                    $estudiantes[$id]['unidad5'] = clamp($califs['unidad5']);
                }
            }
            $datos['estudiantes'] = array_values($estudiantes);
            guardarDatos($datos);
            $_SESSION['modo_edicion'] = false;
            $modo_edicion = false;
            $_SESSION['mensaje'] = "¡Cambios guardados exitosamente!";
        } elseif (isset($_POST['cancelar'])) {
            $_SESSION['modo_edicion'] = false;
            $modo_edicion = false;
        }
        header("Location: lista-alumnos.php?tab=calificaciones");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: lista-alumnos.php?tab=calificaciones");
        exit();
    }
}
?>

<form method="POST">
    <input type="hidden" name="tab" value="<?= $tab_activa ?>">
    <?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-3">
        <?= $_SESSION['mensaje'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['mensaje']); endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Unidad 1</th>
                    <th>Unidad 2</th>
                    <th>Unidad 3</th>
                    <th>Unidad 4</th>
                    <th>Unidad 5</th>
                    <th>Promedio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $est): ?>
                <tr>
                    <td><?= htmlspecialchars($est['nombre']) ?></td>
                    
                    <?php if ($modo_edicion): ?>
                        <?php foreach (['unidad1', 'unidad2', 'unidad3', 'unidad4', 'unidad5'] as $unidad): ?>
                        <td>
                            <input type="number" 
                                   name="calificaciones[<?= $est['id'] ?>][<?= $unidad ?>]" 
                                   value="<?= $est[$unidad] ?>" 
                                   class="form-control form-control-sm" 
                                   min="0" 
                                   max="100"
                                   required>
                        </td>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach (['unidad1', 'unidad2', 'unidad3', 'unidad4', 'unidad5'] as $unidad): ?>
                        <td><?= $est[$unidad] ?></td>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <td class="text-center fw-bold">
                        <?= calcularPromedio($est) ?>
                    </td>
                    
                    <td>
                        <?php if ($modo_edicion): ?>
                            <div class="btn-group btn-group-sm">
                                <button type="submit" name="guardar" class="btn btn-success">
                                    <i class="bi bi-save"></i>
                                </button>
                                <button type="submit" name="cancelar" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        <?php else: ?>
                            <button type="submit" name="editar" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</form>