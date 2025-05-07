<?php
// Obtener días escolares
$consulta = $pdo->query("SELECT dates FROM school_calendar WHERE is_school_day = 1 ORDER BY dates");
$diasEscolares = $consulta->fetchAll(PDO::FETCH_COLUMN);

$grupo = $_GET['grupo'] ?? $_POST['grupo'] ?? null;

if (!$grupo) {
    echo "<div class='alert alert-danger'>No se especificó ningún grupo.</div>";
    return;
}

// Agrupar por semana
$semanas = [];
foreach ($diasEscolares as $fecha) {
    $dt = new DateTime($fecha);
    $clave = $dt->format("o-W");
    $semanas[$clave][] = $fecha;
}

// Determinar la semana seleccionada
$semanaSeleccionada = $_GET['semana'] ?? array_key_first($semanas);
$diasSemana = $semanas[$semanaSeleccionada] ?? [];

// Inserción de nuevas fechas escolares por rango
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertar_fechas'])) {
    $inicio = $_POST['fecha_inicio'] ?? '';
    $fin = $_POST['fecha_fin'] ?? '';

    if ($inicio && $fin && DateTime::createFromFormat('Y-m-d', $inicio) && DateTime::createFromFormat('Y-m-d', $fin)) {
        $start = new DateTime($inicio);
        $end = new DateTime($fin);
        $end->modify('+1 day'); // Incluir el último día

        $interval = new DateInterval('P1D');
        $rango = new DatePeriod($start, $interval, $end);

        foreach ($rango as $fecha) {
            $formato = $fecha->format('Y-m-d');
            $stmt = $pdo->prepare("INSERT IGNORE INTO school_calendar (date, is_school_day) VALUES (?, 1)");
            $stmt->execute([$formato]);
        }

        $_SESSION['mensaje_asistencia'] = "Fechas del $inicio al $fin añadidas correctamente.";
        header("Location: lista-alumnos.php?tab=asistencia&semana=" . urlencode($_POST['semana']) . "&grupo=" . urlencode($_POST['grupo']));
        exit;
    } else {
        $_SESSION['mensaje_asistencia'] = "⚠️ Debes seleccionar una fecha de inicio y fin válidas.";
    }
}

// Guardar asistencia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_asistencia'])) {
    foreach ($_POST['asistencia'] as $idSS => $dias) {
        foreach ($dias as $fecha => $valor) {
            $presente = ($valor === '1') ? 1 : 0;
            $stmt = $pdo->prepare("INSERT INTO attendance (id_student_subject, date, present)
                                   VALUES (?, ?, ?)
                                   ON DUPLICATE KEY UPDATE present = VALUES(present)");
            $stmt->execute([$idSS, $fecha, $presente]);
        }
    }

    $_SESSION['mensaje_asistencia'] = "Asistencia actualizada correctamente!";
    header("Location: lista-alumnos.php?tab=asistencia&semana=" . urlencode($_POST['semana']) . "&grupo=" . urlencode($_POST['grupo']));
    exit;
}
?>

<!-- Formulario para insertar fechas con selector de rango -->
<form method="POST" class="mb-4" id="formulario-fechas">
    <input type="hidden" name="semana" value="<?= htmlspecialchars($semanaSeleccionada) ?>">
    <input type="hidden" name="grupo" value="<?= htmlspecialchars($grupo) ?>">

    <div class="row g-3 align-items-center">
        <div class="col-md-4">
            <label for="fecha_inicio" class="form-label fw-bold">Fecha de inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label for="fecha_fin" class="form-label fw-bold">Fecha de fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
        </div>
        <div class="col-md-4 d-grid gap-2 mt-6">
            <button type="submit" name="insertar_fechas" class="btn btn-success">
                <i class="bi bi-calendar-plus"></i> Agregar rango
            </button>
        </div>
    </div>
</form>


