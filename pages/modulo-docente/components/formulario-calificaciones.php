<?php
require_once '../../../bd/conexion.php';

// Obtener unidades de la materia 'Matemáticas'
$sqlUnidades = "SELECT s.id AS subject_id, su.total_units
                FROM subjects s
                JOIN subject_units su ON s.id = su.id_subject
                WHERE s.name_subject = 'Matemáticas'";
$unidadMat = $pdo->query($sqlUnidades)->fetch(PDO::FETCH_ASSOC);

if (!$unidadMat) {
    echo "<p>No se encontró la materia 'Matemáticas' o no tiene unidades asignadas.</p>";
    return;
}

$subjectId = $unidadMat['subject_id'];
$totalUnits = (int)$unidadMat['total_units'];

// Obtener estudiantes asignados a Matemáticas
$sqlAsignaciones = "SELECT ss.id AS student_subject_id, ss.id_user, st.first_name, st.last_name
                    FROM student_subjects ss
                    JOIN students st ON ss.id_user = st.id_user
                    WHERE ss.id_subject = ?";
$stmt = $pdo->prepare($sqlAsignaciones);
$stmt->execute([$subjectId]);
$asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Eliminar duplicados si vienen del lado del cliente
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

// Activar modo edición si se presionó "Editar"
$modo_edicion = isset($_POST['editar']);
?>

<form method="POST" action="../../guardar_calificaciones.php">
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
                        for ($i = 1; $i <= $totalUnits; $i++):
                            $valor = $califIndexadas[$a['student_subject_id']][$i] ?? '';
                            $suma += is_numeric($valor) ? floatval($valor) : 0;
                            $contador += is_numeric($valor) ? 1 : 0;
                        ?>
                            <td>
                                <input type="number"
                                    name="grades[<?= $a['student_subject_id'] ?>][<?= $i ?>]"
                                    value="<?= htmlspecialchars($valor) ?>"
                                    class="form-control form-control-sm"
                                    min="0" max="100" step="0.1" <?= !$modo_edicion ? 'readonly' : '' ?>>
                            </td>
                        <?php endfor; ?>

                        <td class="text-center fw-bold">
                            <?= $contador ? number_format($suma / $contador, 1) : '-' ?>
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
