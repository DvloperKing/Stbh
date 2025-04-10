<?php
session_start();
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();

// Insertar asignación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_user'], $_POST['id_subject']) && !isset($_POST['eliminar_asignacion'])) {
  $id_user = (int) $_POST['id_user'];
  $materias = $_POST['id_subject']; // array

  foreach ($materias as $id_subject) {
    $id_subject = (int)$id_subject;
    $check = $MYSQLI->query("SELECT id FROM teacher_subjects WHERE id_user = $id_user AND id_subject = $id_subject");
    if ($check->num_rows === 0) {
      $MYSQLI->query("INSERT INTO teacher_subjects (id_user, id_subject) VALUES ($id_user, $id_subject)");
    }
  }
  header("Location: asignar_materias.php?success=1");
  exit;
}

// Eliminar asignación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_asignacion'])) {
  $id_asignacion = (int)$_POST['eliminar_asignacion'];
  $MYSQLI->query("DELETE FROM teacher_subjects WHERE id = $id_asignacion");
  header("Location: asignar_materias.php?deleted=1");
  exit;
}

// Obtener docentes
$docentes = $MYSQLI->query("
  SELECT id, CONCAT(first_name, ' ', last_name) AS nombre 
  FROM users 
  WHERE id_perfil = 2
");

// Obtener materias
$materias = $MYSQLI->query("
  SELECT s.id, CONCAT(s.name_subject, ' (', m.name_modality, ' - ', el.name_level, ')') AS nombre
  FROM subject_modality_level sml
  JOIN subjects s ON s.id = sml.id_subject
  JOIN modalities m ON m.id = sml.id_modality
  JOIN education_levels el ON el.id = sml.id_level
  ORDER BY s.semester, s.name_subject
");

// Consulta de asignaciones actuales
$asignaciones = $MYSQLI->query("
  SELECT ts.id, u.first_name, u.last_name, s.name_subject
  FROM teacher_subjects ts
  JOIN users u ON ts.id_user = u.id
  JOIN subjects s ON ts.id_subject = s.id
  ORDER BY u.first_name, s.name_subject
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Materias</title>
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
    <img src="../assets/img/CRBH.JPG" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="Marca" class="logo-img">
  </div>
</div>
<section class="card-hero">
  <div class="hero-box">
  <h3 class="text-center mb-4">Asignación de Materias a Docentes</h3>
    <div class="btn-group">
      <a href="admin.php" class="btn-stbh btn-lg">Regresar al Menú Principal</a>
    </div>
  </div>
</section>
<div class="container mt-5">

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-auto-close text-center">Materias asignadas correctamente.</div>
  <?php endif; ?>
  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-warning alert-auto-close text-center">Asignación eliminada correctamente.</div>
  <?php endif; ?>

  <div class="text-end mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAsignar">
      <i class="bi bi-plus-circle me-1"></i> Asignar Materia
    </button>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered text-center bg-white">
      <thead class="table-dark">
        <tr>
          <th>Docente</th>
          <th>Materia</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $asignaciones->fetch_assoc()): ?>
          <tr>
            <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
            <td><?= $row['name_subject'] ?></td>
            <td>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="eliminar_asignacion" value="<?= $row['id'] ?>">
                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modalAsignar" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Asignar Materias a un Docente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Docente</label>
          <select name="id_user" class="form-select" required>
            <option value="" disabled selected>Selecciona un docente</option>
            <?php while ($d = $docentes->fetch_assoc()): ?>
              <option value="<?= $d['id'] ?>"><?= $d['nombre'] ?></option>
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
        <button type="submit" class="btn btn-success">Guardar Asignación</button>
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
