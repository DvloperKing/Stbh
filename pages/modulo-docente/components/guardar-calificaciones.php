<?php
session_start();
require_once '../conexion.php';

if (!isset($_POST['grades']) || !is_array($_POST['grades'])) {
    echo "<p class='text-danger'>No se recibieron calificaciones válidas.</p>";
    return;
}

// Obtener grupo desde POST (no GET)
if (!isset($_POST['grupo'])) {
    echo "<p class='text-danger'>No se ha especificado el grupo al guardar.</p>";
    return;
}

$grupo = $_POST['grupo'];
$grades = $_POST['grades'];

$pdo->beginTransaction();

try {
    foreach ($grades as $studentSubjectId => $unidades) {
        foreach ($unidades as $unitNumber => $grade) {
            if ($grade === '') continue;

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM grades WHERE id_student_subject = ? AND unit_number = ?");
            $stmt->execute([$studentSubjectId, $unitNumber]);
            $exists = $stmt->fetchColumn() > 0;

            if ($exists) {
                $update = $pdo->prepare("UPDATE grades SET grade = ? WHERE id_student_subject = ? AND unit_number = ?");
                $update->execute([$grade, $studentSubjectId, $unitNumber]);
            } else {
                $insert = $pdo->prepare("INSERT INTO grades (id_student_subject, unit_number, grade) VALUES (?, ?, ?)");
                $insert->execute([$studentSubjectId, $unitNumber, $grade]);
            }
        }
    }

    $pdo->commit();
    unset($_SESSION['modo_edicion']);

    // Redirigir con POST → GET (ya que ahora tenemos el grupo)
    header("Location: ../lista-alumnos.php?grupo=" . urlencode($grupo));
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "<p class='text-danger'>Error al guardar las calificaciones: " . $e->getMessage() . "</p>";
}
