<?php
// Conexión a la base de datos
include('conexion_db.php'); // Asegúrate de tener el archivo de conexión incluido

// Función para obtener las materias asignadas a un profesor
function obtenerMateriasAsignadas($id_profesor) {
    global $conexion;
    $query = "SELECT s.nombre FROM subjects s 
              INNER JOIN teacher_subjects ts ON s.id = ts.id_subject
              WHERE ts.id_user = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_profesor);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $materias = [];
    while ($row = $resultado->fetch_assoc()) {
        $materias[] = $row['nombre'];
    }

    return $materias;
}

// Función para generar carpetas para las materias del profesor
function generarCarpetasProfesor($materias) {
    $directorioBase = 'archivos/';

    // Generamos carpetas para cada materia asignada
    foreach ($materias as $materia) {
        $rutaCarpeta = $directorioBase . $materia;

        // Verificar si la carpeta ya existe
        if (!is_dir($rutaCarpeta)) {
            // Crear la carpeta si no existe
            mkdir($rutaCarpeta, 0777, true);
            echo "Carpeta creada para la materia: " . ucfirst($materia) . "<br>";
        } else {
            echo "La carpeta para la materia " . ucfirst($materia) . " ya existe.<br>";
        }
    }
}

// Detectamos el rol del usuario (profesor) desde la sesión
if ($_SESSION['rol'] === 'profesor') {
    $id_profesor = $_SESSION['id_usuario'];  // Asumiendo que el ID del usuario está en la sesión
    $materiasAsignadas = obtenerMateriasAsignadas($id_profesor);
    
    // Generar las carpetas para las materias del profesor
    generarCarpetasProfesor($materiasAsignadas);
}
?>