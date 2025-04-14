<?php
session_start();
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();

// Guardar inscripción del alumno a materias
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_user'], $_POST['id_subject'])) {
  $id_user = (int) $_POST['id_user'];
  $materias = $_POST['id_subject']; // array

  foreach ($materias as $id_subject) {
    $id_subject = (int) $id_subject;
    $check = $MYSQLI->query("SELECT id FROM student_subjects WHERE id_user = $id_user AND id_subject = $id_subject");
    if ($check->num_rows === 0) {
      $MYSQLI->query("INSERT INTO student_subjects (id_user, id_subject) VALUES ($id_user, $id_subject)");
    }
  }
  header("Location: inscripciones.php?success=1");
  exit;
}

// Obtener alumnos
$alumnos = $MYSQLI->query("SELECT id, CONCAT(first_name, ' ', last_name) AS nombre FROM users WHERE id_perfil = 3");

// Obtener materias
$materias = $MYSQLI->query("SELECT s.id, CONCAT(s.name_subject, ' (', m.name_modality, ' - ', el.name_level, ' - Sem ', s.semester, ')') AS nombre
FROM subject_modality_level sml
JOIN subjects s ON s.id = sml.id_subject
JOIN modalities m ON m.id = sml.id_modality
JOIN education_levels el ON el.id = sml.id_level
ORDER BY el.name_level, m.name_modality, s.semester, s.name_subject");

// Consultar inscripciones existentes
$inscripciones = $MYSQLI->query("SELECT ss.id, u.first_name, u.last_name, s.name_subject
FROM student_subjects ss
JOIN users u ON u.id = ss.id_user
JOIN subjects s ON s.id = ss.id_subject
ORDER BY u.first_name, s.name_subject");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Inscripciones</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <link href="../assets/css/usuarios.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="logos-container">
  <div class="logos">
    <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../assets/img/CRBH2.png" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="Marca" class="logo-img">
  </div>
</div>
<section class="card-hero">
  <div class="hero-box">
    <h3 class="text-center mb-4">Inscripción de Materias por Alumno</h3>
    <div class="btn-group">
      <a href="admin.php" class="btn-stbh btn-lg">Regresar al Menú Principal</a>
    </div>
  </div>
</section>
<div class="container mt-5">
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-auto-close text-center">Materias inscritas correctamente.</div>
  <?php endif; ?>

  <div class="text-end mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInscribir">
      <i class="bi bi-plus-circle me-1"></i> Inscribir Materias
    </button>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered text-center bg-white">
      <thead class="table-dark">
        <tr>
          <th>Alumno</th>
          <th>Materia</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $inscripciones->fetch_assoc()): ?>
          <tr>
            <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
            <td><?= $row['name_subject'] ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modalInscribir" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Inscribir Materias</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Alumno</label>
          <select name="id_user" class="form-select" required>
            <option value="" disabled selected>Selecciona un alumno</option>
            <?php while ($a = $alumnos->fetch_assoc()): ?>
              <option value="<?= $a['id'] ?>"><?= $a['nombre'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="mb-3">
          <label>Materias</label>
          <select name="id_subject[]" class="form-select" multiple required>
            <?php while ($m = $materias->fetch_assoc()): ?>
              <option value="<?= $m['id'] ?>"><?= $m['nombre'] ?></option>
            <?php endwhile; ?>
          </select>
          <small class="text-muted">Mantén Ctrl (Windows) o Cmd (Mac) para seleccionar varias</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Guardar Inscripción</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  setTimeout(() => {
    const alert = document.querySelector('.alert-auto-close');
    if (alert) alert.style.display = 'none';
  }, 4000);
</script>
</body>
</html>
