<?php
session_start();
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();

// insertar datos de alumno
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_user'], $_POST['control_number'], $_POST['modality'], $_POST['semester'])) {
  $id_user = (int) $_POST['id_user'];
  $control_number = (int) $_POST['control_number'];
  $modality = (int) $_POST['modality'];
  $semester = (int) $_POST['semester'];

  $stmt_check = $MYSQLI->prepare("SELECT id FROM students WHERE id_user = ?");
  $stmt_check->bind_param("i", $id_user);
  $stmt_check->execute();
  $result_check = $stmt_check->get_result();

  if ($result_check && $result_check->num_rows === 0) {
    $stmt_insert = $MYSQLI->prepare("INSERT INTO students (control_number, id_user, id_modality, semester) VALUES (?, ?, ?, ?)");
    $stmt_insert->bind_param("iiii", $control_number, $id_user, $modality, $semester);
    $stmt_insert->execute();
    header("Location: alumno.php?success=1");
    exit;
  }
}

// obtener usuarios con perfil alumno
$SQL = "
  SELECT u.id, u.email, u.first_name, u.last_name, s.control_number, s.semester, m.name_modality
  FROM users u
  LEFT JOIN students s ON u.id = s.id_user
  LEFT JOIN modalities m ON m.id = s.id_modality
  WHERE u.id_perfil = 3
  ORDER BY u.first_name, u.last_name
";
$RESULT = $MYSQLI->query($SQL);
// Modificacion
// para formulario de modalidad
$MODALIDADES = $MYSQLI->query("SELECT * FROM modalities ORDER BY name_modality");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Usuarios</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <link href="../assets/css/usuarios.css" rel="stylesheet" />
</head>
<body>
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
    <h2>Sección Alumnos</h2>
    <div class="btn-group">
      <a href="admin.php" class="btn-stbh btn-lg">Regresar al Menú Principal</a>
    </div>
  </div>
</section>

<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success text-center alert-auto-close">Información del alumno guardada correctamente.</div>
<?php endif; ?>

<section class="users p-4">
  <div class="container">
    <div class="table-responsive">
      <table class="table table-bordered text-center table-stbh">
        <thead>
          <tr>
            <th>Email</th>
            <th>Nombre</th>
            <th>N. Control</th>
            <th>Semestre</th>
            <th>Modalidad</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $RESULT->fetch_assoc()): ?>
          <tr>
            <td><?= $row['email'] ?></td>
            <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
            <td><?= $row['control_number'] ?? '-' ?></td>
            <td><?= $row['semester'] ?? '-' ?></td>
            <td><?= $row['name_modality'] ?? '-' ?></td>
            <td>
              <?php if (!$row['control_number']): ?>
              <button class="btn-stbh btn-sm btn_Completar" data-id="<?= $row['id'] ?>">Completar</button>
              <?php else: ?> - <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- FORMULARIO PARA COMPLETAR DATOS DE ALUMNO -->
<section id="fondo">
  <div id="form_alta">
    <span class="cerrar">&times;</span>
    <form method="POST" action="alumno.php">
      <h2>Completar Información</h2>
      <input type="hidden" name="id_user" id="id_user_input">
      <div class="mb-3">
        <label>Número de control</label>
        <input class="form-control" type="number" name="control_number" required>
      </div>
      <div class="mb-3">
        <label>Semestre</label>
        <input class="form-control" type="number" name="semester" min="1" max="12" required>
      </div>
      <div class="mb-3">
        <label>Modalidad</label>
        <select class="form-control" name="modality" required>
          <option value="" disabled selected>Selecciona</option>
          <?php while ($mod = $MODALIDADES->fetch_assoc()): ?>
            <option value="<?= $mod['id'] ?>"><?= $mod['name_modality'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <button type="submit" class="btn-stbh">Guardar</button>
    </form>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const fondo = document.getElementById('fondo');
  const cerrar = document.querySelector('.cerrar');
  const idInput = document.getElementById('id_user_input');

  document.querySelectorAll('.btn_Completar').forEach(btn => {
    btn.addEventListener('click', () => {
      idInput.value = btn.getAttribute('data-id');
      fondo.style.display = 'block';
    });
  });

  cerrar.addEventListener('click', () => fondo.style.display = 'none');

  const alerta = document.querySelector('.alert-auto-close');
  if (alerta) {
    setTimeout(() => alerta.style.display = 'none', 4000);
  }
});
</script>
</body>
</html>
