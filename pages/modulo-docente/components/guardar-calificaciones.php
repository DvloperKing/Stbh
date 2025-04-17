<?php
session_start(); // Iniciar sesión antes de cualquier salida
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grades'])) {
    $grades = $_POST['grades'];

    try {
        $pdo->beginTransaction();

        foreach ($grades as $studentSubjectId => $unidades) {
            foreach ($unidades as $unitNumber => $grade) {
                if ($grade === '') continue;

                // Verificar si ya existe calificación
                $checkSql = "SELECT COUNT(*) FROM grades WHERE id_student_subject = ? AND unit_number = ?";
                $checkStmt = $pdo->prepare($checkSql);
                $checkStmt->execute([$studentSubjectId, $unitNumber]);
                $exists = $checkStmt->fetchColumn();

                if ($exists) {
                    // Actualizar
                    $updateSql = "UPDATE grades SET grade = ? WHERE id_student_subject = ? AND unit_number = ?";
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([$grade, $studentSubjectId, $unitNumber]);
                } else {
                    // Insertar
                    $insertSql = "INSERT INTO grades (id_student_subject, unit_number, grade) VALUES (?, ?, ?)";
                    $insertStmt = $pdo->prepare($insertSql);
                    $insertStmt->execute([$studentSubjectId, $unitNumber, $grade]);
                }
            }
        }

        $pdo->commit();

        unset($_SESSION['modo_edicion']); // Apaga modo edición después de guardar

        header("Location: ../lista-alumnos.php"); // Ruta corregida si lista-alumnos está un nivel arriba
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo "Error al guardar calificaciones: " . $e->getMessage();
    }

} else {
    header("Location: ../lista-alumnos.php"); // Ruta corregida si entran directamente
    exit;
}