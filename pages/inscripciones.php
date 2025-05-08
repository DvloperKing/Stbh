
<?php
session_start();
if (!isset($_SESSION['users']) || $_SESSION['users']['id_perfil'] != 1) {
  header("Location: ../pages/loginPersonal.php");
  exit;
}
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();

// Guardar inscripción del alumno a materias y actualizar semestre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_user'], $_POST['materias'], $_POST['id_modality'], $_POST['semester'], $_POST['id_level'], $_POST['enrollment_year'], $_POST['enrollment_period'])) {
  $id_user = (int) $_POST['id_user'];
  $materias = $_POST['materias'];
  $nuevo_semestre = (int) $_POST['semester'];
  $id_level = (int) $_POST['id_level'];
  $id_modality = (int) $_POST['id_modality'];
  $year = (int) $_POST['enrollment_year'];
  $period = $_POST['enrollment_period'];

  foreach ($materias as $id_materia) {
    $id_materia = (int) $id_materia;
    $check = $MYSQLI->query("SELECT id FROM student_subject_enrollment WHERE id_user = $id_user AND id_subject = $id_materia AND id_modality = $id_modality");
    if ($check->num_rows === 0) {
      $MYSQLI->query("INSERT INTO student_subject_enrollment (id_user, id_subject, id_modality, semester, enrollment_year, enrollment_period)
                      VALUES ($id_user, $id_materia, $id_modality, $nuevo_semestre, $year, '$period')");
    }
  }

  $MYSQLI->query("UPDATE students SET semester = $nuevo_semestre WHERE id_user = $id_user");
  $mensaje = "<div class='alert alert-success text-center'>Materias inscritas correctamente y semestre actualizado.</div>";
}

$niveles = $MYSQLI->query("SELECT id, name_level FROM education_levels");

$modalidades = [];
if (isset($_POST['id_level'])) {
  $id_level = (int) $_POST['id_level'];
  $res_mod = $MYSQLI->query("SELECT id, name_modality FROM modalities WHERE id IN (SELECT id_modality FROM modality_level WHERE id_level = $id_level)");
  while ($row = $res_mod->fetch_assoc()) {
    $modalidades[] = $row;
  }
}

