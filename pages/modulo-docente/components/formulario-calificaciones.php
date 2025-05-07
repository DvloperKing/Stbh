<?php
// formulario-calificaciones.php
if (!isset($_GET['grupo'])) {
    echo "<p class='text-danger'>No se especificó un grupo.</p>";
    return;
}
$grupoId = (int)$_GET['grupo'];

// 1) Obtener la asignación materia↔grupo (tomamos la primera si hubiera varias)
$sqlGsa = "
  SELECT 
    gsa.id            AS gsa_id,
    gsa.id_subject    AS subject_id,
    g.id_modality_level,
    sub.semester
  FROM group_subject_assignment gsa
  JOIN grupos g  ON gsa.id_group = g.id
  JOIN subjects sub ON sub.id = gsa.id_subject
  WHERE g.id = ?
  LIMIT 1
";
$stmt = $pdo->prepare($sqlGsa);
$stmt->execute([$grupoId]);
$asig = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$asig) {
    echo "<p class='text-danger'>Este grupo no tiene asignada ninguna materia.</p>";
    return;
}

$gsaId     = (int)$asig['gsa_id'];
$subjectId = (int)$asig['subject_id'];
$semester  = (int)$asig['semester'];

// 2) Obtener total_units de subject_units_by_semester
$sqlUnits = "
  SELECT total_units
    FROM subject_units_by_semester
   WHERE id_subject = ? 
     AND semester   = ?
   LIMIT 1
";
$stmt = $pdo->prepare($sqlUnits);
$stmt->execute([$subjectId, $semester]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "<p class='text-danger'>No se definieron unidades para esta materia/semestre.</p>";
    return;
}

$totalUnits = (int)$row['total_units'];

// 3) Obtener alumnos inscritos en este grupo
$sqlAlumnos = "
  SELECT 
    sga.id               AS student_subject_id,
    u.id                 AS user_id,
    u.first_name,
    u.last_name
  FROM student_group_assignment sga
  JOIN student_subjects           ss ON ss.id = sga.student_subject_id
  JOIN users                      u  ON u.id  = ss.id_user
  WHERE sga.group_subject_assignment_id = ?
";
$stmt = $pdo->prepare($sqlAlumnos);
$stmt->execute([$gsaId]);
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4) Indexar calificaciones existentes
$sqlGrades = "SELECT id_enrollment, unit_number, grade FROM grades_per_unit";
$allGrades = $pdo->query($sqlGrades)->fetchAll(PDO::FETCH_ASSOC);

$califIdx = [];
foreach ($allGrades as $g) {
    // aquí asumimos id_enrollment === student_subject_id
    $califIdx[$g['id_enrollment']][$g['unit_number']] = $g['grade'];
}

// 5) Modo edición
if (isset($_POST['editar'])) {
    $_SESSION['modo_edicion'] = true;
} elseif (isset($_POST['guardar']) || isset($_POST['cancelar'])) {
    unset($_SESSION['modo_edicion']);
}
$modo_edicion = !empty($_SESSION['modo_edicion']);
?>

<form method="POST" action="">
    <input type="hidden" name="grupo" value="<?= $grupoId ?>">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Estudiante</th>
                    <?php for ($i = 1; $i <= $totalUnits; $i++): ?>
                        <th>Unidad <?= $i ?></th>
                    <?php endfor; ?>
                    <th>Promedio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($alumnos as $al): ?>
                <?php 
                    $idSS = $al['student_subject_id'];
                    $suma = 0; $cnt = 0;
                ?>
                <tr>
                    <td><?= htmlspecialchars($al['first_name'].' '.$al['last_name']) ?></td>
                    <?php for ($u = 1; $u <= $totalUnits; $u++):
                        $val = $califIdx[$idSS][$u] ?? '';
                        if (is_numeric($val)) { $suma += $val; $cnt++; }
                    ?>
                        <td>
                        <?php if ($modo_edicion): ?>
                            <input type="number"
                                   name="grades[<?= $idSS ?>][<?= $u ?>]"
                                   value="<?= htmlspecialchars($val) ?>"
                                   class="form-control form-control-sm"
                                   min="0" max="100" step="0.1">
                        <?php else: ?>
                            <?= is_numeric($val) ? htmlspecialchars($val) : '-' ?>
                        <?php endif; ?>
                        </td>
                    <?php endfor; ?>
                    <td class="text-center fw-bold">
                        <?= $cnt ? number_format($suma/$cnt,1) : '-' ?>
                    </td>
                    <td class="text-center">
                        <?php if ($modo_edicion): ?>
                            <button name="guardar" class="btn btn-success btn-sm">
                                <i class="bi bi-save"></i>
                            </button>
                            <button name="cancelar" class="btn btn-secondary btn-sm">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        <?php else: ?>
                            <button name="editar" class="btn btn-warning btn-sm">
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
