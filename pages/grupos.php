<?php
session_start();
if (!isset($_SESSION['users']) || $_SESSION['users']['id_perfil'] != 1) {
  header("Location: ../pages/loginPersonal.php");
  exit;
}
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();

// INSERTAR NUEVO GRUPO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_grupo'])) {
  $id_subject = (int) $_POST['id_subject'];
  $id_modality_level = (int) $_POST['id_modality_level'];
  $id_grupo = _clean($_POST['id_grupo'], $MYSQLI);
  $horario = _clean($_POST['horario'], $MYSQLI);
  $id_docente = (int) $_POST['id_docente'];

  $MYSQLI->query("INSERT INTO subject_group (id_subjects, id_modality_level, id_grupo, horario)
                  VALUES ($id_subject, $id_modality_level, '$id_grupo', '$horario')");

  if ($id_docente) {
    $MYSQLI->query("INSERT INTO teacher_subjects (id_user, id_subject, id_modality, id_level)
                    SELECT $id_docente, $id_subject, ml.id_modality, ml.id_level
                    FROM modality_level ml WHERE ml.id = $id_modality_level");
  }

  header("Location: ".$_SERVER['PHP_SELF']."?grupo_creado=1");
exit;

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_grupo'])) {
  $id = (int) $_POST['eliminar_grupo'];
  $MYSQLI->query("DELETE FROM subject_group WHERE id = $id");
  header("Location: ".$_SERVER['PHP_SELF']."?grupo_eliminado=1");
  exit;
  }

$modniv_query = $MYSQLI->query("SELECT ml.id AS id_modality_level, sm.id_subject, s.name_subject, m.name_modality, e.name_level
  FROM subject_modality_level sm
  JOIN subjects s ON sm.id_subject = s.id
  JOIN modality_level ml ON sm.id_modality = ml.id_modality AND sm.id_level = ml.id_level
  JOIN modalities m ON ml.id_modality = m.id
  JOIN education_levels e ON ml.id_level = e.id
  ORDER BY s.name_subject");

$modniv_combos = [];
while ($row = $modniv_query->fetch_assoc()) {
  $modniv_combos[] = $row;
}

$docentes_result = $MYSQLI->query("SELECT id, CONCAT(first_name, ' ', last_name) AS nombre FROM users WHERE id_perfil = 2 ORDER BY first_name");
$docentes = [];
while ($row = $docentes_result->fetch_assoc()) {
  $docentes[] = $row;
}

$grupos_result = $MYSQLI->query("SELECT sg.id, s.name_subject, m.name_modality, e.name_level, sg.id_grupo, sg.horario,
  CONCAT(u.first_name, ' ', u.last_name) AS docente
  FROM subject_group sg
  JOIN subjects s ON sg.id_subjects = s.id
  JOIN modality_level ml ON sg.id_modality_level = ml.id
  JOIN modalities m ON ml.id_modality = m.id
  JOIN education_levels e ON ml.id_level = e.id
  LEFT JOIN teacher_subjects ts ON ts.id_subject = s.id AND ts.id_modality = m.id AND ts.id_level = e.id
  LEFT JOIN users u ON u.id = ts.id_user
  ORDER BY s.name_subject");
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
      border-color: #f4a701;
    }
    .table-dark {
      --bs-table-bg: #0b0146;
    }
    .form-section {
      padding: 30px;
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
      margin-bottom: 40px;
    }
    .form-section h4 {
      font-weight: 600;
      color: #0b0146;
      margin-bottom: 25px;
    }

.container-main {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px 15px;
}

.card-hero {
  margin-bottom: 30px;
  text-align: center;
}

.hero-box {
  padding: 20px;
}

.card-body form .form-label {
  font-weight: 600;
}

.card {
  margin-bottom: 40px;
  border-radius: 15px;
  overflow: hidden;
}

.table td, .table th {
  vertical-align: middle;
  padding: 0.75rem;
}

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
<?php if (isset($_GET['grupo_eliminado'])): ?>
  <div class='alert alert-warning text-center mt-3'>Grupo eliminado correctamente.</div>
<?php endif; ?>

    <!-- Formulario para crear grupo -->
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h4 class="mb-4 text-center">Crear Nuevo Grupo</h4>
        <form method="post" class="row g-4">
          <input type="hidden" name="crear_grupo" value="1">

          <div class="col-md-6">
            <label class="form-label">Materia (con modalidad y nivel):</label>
            <select class="form-select" name="id_subject" required>
              <option value="">Seleccione una materia</option>
              <?php foreach ($modniv_combos as $combo): ?>
                <option value="<?= $combo['id_subject'] ?>" data-ml="<?= $combo['id_modality_level'] ?>">
                  <?= $combo['name_subject'] ?> (<?= $combo['name_modality'] ?> / <?= $combo['name_level'] ?>)
                </option>
              <?php endforeach; ?>
            </select>
            <input type="hidden" name="id_modality_level" id="modality_level_input">
          </div>

          <div class="col-md-3">
            <label class="form-label">Grupo (ID):</label>
            <input type="text" class="form-control" name="id_grupo" required>
          </div>

          <div class="col-md-3">
            <label class="form-label">Horario:</label>
            <input type="text" class="form-control" name="horario" placeholder="Ej: Lun y Mie 10-12" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Asignar Docente:</label>
            <select class="form-select" name="id_docente">
              <option value="">-- Opcional --</option>
              <?php foreach ($docentes as $docente): ?>
                <option value="<?= $docente['id'] ?>"><?= $docente['nombre'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-12 text-end">
            <button type="submit" class="btn btn-success px-4">Crear Grupo</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabla de grupos asignados -->
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h4 class="mb-4 text-center">Grupos Asignados</h4>
        <div class="table-responsive">
          <table class="table table-bordered text-center">
            <thead class="table-dark">
              <tr>
                <th>Materia</th>
                <th>Modalidad</th>
                <th>Nivel</th>
                <th>Grupo</th>
                <th>Horario</th>
                <th>Docente Asignado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($grupo = $grupos_result->fetch_assoc()): ?>
                <tr>
                  <td><?= $grupo['name_subject'] ?></td>
                  <td><?= $grupo['name_modality'] ?></td>
                  <td><?= $grupo['name_level'] ?></td>
                  <td><?= $grupo['id_grupo'] ?></td>
                  <td><?= $grupo['horario'] ?></td>
                  <td><?= $grupo['docente'] ?? '<span class="text-muted">No asignado</span>' ?></td>
                  <td>
                    <form method="post" onsubmit="return confirm('¿Eliminar este grupo?');">
                      <input type="hidden" name="eliminar_grupo" value="<?= $grupo['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    const subjectSelect = document.querySelector('select[name="id_subject"]');
    const mlInput = document.getElementById('modality_level_input');
    subjectSelect.addEventListener('change', function () {
      const selectedOption = subjectSelect.options[subjectSelect.selectedIndex];
      const modalityLevelId = selectedOption.getAttribute('data-ml');
      mlInput.value = modalityLevelId;
    });
  </script>
</body>

</html>