$alumnos = [];
if (isset($_POST['id_modality'], $_POST['id_level'])) {
  $id_modality = (int) $_POST['id_modality'];
  $id_level = (int) $_POST['id_level'];
  $res_alumnos = $MYSQLI->query("SELECT s.id_user AS id, CONCAT(u.first_name, ' ', u.last_name) AS nombre FROM students s JOIN users u ON u.id = s.id_user WHERE s.id_modality = $id_modality AND EXISTS (SELECT 1 FROM modality_level ml WHERE ml.id_modality = s.id_modality AND ml.id_level = $id_level)");
  while ($row = $res_alumnos->fetch_assoc()) {
    $alumnos[] = $row;
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
  <title>STBH | Inscripciones</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/soft-ui-dashboard.css?v=1.1.1" rel="stylesheet" />
  <link href="../assets/css/usuarios.css" rel="stylesheet" />
  <style>
    body { background-color: #f8f9fa; }
    h2 { color: #0b0146; }
    .btn-primary { background-color: #0b0146; border: none; }
    .btn-success { background-color: #f4a701 !important; }
    .table-dark { --bs-table-bg: #0b0146; }
    .form-check-label { font-weight: 500; }
  
    .btn-primary { background-color: #0b0146 !important; }
    .btn-success { background-color: #f4a701 !important; border-color: #f4a701; }
    .table-dark { --bs-table-bg: #0b0146; }
    .container-main { max-width: 1200px; margin: 0 auto; padding: 20px 15px; }
    .card { border-radius: 15px; overflow: hidden; margin-bottom: 40px; }
    .card-body form .form-label { font-weight: 600; }
    .table td, .table th { vertical-align: middle; padding: 0.75rem; }
    .card-hero { margin-bottom: 30px; text-align: center; }
    .hero-box { padding: 20px; }

  .form-label { font-weight: 600; }
  .form-select, .form-control { border-radius: 10px; }
  .form-check-label { font-weight: 500; }
  .btn-warning:hover { background-color: #d99000 !important; }
</style>
</head>

<body class="bg-light">
<div class="logos-container">
  <div class="logos text-center my-3">
    <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../assets/img/CRBH3.png" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="STBH" class="logo-img">
  </div>
</div>

<div class="container-main">

<!-- <div class="card shadow-sm border-0 mb-4">
    <div class="card-body text-center">
      <a href="admin.php" class="btn-stbh btn-lg btn btn-primary btn-lg px-5">
        <i class="bi bi-arrow-left-circle me-2 "></i> Regresar al Menú Principal
      </a>    
    </div>
  </div> -->
  <section class="card-hero d-flex justify-content-center mt-4">
  <div class="d-flex flex-column align-items-center align-content-center">
    <p>
      <a href="admin.php" class="btn btn-lg bg-white custom-btn px25 px-4 fs-4">
        <i class="bi bi-arrow-left-circle me-2" style="font-size: 25px;"></i> Regresar al Menú Principal
      </a>
    </p>
  </div>
</section>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h4 class="mb-4 text-center">Inscripción de Materias</h4>

  <div class="container mt-5">
    <?php if (isset($mensaje)) echo $mensaje; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nivel educativo:</label>
        <select class="form-select" name="id_level" required onchange="this.form.submit()">
          <option value="">-- Elegir --</option>
          <?php while ($lvl = $niveles->fetch_assoc()): ?>
            <option value="<?= $lvl['id'] ?>" <?= isset($_POST['id_level']) && $_POST['id_level'] == $lvl['id'] ? 'selected' : '' ?>><?= $lvl['name_level'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <?php if (!empty($modalidades)): ?>
      <div class="mb-3">
        <label class="form-label">Modalidad:</label>
        <select class="form-select" name="id_modality" required onchange="this.form.submit()">
          <option value="">-- Elegir --</option>
          <?php foreach ($modalidades as $mod): ?>
            <option value="<?= $mod['id'] ?>" <?= isset($_POST['id_modality']) && $_POST['id_modality'] == $mod['id'] ? 'selected' : '' ?>><?= $mod['name_modality'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>

      <?php if (!empty($alumnos)): ?>
      <div class="mb-3">
        <label class="form-label">Alumno:</label>
        <select class="form-select" name="id_user" required>
          <option value="">-- Elegir --</option>
          <?php foreach ($alumnos as $a): ?>
            <option value="<?= $a['id'] ?>" <?= isset($_POST['id_user']) && $_POST['id_user'] == $a['id'] ? 'selected' : '' ?>><?= $a['nombre'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>

      <?php if (isset($_POST['id_modality'], $_POST['id_level'])): ?>
        <div class="col-md-6 mb-3">
        <label class="form-label">Semestre:</label>
        <select class="form-select" name="semester" required onchange="this.form.submit()">
            <option value="" disabled <?= !isset($_POST['semester']) ? 'selected' : '' ?>>Selecciona un Semestre</option>
            <?php for ($i = 1; $i <= 6; $i++): ?>
            <option value="<?= $i ?>" <?= isset($_POST['semester']) && $_POST['semester'] == $i ? 'selected' : '' ?>>
                <?= $i ?>º Semestre
            </option>
            <?php endfor; ?>
        </select>
        </div>
      <?php endif; ?>

      <?php if (!empty($materias)): ?>
        <h5>Materias disponibles:</h5>
        <?php foreach ($materias as $m): ?>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="materias[]" value="<?= $m['id'] ?>" id="materia<?= $m['id'] ?>">
            <label class="form-check-label" for="materia<?= $m['id'] ?>"><?= $m['name_subject'] ?> (<?= $m['code'] ?>)</label>
          </div>
        <?php endforeach; ?>

        <div class="row mt-3">
          <div class="col-md-6">
            <label class="form-label">Año escolar:</label>
            <input type="number" name="enrollment_year" class="form-control" min="<?= date('Y') ?>" max="<?= date('Y') ?>" value="<?= date('Y') ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Periodo escolar:</label>
            <select class="form-select" name="enrollment_period" required>
              <option value="Enero-Junio">Enero-Junio</option>
              <option value="Agosto-Diciembre">Agosto-Diciembre</option>
            </select>
          </div>
        </div>

        <button type="submit" class="btn btn-success mt-4">Inscribir</button>
      <?php elseif (isset($_POST['semester'])): ?>
        <p class="text-danger">No hay materias disponibles para la combinación elegida.</p>
      <?php endif; ?>
    </form>
  </div>
</div>
</div>
</body>
</html>