<!-- Selector de semana -->
<form method="GET" action="lista-alumnos.php" class="mb-3">
    <input type="hidden" name="tab" value="asistencia">
    <input type="hidden" name="grupo" value="<?= $grupo ?>">
    <label for="selectorSemana" class="form-label fw-bold">Seleccionar semana:</label>
    <select name="semana" id="selectorSemana" class="form-select" onchange="this.form.submit()">
        <?php foreach ($semanas as $clave => $fechas): ?>
            <option value="<?= $clave ?>" <?= $clave === $semanaSeleccionada ? 'selected' : '' ?>>
                Semana <?= $clave ?> (<?= reset($fechas) ?> - <?= end($fechas) ?>)
            </option>
        <?php endforeach; ?>
    </select>
</form>

<!-- Mensaje de sesión -->
<?php if (isset($_SESSION['mensaje_asistencia'])): ?>
    <div class="alert alert-success"><?= $_SESSION['mensaje_asistencia'] ?></div>
    <?php unset($_SESSION['mensaje_asistencia']); ?>
<?php endif; ?>

<!-- Formulario de asistencia -->
<form method="POST">
    <input type="hidden" name="semana" value="<?= $semanaSeleccionada ?>">
    <input type="hidden" name="grupo" value="<?= $grupo ?>">

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover table-sm">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Estudiante</th>
                    <?php foreach ($diasSemana as $fecha): ?>
                        <th data-fecha="<?= $fecha ?>"><?= date('d/m', strtotime($fecha)) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->prepare("SELECT ss.id AS id_ss, s.first_name, s.last_name 
                                       FROM student_subjects ss
                                       JOIN students s ON ss.id = s.id
                                       WHERE s.id_grupo = ?");
                $stmt->execute([$grupo]);
                $estudiantes = $stmt->fetchAll();

                foreach ($estudiantes as $est):
                ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($est['first_name'] . ' ' . $est['last_name']) ?></td>
                        <?php foreach ($diasSemana as $fecha):
                            $query = $pdo->prepare("SELECT present FROM attendance WHERE id_student_subject = ? AND date = ?");
                            $query->execute([$est['id_ss'], $fecha]);
                            $presente = $query->fetchColumn();
                            $checked = ($presente == 1) ? 'checked' : '';
                        ?>
                            <td>
                                <input type="checkbox" name="asistencia[<?= $est['id_ss'] ?>][<?= $fecha ?>]" value="1" <?= $checked ?> class="form-check-input">
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-end mt-3">
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
        border: 1px solid #6c757d;
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
    const today = new Date().toISOString().split('T')[0];
    const header = document.querySelector(`th[data-fecha="${today}"]`);
    if (header) {
        header.scrollIntoView({ behavior: 'smooth', inline: 'center' });
    }
});

//validación del selector de fechas
document.addEventListener('DOMContentLoaded', () => {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    const formFechas = document.getElementById('formulario-fechas');

    // Actualizar límites al seleccionar fecha de inicio
    fechaInicio.addEventListener('change', () => {
        const inicio = new Date(fechaInicio.value);
        if (!isNaN(inicio.getTime())) {
            const maxFin = new Date(inicio);
            maxFin.setDate(inicio.getDate() + 4);

            const minFinStr = fechaInicio.value;
            const maxFinStr = maxFin.toISOString().split('T')[0];

            fechaFin.min = minFinStr;
            fechaFin.max = maxFinStr;

            // Si fecha_fin actual está fuera de rango, la limpia
            if (fechaFin.value && (fechaFin.value < minFinStr || fechaFin.value > maxFinStr)) {
                fechaFin.value = '';
            }
        }
    });

    // Validación al enviar formulario
    formFechas.addEventListener('submit', (e) => {
        const inicio = new Date(fechaInicio.value);
        const fin = new Date(fechaFin.value);
        const diff = (fin - inicio) / (1000 * 60 * 60 * 24); // días

        if (isNaN(inicio) || isNaN(fin)) return;

        if (diff < 0) {
            alert("⚠️ La fecha de fin no puede ser anterior a la de inicio.");
            e.preventDefault();
        } else if (diff > 5) {
            alert("⚠️ El rango no puede ser mayor a 5 días.");
            e.preventDefault();
        }
    });
});
</script>
