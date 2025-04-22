<?php
session_start();

/*
// VALIDACIÓN REAL (descomentar en producción)
if (!isset($_SESSION['alumnos'])) {
    header("Location: ../loginAlum.php");
    exit;
}
*/

// DATOS DE PRUEBA
$_SESSION['alumnos'] = [
    'id' => 1,
    'id_user' => 1,
    'first_name' => 'Eliezer',
    'last_name' => 'Hernandez Geronimo',
    'control_number' => '21070390',
    'semester' => '2'
];

$materias_alumno = [
    ['id' => 1, 'name_subject' => 'Matemáticas', 'code' => 'MAT101', 'semester' => 2],
    ['id' => 2, 'name_subject' => 'Historia', 'code' => 'HIS201', 'semester' => 2],
];

$horarios_alumno = [
    ['dia_semana' => 1, 'hora_inicio' => '08:00', 'hora_fin' => '09:30', 'name_subject' => 'Matemáticas', 'aula' => 'A1', 'id_teacher' => 1],
    ['dia_semana' => 3, 'hora_inicio' => '10:00', 'hora_fin' => '11:30', 'name_subject' => 'Historia', 'aula' => 'B2', 'id_teacher' => 2],
];

$calificaciones_alumno = [
    ['name_subject' => 'Matemáticas', 'parcial1' => 8, 'parcial2' => 7, 'parcial3' => 9],
    ['name_subject' => 'Historia', 'parcial1' => 6, 'parcial2' => 6, 'parcial3' => 6],
];

$semestre = $_SESSION['alumnos']['semester'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Módulo Alumno</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/usuarios.css"> <!-- Reutilizado -->
  <style>
 
.nav-link {
    color: #0b0146 !important;
    font-weight: bold;
}
.text-primary {
    color: #0b0146 !important;
    font-weight: bold;
}
.bg-primary {
    background-color: #0b0146 !important;
}
</style>
</head>

<body>

<!-- Logos -->
<div class="logos-container">
  <div class="logos">
    <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../assets/img/CRBH3.png" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="Marca" class="logo-img">
  </div>
</div>

<!-- Card de Bienvenida -->
<section class="card-hero">
  <div class="hero-box">
    <h2>Bienvenido, <?= $_SESSION['alumnos']['first_name'] . ' ' . $_SESSION['alumnos']['last_name'] ?></h2>
    <p>Número de control: <strong><?= $_SESSION['alumnos']['control_number'] ?></strong></p>
    <span class="badge bg-primary">Semestre: <?= $semestre ?></span>
    <br><br><small>Fecha: <?= date("d/m/Y") ?></small>
  </div>
</section>

<!-- Tabs -->
<div class="container mt-4">
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#horarios">Horarios</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#calificaciones">Calificaciones</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#material">Material Didáctico</button></li>
    <a href="../logout.php"><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#cerrarsesion">Cerrar Sesión</button></li></a>
</ul>

  <div class="tab-content border border-top-0 rounded-bottom p-3 mt-2">
    <!-- Horarios -->
    <div class="tab-pane fade show active" id="horarios">
      <h4 class="mb-3 text-primary">Mis Horarios</h4>
      <table class="table table-bordered text-center table-stbh">
        <thead><tr><th>Día</th><th>Hora</th><th>Materia</th><th>Aula</th><th>Docente</th></tr></thead>
        <tbody>
          <?php foreach ($horarios_alumno as $h): ?>
            <tr>
              <td><?= obtenerDiaSemana($h['dia_semana']) ?></td>
              <td><?= $h['hora_inicio'] ?> - <?= $h['hora_fin'] ?></td>
              <td><?= $h['name_subject'] ?></td>
              <td><?= $h['aula'] ?></td>
              <td><?= obtenerNombreDocente($h['id_teacher']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Calificaciones -->
    <div class="tab-pane fade" id="calificaciones">
      <h4 class="mb-3 text-primary">Mis Calificaciones</h4>
      <table class="table table-bordered text-center table-stbh">
        <thead><tr><th>Materia</th><th>Parcial 1</th><th>Parcial 2</th><th>Parcial 3</th><th>Promedio</th><th>Estado</th></tr></thead>
        <tbody>
          <?php foreach ($calificaciones_alumno as $c): ?>
            <?php $prom = calcularPromedio($c); ?>
            <tr>
              <td><?= $c['name_subject'] ?></td>
              <td><?= $c['parcial1'] ?></td>
              <td><?= $c['parcial2'] ?></td>
              <td><?= $c['parcial3'] ?></td>
              <td><strong><?= $prom ?></strong></td>
              <td><?= obtenerEstado($prom) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Material Didáctico -->
    <div class="tab-pane fade" id="material">
      <h4 class="mb-3 text-primary">Material Didáctico</h4>
      <div class="row">
        <?php foreach ($materias_alumno as $materia): ?>
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-header bg-stbh text-white">
                <?= $materia['name_subject'] ?>
                <span class="badge badge-semester float-end">Sem <?= $materia['semester'] ?></span>
              </div>
              <div class="card-body">
                <ul class="list-group">
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    archivo_ejemplo.pdf
                    <a href="#" class="btn-stbh btn-sm">Descargar</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="footer py-4 text-center text-secondary">
  STBH © <?= date('Y') ?> | Todos los derechos reservados
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// === FUNCIONES AUXILIARES ===
function obtenerDiaSemana($numeroDia) {
    $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado'];
    return $dias[$numeroDia] ?? 'Desconocido';
}

function obtenerNombreDocente($id_teacher) {
    $docentes = [1 => 'Mtro. Luis García', 2 => 'Lic. Ana Ramírez'];
    return $docentes[$id_teacher] ?? 'Por asignar';
}

function calcularPromedio($c) {
    $sum = $c['parcial1'] + $c['parcial2'] + $c['parcial3'];
    return round($sum / 3, 1);
}

function obtenerEstado($prom) {
    return $prom >= 6 
        ? '<span class="badge bg-success">Aprobado</span>' 
        : '<span class="badge bg-danger">Reprobado</span>';
}
?>
