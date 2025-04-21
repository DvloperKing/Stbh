<?php
if (!isset($_GET['grupo'])) {
    echo "<p class='text-danger'>No se especificó un grupo.</p>";
    return;
}

$grupoId = $_GET['grupo'];

// Obtener unidades de la materia 'Matemáticas de prueba'
$sqlUnidades = "SELECT s.id AS subject_id, su.total_units
                FROM subjects s
                JOIN subject_units su ON s.id = su.id_subject
                WHERE s.name_subject LIKE '%Matemáticas%'";
$unidadMat = $pdo->query($sqlUnidades)->fetch(PDO::FETCH_ASSOC);

if (!$unidadMat) {
    echo "<p>No se encontró la materia 'Matemáticas' o no tiene unidades asignadas.</p>";
    return;
}

$subjectId = $unidadMat['subject_id'];
$totalUnits = (int)$unidadMat['total_units'];

// Obtener estudiantes asignados a esta materia Y pertenecientes al grupo actual
$sqlAsignaciones = "SELECT ss.id AS student_subject_id, ss.id_user, st.first_name, st.last_name
                    FROM student_subjects ss
                    JOIN students st ON ss.id_user = st.id_user
                    WHERE ss.id_subject = ? AND st.id_grupo = ?";
$stmt = $pdo->prepare($sqlAsignaciones);
$stmt->execute([$subjectId, $grupoId]);
$asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Eliminar duplicados por id_user
$asignacionesUnicas = [];
$idsVistos = [];

foreach ($asignaciones as $a) {
    if (!in_array($a['id_user'], $idsVistos)) {
        $asignacionesUnicas[] = $a;
        $idsVistos[] = $a['id_user'];
    }
}

// Obtener calificaciones
$sqlCalificaciones = "SELECT id_student_subject, unit_number, grade FROM grades";
$calificaciones = $pdo->query($sqlCalificaciones)->fetchAll(PDO::FETCH_ASSOC);

// Indexar calificaciones
$califIndexadas = [];
foreach ($calificaciones as $c) {
    $califIndexadas[$c['id_student_subject']][$c['unit_number']] = $c['grade'];
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
       <?php if (isset($_GET['grupo'])): ?>
        <input type="hidden" name="grupo" value="<?= htmlspecialchars($_GET['grupo']) ?>">
    <?php endif; ?>
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
                <?php foreach ($asignacionesUnicas as $a): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['first_name'] . ' ' . $a['last_name']) ?></td>
                        <?php
                        $suma = 0;
                        $contador = 0;
                        $studentSubjectId = $a['student_subject_id'];
                        for ($i = 1; $i <= $totalUnits; $i++):
                            $valor = $califIndexadas[$studentSubjectId][$i] ?? '';
                            $suma += is_numeric($valor) ? floatval($valor) : 0;
                            $contador += is_numeric($valor) ? 1 : 0;
                        ?>
                            <td>
                                <?php if ($modo_edicion): ?>
                                    <input type="number"
                                        name="grades[<?= $studentSubjectId ?>][<?= $i ?>]"
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
