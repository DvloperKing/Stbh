<?php 
// Asume que session_start() y $pdo ya vienen de lista-alumnos.php

// 1. Traer todos los días escolares (is_school_day = 1)
$consulta = $pdo->query("SELECT dates FROM school_calendar WHERE is_school_day = 1 ORDER BY dates");
$diasEscolares = $consulta->fetchAll(PDO::FETCH_COLUMN);

// 2. Recuperar el grupo de la URL o del POST
$grupo = $_GET['grupo'] ?? $_POST['grupo'] ?? null;
if (!$grupo) {
    echo "<div class='alert alert-danger'>No se especificó ningún grupo.</div>";
    return;
}

// 3. Agrupar esos días por semana (clave Año-semana ISO)
$semanas = [];
foreach ($diasEscolares as $fecha) {
    $dt   = new DateTime($fecha);
    $clave = $dt->format("o-W"); // por ejemplo "2025-16"
    $semanas[$clave][] = $fecha;
}

// 4. Semana seleccionada (por GET, POST o por defecto la primera)
$semanaSeleccionada = $_GET['semana'] ?? $_POST['semana'] ?? array_key_first($semanas);
$diasSemana        = $semanas[$semanaSeleccionada] ?? [];

// 5. Inserción masiva de fechas al calendario (rango)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertar_fechas'])) {
    $inicio = $_POST['fecha_inicio'] ?? '';
    $fin    = $_POST['fecha_fin']    ?? '';
    if (
        DateTime::createFromFormat('Y-m-d', $inicio) &&
        DateTime::createFromFormat('Y-m-d', $fin) &&
        $inicio <= $fin
    ) {
        $start = new DateTime($inicio);
        $end   = new DateTime($fin);
        $end->modify('+1 day'); // para incluir el fin
        $interval = new DateInterval('P1D');
        foreach (new DatePeriod($start, $interval, $end) as $d) {
            $stmt = $pdo->prepare("
                INSERT IGNORE INTO school_calendar (dates, is_school_day)
                VALUES (?, 1)
            ");
            $stmt->execute([$d->format('Y-m-d')]);
        }
        $_SESSION['mensaje_asistencia'] = "Fechas del $inicio al $fin añadidas correctamente.";
        header("Location: lista-alumnos.php?tab=asistencia&semana="
               .urlencode($semanaSeleccionada)."&grupo=".urlencode($grupo));
        exit;
    } else {
        $_SESSION['mensaje_asistencia'] = "⚠️ Rango inválido (máximo 5 días y fin ≥ inicio).";
    }
}

// 6. Guardar asistencia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_asistencia'])) {
    foreach ($_POST['asistencia'] as $idSS => $dias) {
        foreach ($dias as $fecha => $valor) {
            $p = ($valor === '1') ? 1 : 0;
            $stmt = $pdo->prepare("
                INSERT INTO attendance (id_student_subject, dates, present)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE present = VALUES(present)
            ");
            $stmt->execute([$idSS, $fecha, $p]);
        }
    }
    $_SESSION['mensaje_asistencia'] = "Asistencia actualizada correctamente!";
    header("Location: lista-alumnos.php?tab=asistencia&semana="
           .urlencode($semanaSeleccionada)."&grupo=".urlencode($grupo));
    exit;
}
?>

<!-- 7. Formulario para agregar rango de fechas -->
<form method="POST" class="mb-4 row g-3 align-items-end" id="formulario-fechas">
  <input type="hidden" name="grupo" value="<?= htmlspecialchars($grupo) ?>">
  <input type="hidden" name="semana" value="<?= htmlspecialchars($semanaSeleccionada) ?>">

  <div class="col-md-4">
    <label class="form-label fw-bold" for="fecha_inicio">Fecha de inicio</label>
    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
  </div>
  <div class="col-md-4">
    <label class="form-label fw-bold" for="fecha_fin">Fecha de fin</label>
    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
  </div>
  <div class="col-md-4 d-grid">
    <button type="submit" name="insertar_fechas" class="btn btn-success">
      <i class="bi bi-calendar-plus"></i> Agregar rango
    </button>
  </div>
</form>

