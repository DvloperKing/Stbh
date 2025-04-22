<?php
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
  $id = (int) $_POST['eliminar_id'];
  $MYSQLI->query("DELETE FROM teacher_subjects WHERE id = $id");
  echo "<div class='alert alert-success text-center mt-3'>Asignación eliminada correctamente.</div>";
}

$modalidades_result = $MYSQLI->query("SELECT DISTINCT id, name_modality FROM modalities ORDER BY name_modality");
$modalidades = [];
while ($row = $modalidades_result->fetch_assoc()) {
  $modalidades[] = $row;
}

$niveles_result = $MYSQLI->query("SELECT DISTINCT id, name_level FROM education_levels ORDER BY name_level");
$niveles = [];
while ($row = $niveles_result->fetch_assoc()) {
  $niveles[] = $row;
}

$selected_modality = $_GET['modality'] ?? '';
$selected_semester = $_GET['semester'] ?? '';
$selected_level = $_GET['level'] ?? '';

$where = "WHERE u.id_perfil = 2";
if ($selected_modality !== '') {
  $mod = (int)$selected_modality;
  $where .= " AND ts.id_modality = $mod";
}
if ($selected_semester !== '') {
  $sem = (int)$selected_semester;
  $where .= " AND s.semester = $sem";
}
if ($selected_level !== '') {
  $lvl = (int)$selected_level;
  $where .= " AND ts.id_level = $lvl";
}

$grupos = $MYSQLI->query("SELECT 
    ts.id,
    m.name_modality AS modalidad,
    el.name_level AS nivel,
    s.semester,
    s.name_subject AS materia,
    s.code AS codigo,
    CONCAT(u.first_name, ' ', u.last_name) AS docente
  FROM teacher_subjects ts
  JOIN users u ON ts.id_user = u.id
  JOIN subjects s ON ts.id_subject = s.id
  JOIN modalities m ON ts.id_modality = m.id
  JOIN education_levels el ON ts.id_level = el.id
  $where
  ORDER BY el.name_level, m.name_modality, s.semester, s.name_subject");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Grupos</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <link href="../assets/css/usuarios.css" rel="stylesheet" />
  <style>
    .btn-primary {
      background-color: #0b0146 !important;
    }
    .btn-success {
      background-color: #f4a701 !important;
    }
    .table-dark {
      --bs-table-bg: #0b0146;
    }
  </style>
</head>
<body class="bg-light">
<div class="logos-container">
  <div class="logos">
    <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../assets/img/CRBH3.png" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="Marca" class="logo-img">
  </div>
</div>
<section class="card-hero">
  <div class="hero-box">
    <h3 class="text-center mb-4">Grupos Académicos</h3>
    <div class="btn-group">
      <a href="admin.php" class="btn-stbh btn-lg">Regresar al Menú Principal</a>
    </div>
  </div>
</section>
<div class="container mt-4">
  <form class="row mb-4" method="get">
    <div class="col-md-4">
      <label class="form-label">Filtrar por modalidad:</label>
      <select class="form-select" name="modality" onchange="this.form.submit()">
        <option value="">Todas</option>
        <?php foreach ($modalidades as $mod): ?>
          <option value="<?= $mod['id'] ?>" <?= $selected_modality == $mod['id'] ? 'selected' : '' ?>>
            <?= $mod['name_modality'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Filtrar por semestre:</label>
      <select class="form-select" name="semester" onchange="this.form.submit()">
        <option value="">Todos</option>
        <?php for ($i = 1; $i <= 6; $i++): ?>
          <option value="<?= $i ?>" <?= $selected_semester == $i ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Filtrar por nivel educativo:</label>
      <select class="form-select" name="level" onchange="this.form.submit()">
        <option value="">Todos</option>
        <?php foreach ($niveles as $lvl): ?>
          <option value="<?= $lvl['id'] ?>" <?= $selected_level == $lvl['id'] ? 'selected' : '' ?>>
            <?= $lvl['name_level'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-bordered text-center bg-white">
      <thead class="table-dark">
        <tr>
          <th>Modalidad</th>
          <th>Semestre</th>
          <th>Materia</th>
          <th>Código</th>
          <th>Docente Asignado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($row = $grupos->fetch_assoc()): ?>
        <tr>
          <td><?= $row['modalidad'] ?></td>
          <td><?= $row['semester'] ?></td>
          <td><?= $row['materia'] ?></td>
          <td><?= $row['codigo'] ?></td>
          <td><?= $row['docente'] ?></td>
          <td>
            <form method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta asignación?');">
              <input type="hidden" name="eliminar_id" value="<?= $row['id'] ?>">
              <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
