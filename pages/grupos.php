<?php
session_start();
if (!isset($_SESSION['users']) || $_SESSION['users']['id_perfil'] != 1) {
  header("Location: ../pages/loginPersonal.php");
  exit;
}

include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();

// Insertar nueva asignación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['asignar_materia'])) {
    $id_group = (int) $_POST['id_group'];
    $id_subject = (int) $_POST['id_subject'];
    $id_teacher = (int) $_POST['id_teacher'];

    $MYSQLI->query("INSERT INTO group_subject_assignment (id_group, id_subject, id_teacher)
                    VALUES ($id_group, $id_subject, $id_teacher)");

    header("Location: " . $_SERVER['PHP_SELF'] . "?asignado=1");
    exit;
  }

  if (isset($_POST['crear_grupo'])) {
    $group_name = _clean($_POST['group_name'], $MYSQLI);
    $id_modality_level = (int) $_POST['id_modality_level'];

    $MYSQLI->query("INSERT INTO grupos (name, id_modality_level) VALUES ('$group_name', $id_modality_level)");
    header("Location: " . $_SERVER['PHP_SELF'] . "?grupo_creado=1");
    exit;
  }
}

// Obtener grupos y su modalidad/nivel
$grupos_result = $MYSQLI->query("SELECT g.id, g.name, m.name_modality, e.name_level, ml.id AS id_modality_level
  FROM grupos g
  JOIN modality_level ml ON g.id_modality_level = ml.id
  JOIN modalities m ON ml.id_modality = m.id
  JOIN education_levels e ON ml.id_level = e.id
  ORDER BY g.name");

$grupos = [];
while ($row = $grupos_result->fetch_assoc()) {
  $grupos[] = $row;
}

// Obtener materias por modalidad/nivel
$materias_result = $MYSQLI->query("SELECT sm.id_subject, s.name_subject, ml.id AS id_modality_level
  FROM subject_modality_level sm
  JOIN subjects s ON sm.id_subject = s.id
  JOIN modality_level ml ON sm.id_modality = ml.id_modality AND sm.id_level = ml.id_level
  ORDER BY s.name_subject");

$materias_por_ml = [];
while ($row = $materias_result->fetch_assoc()) {
  $materias_por_ml[$row['id_modality_level']][] = $row;
}

// Obtener docentes
$docentes_result = $MYSQLI->query("SELECT id, CONCAT(first_name, ' ', last_name) AS nombre FROM users WHERE id_perfil = 2 ORDER BY first_name");
$docentes = [];
while ($row = $docentes_result->fetch_assoc()) {
  $docentes[] = $row;
}

// Consultar modalidad + nivel para crear grupo
$modniv_query = $MYSQLI->query("SELECT ml.id AS id_modality_level, m.name_modality, e.name_level
  FROM modality_level ml
  JOIN modalities m ON ml.id_modality = m.id
  JOIN education_levels e ON ml.id_level = e.id
  ORDER BY m.name_modality, e.name_level");

$modniv_combos = [];
while ($row = $modniv_query->fetch_assoc()) {
  $modniv_combos[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Grupos y Asignación</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <link href="../assets/css/usuarios.css" rel="stylesheet" />
  <style>
    .btn-primary { background-color: #0b0146 !important; }
    .btn-success { background-color: #f4a701 !important; border-color: #f4a701; }
    .table-dark { --bs-table-bg: #0b0146; }
    .container-main { max-width: 1200px; margin: 0 auto; padding: 20px 15px; }
    .card { border-radius: 15px; overflow: hidden; margin-bottom: 40px; }
    .card-body form .form-label { font-weight: 600; }
    .table td, .table th { vertical-align: middle; padding: 0.75rem; }
    .card-hero { margin-bottom: 30px; text-align: center; }
    .hero-box { padding: 20px; }
  </style>
</head>
<body class="bg-light">
<div class="logos-container">
  <div class="logos">
    <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../pages/Stbh-docentes/assets/img/CRBH3.png" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="STBH" class="logo-img">
  </div>
</div>

<div class="container-main">
  <section class="card-hero">
    <div class="hero-box">
      <a href="admin.php" class="btn-stbh btn-lg">Regresar al Menú Principal</a>
    </div>
  </section>

  <?php if (isset($_GET['grupo_creado'])): ?>
    <div class='alert alert-success text-center mt-3'>Grupo creado correctamente.</div>
  <?php endif; ?>

  <!-- Formulario para crear grupo -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h4 class="mb-4 text-center">Crear Nuevo Grupo</h4>
      <form method="post" class="row g-4">
        <input type="hidden" name="crear_grupo" value="1">
        <div class="col-md-6">
          <label class="form-label">Nombre del Grupo:</label>
          <input type="text" class="form-control" name="group_name" required placeholder="Ej: Grupo A - Internado Básico">
        </div>
        <div class="col-md-6">
          <label class="form-label">Modalidad y Nivel:</label>
          <select class="form-select" name="id_modality_level" required>
            <option value="">Seleccione una opción</option>
            <?php foreach ($modniv_combos as $combo): ?>
              <option value="<?= $combo['id_modality_level'] ?>">
                <?= $combo['name_modality'] ?> / <?= $combo['name_level'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-success px-4">Crear Grupo</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Formulario para asignar materia -->
  <?php if (isset($_GET['asignado'])): ?>
    <div class="alert alert-success text-center">Asignación registrada correctamente.</div>
  <?php endif; ?>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h4 class="mb-4 text-center">Asignar Materia y Docente a un Grupo</h4>
      <form method="post" class="row g-4">
        <input type="hidden" name="asignar_materia" value="1">
        <div class="col-md-6">
          <label class="form-label">Grupo:</label>
          <select class="form-select" name="id_group" id="grupo_select" required>
            <option value="">Seleccione un grupo</option>
            <?php foreach ($grupos as $grupo): ?>
              <option value="<?= $grupo['id'] ?>" data-ml="<?= $grupo['id_modality_level'] ?>">
                <?= $grupo['name'] ?> (<?= $grupo['name_modality'] ?> / <?= $grupo['name_level'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Materia:</label>
          <select class="form-select" name="id_subject" id="materia_select" required>
            <option value="">Seleccione un grupo primero</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Docente:</label>
          <select class="form-select" name="id_teacher" required>
            <option value="">Seleccione un docente</option>
            <?php foreach ($docentes as $doc): ?>
              <option value="<?= $doc['id'] ?>"><?= $doc['nombre'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-success px-4">Asignar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabla de asignaciones registradas -->
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h4 class="mb-4 text-center">Asignaciones Registradas</h4>
    <div class="table-responsive">
      <table class="table table-bordered text-center">
        <thead style="background-color: #0b0146; color: white;">
          <tr>
            <th>Grupo</th>
            <th>Modalidad</th>
            <th>Nivel</th>
            <th>Materia</th>
            <th>Nombre del Docente</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $asignaciones_query = "
            SELECT 
              g.name AS grupo,
              m.name_modality,
              e.name_level,
              s.name_subject,
              CONCAT(u.first_name, ' ', u.last_name) AS nombre_docente
            FROM group_subject_assignment ga
            JOIN grupos g ON ga.id_group = g.id
            JOIN modality_level ml ON g.id_modality_level = ml.id
            JOIN modalities m ON ml.id_modality = m.id
            JOIN education_levels e ON ml.id_level = e.id
            JOIN subjects s ON ga.id_subject = s.id
            JOIN users u ON ga.id_teacher = u.id
            ORDER BY g.name, s.name_subject
          ";
          $asignaciones_result = $MYSQLI->query($asignaciones_query);
          while ($fila = $asignaciones_result->fetch_assoc()):
          ?>
          <tr>
            <td><?= $fila['grupo'] ?></td>
            <td><?= $fila['name_modality'] ?></td>
            <td><?= $fila['name_level'] ?></td>
            <td><?= $fila['name_subject'] ?></td>
            <td><?= $fila['nombre_docente'] ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


<script>
  const materiasPorML = <?= json_encode($materias_por_ml) ?>;
  const grupoSelect = document.getElementById('grupo_select');
  const materiaSelect = document.getElementById('materia_select');

  grupoSelect.addEventListener('change', function () {
    const mlId = this.selectedOptions[0].getAttribute('data-ml');
    materiaSelect.innerHTML = '<option value="">Seleccione una materia</option>';

    if (materiasPorML[mlId]) {
      materiasPorML[mlId].forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id_subject;
        opt.textContent = m.name_subject;
        materiaSelect.appendChild(opt);
      });
    }
  });
</script>
</body>
</html>