<!-- 8. Selector de semana -->
<form method="GET" class="mb-3 d-flex align-items-center gap-2">
  <input type="hidden" name="tab" value="asistencia">
  <input type="hidden" name="grupo" value="<?= htmlspecialchars($grupo) ?>">
  <label class="form-label fw-bold mb-0" for="selectorSemana">Seleccionar semana:</label>
  <select name="semana" id="selectorSemana" class="form-select w-auto" onchange="this.form.submit()">
    <?php foreach ($semanas as $clave => $fechas): ?>
      <option value="<?= htmlspecialchars($clave) ?>"
        <?= $clave === $semanaSeleccionada ? 'selected' : '' ?>>
        Semana <?= htmlspecialchars($clave) ?> (<?= reset($fechas) ?> - <?= end($fechas) ?>)
      </option>
    <?php endforeach; ?>
  </select>
</form>

<!-- 9. Mensaje de resultado -->
<?php if (isset($_SESSION['mensaje_asistencia'])): ?>
  <div class="alert alert-success"><?= $_SESSION['mensaje_asistencia'] ?></div>
  <?php unset($_SESSION['mensaje_asistencia']); ?>
<?php endif; ?>

<!-- 10. Tabla de asistencia -->
<form method="POST">
  <input type="hidden" name="grupo" value="<?= htmlspecialchars($grupo) ?>">
  <input type="hidden" name="semana" value="<?= htmlspecialchars($semanaSeleccionada) ?>">

  <div class="table-responsive">
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
        // Obtener alumnos de este grupo mediante student_subjects + group_subject_assignment
        $stmt = $pdo->prepare("
          SELECT 
            ss.id    AS id_ss,
            u.first_name,
            u.last_name
          FROM student_subjects ss
          JOIN users u ON ss.id_user = u.id
          JOIN group_subject_assignment gsa
            ON gsa.id_subject = ss.id_subject
          WHERE gsa.id_group = ?
        ");
        $stmt->execute([$grupo]);
        $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($estudiantes as $est):
        ?>
          <tr>
            <td class="fw-bold">
              <?= htmlspecialchars($est['first_name'] . ' ' . $est['last_name']) ?>
            </td>
            <?php foreach ($diasSemana as $fecha):
              // Leer asistencia para cada día
              $q = $pdo->prepare("
                SELECT present 
                  FROM attendance 
                 WHERE id_student_subject = ? 
                   AND dates = ?
              ");
              $q->execute([$est['id_ss'], $fecha]);
              $presente = $q->fetchColumn();
              $checked = ($presente == 1) ? 'checked' : '';
            ?>
              <td>
                <input type="checkbox"
                       name="asistencia[<?= $est['id_ss'] ?>][<?= $fecha ?>]"
                       value="1" <?= $checked ?>
                       class="form-check-input">
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

<!-- 11. Estilos y validación de rango (igual que antes) -->
<style>
  .form-check-input { width:1.2em; height:1.2em; margin-right:5px; border:1px solid #6c757d;}
  .table th, .table td { text-align:center; vertical-align:middle; white-space:nowrap; }
  .table td:first-child {
    text-align:left; font-weight:bold; background:#f8f9fa;
    position:sticky; left:0; z-index:1;
  }
  .table thead th:first-child {
    position:sticky; left:0; z-index:2;
    background:#212529; color:#fff;
  }
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Scroll automático a la columna de hoy
  const today = new Date().toISOString().split('T')[0];
  const header = document.querySelector(`th[data-fecha="${today}"]`);
  if (header) header.scrollIntoView({ behavior:'smooth', inline:'center' });

  // Validación de rango en el formulario de fechas
  const fi = document.getElementById('fecha_inicio'),
        ff = document.getElementById('fecha_fin'),
        formF = document.getElementById('formulario-fechas');

  fi.addEventListener('change', () => {
    const d0 = new Date(fi.value);
    if (!isNaN(d0)) {
      let max = new Date(d0);
      max.setDate(d0.getDate()+4);
      ff.min = fi.value;
      ff.max = max.toISOString().split('T')[0];
      if (ff.value && (ff.value < ff.min || ff.value > ff.max)) ff.value = '';
    }
  });

  formF.addEventListener('submit', e => {
    const d0 = new Date(fi.value),
          d1 = new Date(ff.value),
          diff = (d1 - d0)/(1000*60*60*24);
    if (isNaN(d0) || isNaN(d1)) return;
    if (diff < 0)     { alert("⚠️ Fin antes de inicio."); e.preventDefault(); }
    if (diff > 5)     { alert("⚠️ Rango mayor a 5 días."); e.preventDefault(); }
  });
});
</script>
