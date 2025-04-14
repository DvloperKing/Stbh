<?php
define('JSON_FILE', './asistencia.json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Cargar datos desde el JSON
$datos = json_decode(file_get_contents(JSON_FILE), true);
$estudiantes = array_column($datos['estudiantes'], null, 'id');
$config = $datos['config'];

// 2. Obtener todos los días hábiles entre dos fechas
function obtenerDiasHabiles($inicio, $fin, $festivos) {
    $intervalo = new DateInterval('P1D');
    $periodo = new DatePeriod(new DateTime($inicio), $intervalo, (new DateTime($fin))->modify('+1 day'));

    $diasHabiles = [];
    foreach ($periodo as $fecha) {
        if ($fecha->format('N') < 6 && !in_array($fecha->format('Y-m-d'), $festivos)) {
            $diasHabiles[] = $fecha->format('Y-m-d');
        }
    }
    return $diasHabiles;
}

// 3. Simular la fecha actual del servidor
$fechaActual = '2024-04-05';

// 4. Obtener días hábiles en el periodo escolar
$todosLosDiasHabiles = obtenerDiasHabiles(
    $config['periodo_escolar']['inicio'],
    $config['periodo_escolar']['fin'],
    $config['dias_festivos']
);

// 5. Definir ventana de fechas centrada en la fecha actual
$inicioVentana = (new DateTime($fechaActual))->modify('-3 days')->format('Y-m-d');
$finVentana = (new DateTime($fechaActual))->modify('+7 days')->format('Y-m-d');

// Filtrar días hábiles entre la ventana
$diasHabiles = array_values(array_filter($todosLosDiasHabiles, function($fecha) use ($inicioVentana, $finVentana) {
    return $fecha >= $inicioVentana && $fecha <= $finVentana;
}));

// 6. Guardar asistencia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_asistencia'])) {
    foreach ($_POST['asistencia'] as $idEstudiante => $dias) {
        foreach ($dias as $fecha => $valor) {
            $datos['estudiantes'][$idEstudiante]['asistencia'][$fecha] = ($valor === '1') ? 1 : 0;
        }
    }
    file_put_contents(JSON_FILE, json_encode($datos, JSON_PRETTY_PRINT));
    $_SESSION['mensaje_asistencia'] = "Asistencia actualizada correctamente!";
    header("Location: lista-alumnos.php?tab=asistencia");

    exit();
}
?>

<!-- Mensaje de éxito -->
<?php if (isset($_SESSION['mensaje_asistencia'])): ?>
<div class="alert alert-success alert-dismissible fade show mt-3">
    <?= $_SESSION['mensaje_asistencia'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['mensaje_asistencia']); endif; ?>

<!-- Formulario de asistencia -->
<form method="POST">
    <div class="table-responsive mt-3" style="max-height: 500px; overflow: auto;" id="tabla-asistencia">
        <table class="table table-bordered table-hover table-sm">
            <thead class="bg-dark text-white">
                <tr>
                    <th style="min-width: 200px;">Estudiante</th>
                    <?php foreach ($diasHabiles as $fecha): ?>
                    <th data-fecha="<?= $fecha ?>" style="min-width: 80px;">
                        <?= date('d/m', strtotime($fecha)) ?>
                    </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $est): ?>
                <?php if (!isset($est['nombre'])) continue; ?>
                <tr>
                    <td class="fw-bold"><?= htmlspecialchars($est['nombre']) ?></td>
                    <?php foreach ($diasHabiles as $fecha): 
                        $checked = (isset($est['asistencia'][$fecha]) && $est['asistencia'][$fecha] == 1) ? 'checked' : '';
                    ?>
                    <td>
                        <input type="checkbox"
                               name="asistencia[<?= $est['id'] ?>][<?= $fecha ?>]"
                               value="1" <?= $checked ?>
                               class="form-check-input border border-secondary">
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-end mt-3 mb-5">
        <button type="submit" name="guardar_asistencia" class="btn btn-primary">
            <i class="bi bi-save"></i> Guardar cambios
        </button>
    </div>
</form>

<style>
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        margin-right: 5px;
        border: 1px solid #6c757d; /* Borde gris visible */
    }
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table td:first-child {
        text-align: left;
        font-weight: bold;
        background-color: #f8f9fa;
        position: sticky;
        left: 0;
        z-index: 1;
    }
    .table thead th:first-child {
        position: sticky;
        left: 0;
        z-index: 2;
        background-color: #212529;
        color: white;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const today = '2024-04-05';
    const header = document.querySelector(`th[data-fecha="${today}"]`);
    if (header) {
        header.scrollIntoView({ behavior: 'smooth', inline: 'center' });
    }
});
</script>
