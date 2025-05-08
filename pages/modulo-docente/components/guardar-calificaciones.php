<?php
session_start();
require_once '../conexion.php';

if (!isset($_POST['grades']) || !is_array($_POST['grades'])) {
    echo "<p class='text-danger'>No se recibieron calificaciones válidas.</p>";
    return;
}

// Obtener grupo desde POST
if (!isset($_POST['grupo'])) {
    echo "<p class='text-danger'>No se ha especificado el grupo al guardar.</p>";
    return;
}

$grupo = $_POST['grupo'];
$grades = $_POST['grades'];

$pdo->beginTransaction();

try {
    foreach ($grades as $id_enrollment => $unidades) {
        foreach ($unidades as $unitNumber => $grade) {
            if ($grade === '') continue;

            // Verificar si ya existe la calificación
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM grades_per_unit WHERE id_enrollment = ? AND unit_number = ?");
            $stmt->execute([$id_enrollment, $unitNumber]);
            $exists = $stmt->fetchColumn() > 0;

            if ($exists) {
                $update = $pdo->prepare("UPDATE grades_per_unit SET grade = ? WHERE id_enrollment = ? AND unit_number = ?");
                $update->execute([$grade, $id_enrollment, $unitNumber]);
            } else {
                $insert = $pdo->prepare("INSERT INTO grades_per_unit (id_enrollment, unit_number, grade) VALUES (?, ?, ?)");
                $insert->execute([$id_enrollment, $unitNumber, $grade]);
            }
        }
    }

    $pdo->commit();
    unset($_SESSION['modo_edicion']);

    // Redirigir al listado nuevamente
    header("Location: ../lista-alumnos.php?grupo=" . urlencode($grupo));
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "<p class='text-danger'>Error al guardar las calificaciones: " . $e->getMessage() . "</p>";
}
