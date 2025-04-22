<?php
session_start();
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();

// Guardar asignación de materias a docente sin duplicados y con modalidad y nivel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_user'], $_POST['materias'], $_POST['id_modality'], $_POST['semester'], $_POST['id_level'])) {
  $id_user = (int) $_POST['id_user'];
  $id_modality = (int) $_POST['id_modality'];
  $id_level = (int) $_POST['id_level'];
  $materias = $_POST['materias'];

  foreach ($materias as $id_materia) {
    $id_materia = (int)$id_materia;
    $MYSQLI->query("INSERT INTO teacher_subjects (id_user, id_subject, id_modality, id_level)
      SELECT * FROM (SELECT $id_user AS id_user, $id_materia AS id_subject, $id_modality AS id_modality, $id_level AS id_level) AS tmp
      WHERE NOT EXISTS (
        SELECT 1 FROM teacher_subjects 
        WHERE id_user = $id_user AND id_subject = $id_materia AND id_modality = $id_modality AND id_level = $id_level
      ) LIMIT 1");
  }

  echo "<div class='alert alert-success text-center mt-3'>Materias asignadas correctamente al docente.</div>";
}

$niveles = $MYSQLI->query("SELECT id, name_level FROM education_levels");

$modalidades = [];
if (isset($_POST['id_level'])) {
  $id_level = (int) $_POST['id_level'];
  if ($id_level === 1) {
    $modalidades[] = ['id' => 1, 'name_modality' => 'internado'];
  } else {
    $res_mod = $MYSQLI->query("SELECT id, name_modality FROM modalities WHERE id IN (SELECT id_modality FROM modality_level WHERE id_level = $id_level)");
    while ($row = $res_mod->fetch_assoc()) {
      $modalidades[] = $row;
    }
  }
}

$docentes = [];
if (isset($_POST['id_level'], $_POST['id_modality'])) {
  $res_doc = $MYSQLI->query("SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS nombre FROM users u WHERE u.id_perfil = 2");
  while ($row = $res_doc->fetch_assoc()) {
    $docentes[] = $row;
  }
}

$materias = [];
if (isset($_POST['id_modality'], $_POST['semester'], $_POST['id_level'])) {
  $id_modality = (int) $_POST['id_modality'];
  $semester = (int) $_POST['semester'];
  $id_level = (int) $_POST['id_level'];
  $materias_result = $MYSQLI->query("SELECT sub.id, sub.name_subject, sub.code FROM subject_modality_level sml JOIN subjects sub ON sub.id = sml.id_subject WHERE sml.id_modality = $id_modality AND sml.id_level = $id_level AND sub.semester = $semester");
  while ($row = $materias_result->fetch_assoc()) {
    $materias[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Asignar Materias</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <link href="../assets/css/usuarios.css" rel="stylesheet" />
  <style>
    body { background-color: #f8f9fa; }
    h2 { color: #0b0146; }
    .btn-primary { background-color: #0b0146; border: none; }
    .form-check-label { font-weight: 500; }
    .logos-container {
      background: #fff;
      padding: 10px;
      text-align: center;
      border-bottom: 1px solid #ccc;
    }
    .btn-success { background-color: #f4a701 !important; }
    .table-dark { --bs-table-bg: #0b0146; }
  </style>
</head>
<body>
  <div class="logos-container">
    <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../assets/img/CRBH3.png" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="Marca" class="logo-img">
  </div>

  <section class="card-hero">
    <div class="hero-box">
      <h2 class="mb-4">Asignar Materias a Docentes</h2>
      <div class="btn-group">
        <a href="admin.php" class="btn-stbh btn-lg">Regresar al Menú Principal</a>
      </div>
    </div>
  </section>

  <div class="container mt-5">
    <form method="post">
      <div class="mb-3">
        <label for="id_level" class="form-label">Selecciona nivel educativo:</label>
        <select class="form-select" name="id_level" id="id_level" required onchange="this.form.submit()">
          <option value="">-- Elegir --</option>
          <?php while ($lvl = $niveles->fetch_assoc()): ?>
            <option value="<?= $lvl['id'] ?>" <?= isset($_POST['id_level']) && $_POST['id_level'] == $lvl['id'] ? 'selected' : '' ?>>
              <?= $lvl['name_level'] ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <?php if (!empty($modalidades)): ?>
      <div class="mb-3">
        <label for="id_modality" class="form-label">Selecciona modalidad:</label>
        <select class="form-select" name="id_modality" id="id_modality" required onchange="this.form.submit()">
          <option value="">-- Elegir --</option>
          <?php foreach ($modalidades as $mod): ?>
            <option value="<?= $mod['id'] ?>" <?= isset($_POST['id_modality']) && $_POST['id_modality'] == $mod['id'] ? 'selected' : '' ?>>
              <?= $mod['name_modality'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>

      <?php if (!empty($docentes)): ?>
      <div class="mb-3">
        <label for="id_user" class="form-label">Selecciona un docente:</label>
        <select class="form-select" name="id_user" id="id_user" required>
          <option value="">-- Elegir --</option>
          <?php foreach ($docentes as $doc): ?>
            <option value="<?= $doc['id'] ?>" <?= isset($_POST['id_user']) && $_POST['id_user'] == $doc['id'] ? 'selected' : '' ?>>
              <?= $doc['nombre'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>

      <div class="mb-3">
        <label for="semester" class="form-label">Selecciona semestre:</label>
        <?php if (isset($_POST['id_level']) && $_POST['id_level'] == 2): ?>
          <select class="form-select" name="semester" id="semester" required onchange="this.form.submit()">
            <option value="">-- Elegir semestre --</option>
            <?php for ($i = 1; $i <= 6; $i++): ?>
              <option value="<?= $i ?>" <?= isset($_POST['semester']) && $_POST['semester'] == $i ? 'selected' : '' ?>>
                <?= $i ?>
              </option>
            <?php endfor; ?>
          </select>
        <?php else: ?>
          <input type="number" class="form-control" name="semester" id="semester" value="<?= isset($_POST['semester']) ? $_POST['semester'] : '' ?>" min="1" max="10" required onchange="this.form.submit()">
        <?php endif; ?>
      </div>

      <?php if (!empty($materias)): ?>
        <h5>Materias disponibles:</h5>
        <?php foreach ($materias as $m): ?>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="materias[]" value="<?= $m['id'] ?>" id="materia<?= $m['id'] ?>">
            <label class="form-check-label" for="materia<?= $m['id'] ?>">
              <?= $m['name_subject'] ?> (<?= $m['code'] ?>)
            </label>
          </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary mt-3">Asignar al Docente</button>
      <?php elseif (isset($_POST['id_modality'], $_POST['semester'], $_POST['id_level'])): ?>
        <p class="text-warning">No hay materias disponibles para esta modalidad, nivel y semestre.</p>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>
