<?php
session_start();
if (!isset($_SESSION['users']) || $_SESSION['users']['id_perfil'] != 1) {
  header("Location: ../pages/loginPersonal.php");
  exit;
}

include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();

// Filtro de modalidad
$filtro_modalidad = isset($_GET['modalidad']) ? $MYSQLI->real_escape_string($_GET['modalidad']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['asignar_horario'])) {
    $id_assignment = (int) $_POST['id_assignment'];
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $MYSQLI->query("INSERT INTO schedules (id_assignment, day_of_week, start_time, end_time)
                    VALUES ($id_assignment, '$day_of_week', '$start_time', '$end_time')");
    header("Location: horarios.php?horario_asignado=1");
    exit;
  }

  if (isset($_POST['actualizar_horario'])) {
    $id_schedule = (int) $_POST['id_schedule'];
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $MYSQLI->query("UPDATE schedules SET day_of_week = '$day_of_week', start_time = '$start_time', end_time = '$end_time'
                    WHERE id = $id_schedule");
    header("Location: horarios.php?horario_actualizado=1");
    exit;
  }
}

// Cargar modalidades para el filtro
$modalidades_result = $MYSQLI->query("SELECT DISTINCT name_modality FROM modalities ORDER BY name_modality");

// Cargar asignaciones existentes
$asignaciones_result = $MYSQLI->query("
  SELECT 
    ga.id, 
    CONCAT(g.name, ' - ', s.name_subject, ' (', u.first_name, ' ', u.last_name, ')') AS asignacion
  FROM group_subject_assignment ga
  JOIN grupos g ON ga.id_group = g.id
  JOIN subjects s ON ga.id_subject = s.id
  JOIN users u ON ga.id_teacher = u.id
  ORDER BY g.name, s.name_subject
");

// Cargar horarios existentes con filtro de modalidad
$query_horarios = "
  SELECT 
    sc.id AS id_schedule,
    g.name AS grupo,
    m.name_modality AS modalidad,
    e.name_level AS nivel,
    s.name_subject AS materia,
    CONCAT(u.first_name, ' ', u.last_name) AS docente,
    sc.day_of_week AS dia,
    sc.start_time AS inicio,
    sc.end_time AS fin
  FROM schedules sc
  JOIN group_subject_assignment ga ON sc.id_assignment = ga.id
  JOIN grupos g ON ga.id_group = g.id
  JOIN modality_level ml ON g.id_modality_level = ml.id
  JOIN modalities m ON ml.id_modality = m.id
  JOIN education_levels e ON ml.id_level = e.id
  JOIN subjects s ON ga.id_subject = s.id
  JOIN users u ON ga.id_teacher = u.id";

if ($filtro_modalidad !== '') {
  $query_horarios .= " WHERE m.name_modality = '$filtro_modalidad'";
}

$query_horarios .= " ORDER BY g.name, sc.day_of_week, sc.start_time";
$horarios_result = $MYSQLI->query($query_horarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Horarios</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/soft-ui-dashboard.css?v=1.1.1" rel="stylesheet" />
  <link href="../assets/css/usuarios.css" rel="stylesheet" />
  <link href="../assets/css/horarios.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body class="bg-light">
<div class="logos-container">
  <div class="logos">
    <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../assets/img/CRBH3.png" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="STBH" class="logo-img">
  </div>
</div>

<div class="container-main">
<section class="card-hero d-flex justify-content-center mt-4">
  <div class="d-flex flex-column align-items-center align-content-center">
    <p>
      <a href="admin.php" class="btn btn-lg bg-white custom-btn px25 px-4 fs-4">
        <i class="bi bi-arrow-left-circle me-2" style="font-size: 25px;"></i> Regresar al Menú Principal
      </a>
    </p>
  </div>
</section>

  <?php if (isset($_GET['horario_asignado'])): ?>
    <div class="alert alert-success text-center">Horario asignado correctamente.</div>
  <?php endif; ?>
  <?php if (isset($_GET['horario_actualizado'])): ?>
    <div class="alert alert-warning text-center">Horario actualizado correctamente.</div>
  <?php endif; ?>
  <!-- Formulario para asignar horario -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h4 class="mb-4 text-center ">Asignar Horario a una Materia de Grupo</h4>
      <form method="post" class="row g-4">
        <input type="hidden" name="asignar_horario" value="1">
        <div class="col-md-5">
          <label class="form-label">Asignación:</label>
          <select name="id_assignment" class="form-select" required>
            <option value="">Seleccione una asignación</option>
            <?php while ($row = $asignaciones_result->fetch_assoc()): ?>
              <option value="<?= $row['id'] ?>"><?= $row['asignacion'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Día:</label>
          <select name="day_of_week" class="form-select" required>
            <option value="">Seleccione un día</option>
            <option>Lunes</option>
            <option>Martes</option>
            <option>Miércoles</option>
            <option>Jueves</option>
            <option>Viernes</option>
            <option>Sábado</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Inicio:</label>
          <input type="time" name="start_time" class="form-control" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Fin:</label>
          <input type="time" name="end_time" class="form-control" required>
        </div>
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-success px-4">Guardar Horario</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Filtro de modalidad -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <form method="get" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Filtrar por modalidad:</label>
          <select name="modalidad" class="form-select" onchange="this.form.submit()">
            <option value="">Todas las modalidades</option>
            <?php while ($row = $modalidades_result->fetch_assoc()): ?>
              <option value="<?= $row['name_modality'] ?>" <?= $filtro_modalidad === $row['name_modality'] ? 'selected' : '' ?>>
                <?= $row['name_modality'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
          <!-- Botón para habilitar edición -->
        <div class="text-end mb-3">
        <button id="btn-habilitar-edicion" class="btn btn-warning">Actualizar Horarios</button>
        </div>
      </form>
    </div>
  </div>
    <!-- Tabla de horarios registrados con formularios de actualización -->
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h4 class="mb-4 text-center">Horarios Registrados<?= $filtro_modalidad ? ' - ' . htmlspecialchars($filtro_modalidad) : '' ?></h4>
    <div class="table-responsive">
      <table class="table table-bordered text-center">
        <thead style="background-color: #0b0146; color: white;">
          <tr>
            <th>Grupo</th>
            <th>Modalidad</th>
            <th>Nivel</th>
            <th>Materia</th>
            <th>Docente</th>
            <th>Día</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $horarios_result->fetch_assoc()): ?>
            <tr>
              <form method="post" class="align-middle">
                <input type="hidden" name="actualizar_horario" value="1">
                <input type="hidden" name="id_schedule" value="<?= $row['id_schedule'] ?>">
                <td><?= $row['grupo'] ?></td>
                <td><?= $row['modalidad'] ?></td>
                <td><?= $row['nivel'] ?></td>
                <td><?= $row['materia'] ?></td>
                <td><?= $row['docente'] ?></td>
                <td>
                  <select name="day_of_week" class="form-select form-select-sm edit-field" disabled>
                    <?php foreach (["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"] as $dia): ?>
                      <option value="<?= $dia ?>" <?= $row['dia'] === $dia ? 'selected' : '' ?>><?= $dia ?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td><input type="time" name="start_time" class="form-control form-control-sm edit-field" value="<?= $row['inicio'] ?>" disabled></td>
                <td><input type="time" name="end_time" class="form-control form-control-sm edit-field" value="<?= $row['fin'] ?>" disabled></td>
                <td><button type="submit" class="btn btn-warning btn-sm edit-field" disabled>Actualizar</button></td>
              </form>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  const btnEditar = document.getElementById('btn-habilitar-edicion');
  btnEditar.addEventListener('click', () => {
    document.querySelectorAll('.edit-field').forEach(el => {
      el.disabled = false;
    });
    btnEditar.disabled = true;
  });
</script>
</body>
</html>
