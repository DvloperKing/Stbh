<?php
if (!isset($_GET['grupo'])) {
    echo "<p class='text-danger'>No se especificó un grupo.</p>";
    return;
}

$grupoId = $_GET['grupo'];

// Obtener información del grupo, materia, modalidad y semestre
$sqlInfo = "
    SELECT 
        gsa.id AS group_assignment_id,
        s.id AS subject_id,
        s.name_subject,
        s.semester,
        ml.id_modality
    FROM group_subject_assignment gsa
    JOIN subjects s ON gsa.id_subject = s.id
    JOIN grupos g ON g.id = gsa.id_group
    JOIN modality_level ml ON g.id_modality_level = ml.id
    WHERE gsa.id_group = ?
    LIMIT 1
";
$stmtInfo = $pdo->prepare($sqlInfo);
$stmtInfo->execute([$grupoId]);
$info = $stmtInfo->fetch(PDO::FETCH_ASSOC);

if (!$info) {
    echo "<p class='text-danger'>No se encontró información del grupo o la materia.</p>";
    return;
}

$subjectId = $info['subject_id'];
$modalityId = $info['id_modality'];
$semester = $info['semester'];

// Obtener total de unidades de la materia según modalidad y semestre
$sqlUnidades = "
    SELECT total_units
    FROM subject_units_by_semester
    WHERE id_subject = ? AND id_modality = ? AND semester = ?
";
$stmtUnidades = $pdo->prepare($sqlUnidades);
$stmtUnidades->execute([$subjectId, $modalityId, $semester]);
$unidadMat = $stmtUnidades->fetch(PDO::FETCH_ASSOC);

if (!$unidadMat) {
    echo "<p>No se encontraron unidades registradas para esta materia.</p>";
    return;
}

$totalUnits = (int)$unidadMat['total_units'];

// Obtener estudiantes inscritos en esta materia
$sqlAsignaciones = "
    SELECT 
        e.id AS enrollment_id,
        u.id AS id_user,
        u.first_name,
        u.last_name
    FROM student_subject_enrollment e
    JOIN users u ON e.id_user = u.id
    WHERE e.id_subject = ? AND e.id_modality = ? AND e.semester = ?
";
$stmt = $pdo->prepare($sqlAsignaciones);
$stmt->execute([$subjectId, $modalityId, $semester]);
$asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener calificaciones por unidad
$sqlCalificaciones = "
    SELECT id_enrollment, unit_number, grade 
    FROM grades_per_unit
";
$calificaciones = $pdo->query($sqlCalificaciones)->fetchAll(PDO::FETCH_ASSOC);

// Indexar calificaciones
$califIndexadas = [];
foreach ($calificaciones as $c) {
    $califIndexadas[$c['id_enrollment']][$c['unit_number']] = $c['grade'];
}

// Activar o desactivar modo edición
if (isset($_POST['editar'])) {
    $_SESSION['modo_edicion'] = true;
} elseif (isset($_POST['cancelar']) || isset($_POST['guardar'])) {
    unset($_SESSION['modo_edicion']);
}

$modo_edicion = isset($_SESSION['modo_edicion']);
?>

<form method="POST" action="">
    <input type="hidden" name="grupo" value="<?= htmlspecialchars($grupoId) ?>">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <?php for ($i = 1; $i <= $totalUnits; $i++): ?>
                        <th>Unidad <?= $i ?></th>
                    <?php endfor; ?>
                    <th>Promedio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asignaciones as $a): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['first_name'] . ' ' . $a['last_name']) ?></td>
                        <?php
                        $suma = 0;
                        $contador = 0;
                        $enrollmentId = $a['enrollment_id'];
                        for ($i = 1; $i <= $totalUnits; $i++):
                            $valor = $califIndexadas[$enrollmentId][$i] ?? '';
                            $suma += is_numeric($valor) ? floatval($valor) : 0;
                            $contador += is_numeric($valor) ? 1 : 0;
                        ?>
                            <td>
                                <?php if ($modo_edicion): ?>
                                    <input type="number"
                                        name="grades[<?= $enrollmentId ?>][<?= $i ?>]"
                                        value="<?= htmlspecialchars($valor) ?>"
                                        class="form-control form-control-sm"
                                        min="0" max="100" step="0.1">
                                <?php else: ?>
                                    <?= is_numeric($valor) ? htmlspecialchars($valor) : '-' ?>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>

                        <td class="text-center fw-bold">
                            <?= $contador ? number_format($suma / $contador, 1) : '-' ?>
                        </td>

                        <td class="text-center">
                            <?php if ($modo_edicion): ?>
                                <button type="submit" name="guardar" formaction="components/guardar-calificaciones.php" class="btn btn-success btn-sm">
                                    <i class="bi bi-save"></i>
                                </button>
                                <button type="submit" name="cancelar" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-x-lg"></i>
                                </button>
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
