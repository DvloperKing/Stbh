<?php
session_start();
if (!isset($_SESSION['users']) || $_SESSION['users']['id_perfil'] != 1) {
  header("Location: ../pages/loginPersonal.php");
  exit;
}
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();

// insertar nueva materia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name_subject'], $_POST['code'], $_POST['semester'], $_POST['modality_level'])) {
  $name = _clean($_POST['name_subject'], $MYSQLI);
  $code = _clean($_POST['code'], $MYSQLI);
  $semester = (int)$_POST['semester'];
  $pairs = $_POST['modality_level'];

  if (!empty($name) && !empty($code) && $semester > 0 && is_array($pairs)) {
    $insert = "insert into subjects (name_subject, code, semester) values ('$name', '$code', $semester)";
    if (_Q($insert, $MYSQLI, 1)) {
      $subject_id = $MYSQLI->insert_id;
      foreach ($pairs as $combo) {
        list($id_modality, $id_level) = explode('-', $combo);
        $id_modality = (int)$id_modality;
        $id_level = (int)$id_level;
        _Q("insert into subject_modality_level (id_subject, id_modality, id_level) values ($subject_id, $id_modality, $id_level)", $MYSQLI, 1);
      }
      header("Location: materias.php?success=1");
      exit;
    }
  }
}

// consulta materias con modalidad y nivel exacto
$SQL = "
  select 
    s.name_subject, 
    s.code, 
    s.semester, 
    m.name_modality, 
    el.name_level
  from subject_modality_level sml
  join subjects s on s.id = sml.id_subject
  join modalities m on m.id = sml.id_modality
  join education_levels el on el.id = sml.id_level
  order by el.name_level, m.name_modality, s.semester, s.name_subject
";
$RESULT = _Q($SQL, $MYSQLI, 2);

// para el formulario
$MOD_LEVEL = _Q("select ml.id_modality, ml.id_level, m.name_modality, el.name_level from modality_level ml join modalities m on m.id = ml.id_modality join education_levels el on el.id = ml.id_level order by m.name_modality, el.name_level", $MYSQLI, 2);
$MOD_NAMES = _Q("select distinct name_modality from modalities order by name_modality", $MYSQLI, 2);
$LEVELS = _Q("select distinct name_level from education_levels order by name_level", $MYSQLI, 2);
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
    <h2>Sección Materias</h2>
    <div class="btn-group">
      <a href="admin.php" class="btn-stbh btn-lg">Regresar al Menú Principal</a>
      <button class="btn-stbh btn-lg btn_Alta">Nueva Materia</button>
    </div>
  </div>
</section>
<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success text-center">Materia agregada correctamente.</div>
<?php endif; ?>
<div class="container mb-3 text-center">
  <div class="row justify-content-center g-3 align-items-center">
    <div class="col-md-4">
      <label>Filtrar por modalidad:</label>
      <select id="filtroModalidad" class="form-select">
        <option value="">Todas</option>
        <?php foreach ($MOD_NAMES as $mod) echo "<option value=\"{$mod['name_modality']}\">{$mod['name_modality']}</option>"; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label>Filtrar por nivel:</label>
      <select id="filtroNivel" class="form-select">
        <option value="">Todos</option>
        <?php foreach ($LEVELS as $niv) echo "<option value=\"{$niv['name_level']}\">{$niv['name_level']}</option>"; ?>
      </select>
    </div>
  </div>
</div>
<section class="users p-4">
  <div class="container">
    <div class="table-responsive">
      <table class="table table-bordered text-center table-stbh" id="tablaMaterias">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Código</th>
            <th>Semestre</th>
            <th>Modalidad</th>
            <th>Nivel</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($RESULT as $row): ?>
          <tr>
            <td><?= $row['name_subject'] ?></td>
            <td><?= $row['code'] ?></td>
            <td><?= $row['semester'] ?></td>
            <td><?= $row['name_modality'] ?></td>
            <td><?= $row['name_level'] ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<section id="fondo">
  <div id="form_alta">
    <span class="cerrar">&times;</span>
    <form method="POST" action="materias.php">
      <h2>NUEVA MATERIA</h2>
      <div class="mb-3">
        <label>Nombre de la materia</label>
        <input class="form-control" type="text" name="name_subject" required>
      </div>
      <div class="mb-3">
        <label>Código</label>
        <input class="form-control" type="text" name="code" required>
      </div>
      <div class="mb-3">
        <label>Semestre</label>
        <input class="form-control" type="number" name="semester" min="1" max="12" required>
      </div>
      <div class="mb-3">
        <label>Modalidad + Nivel</label>
        <select class="form-control" name="modality_level[]" multiple required>
          <?php foreach ($MOD_LEVEL as $item): ?>
            <option value="<?= $item['id_modality'] ?>-<?= $item['id_level'] ?>">
              <?= $item['name_modality'] ?> - <?= $item['name_level'] ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small class="text-muted">Usa Ctrl (Windows) o Cmd (Mac) para seleccionar varias</small>
      </div>
      <button type="submit" class="btn-stbh">Guardar</button>
    </form>
  </div>
</section>
<footer class="footer py-4 text-center text-secondary">
  STBH © <script>document.write(new Date().getFullYear())</script> | Todos los derechos reservados
</footer>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const fondo = document.getElementById('fondo');
  const cerrar = document.querySelector('.cerrar');
  const btnAlta = document.querySelector('.btn_Alta');
  btnAlta.addEventListener('click', () => fondo.style.display = 'block');
  cerrar.addEventListener('click', () => fondo.style.display = 'none');

  const filtroModalidad = document.getElementById('filtroModalidad');
  const filtroNivel = document.getElementById('filtroNivel');
  const tabla = document.querySelector('#tablaMaterias tbody');

  function aplicarFiltros() {
    const modalidad = filtroModalidad.value.toLowerCase();
    const nivel = filtroNivel.value.toLowerCase();
    [...tabla.rows].forEach(row => {
      const textoModalidad = row.cells[3].textContent.toLowerCase();
      const textoNivel = row.cells[4].textContent.toLowerCase();
      const coincideModalidad = !modalidad || textoModalidad.includes(modalidad);
      const coincideNivel = !nivel || textoNivel.includes(nivel);
      row.style.display = (coincideModalidad && coincideNivel) ? '' : 'none';
    });
  }
  filtroModalidad.addEventListener('change', aplicarFiltros);
  filtroNivel.addEventListener('change', aplicarFiltros);
});
</script>
<script>
  // Ocultar automáticamente la alerta de éxito
  document.addEventListener('DOMContentLoaded', () => {
    const alerta = document.querySelector('.alert-success');
    if (alerta) {
      setTimeout(() => {
        alerta.style.display = 'none';
      }, 4000); // 4 segundos
    }
  });
</script>

</body>
</html>