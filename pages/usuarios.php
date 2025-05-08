<?php
session_start();
if (!isset($_SESSION['users']) || $_SESSION['users']['id_perfil'] != 1) {
  header("Location: ../pages/loginPersonal.php");
  exit;
}
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();
// Insertar nuevo usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['pass'], $_POST['perfil'], $_POST['first_name'], $_POST['last_name'])) {
  $email       = _clean($_POST['email'], $MYSQLI);
  $pass        = _clean($_POST['pass'], $MYSQLI);
  $perfil      = _clean($_POST['perfil'], $MYSQLI);
  $first_name  = _clean($_POST['first_name'], $MYSQLI);
  $last_name   = _clean($_POST['last_name'], $MYSQLI);

  if (!empty($email) && !empty($pass) && !empty($perfil) && !empty($first_name) && !empty($last_name)) {
      $SQLInsert = "INSERT INTO users (email, pass, first_name, last_name, id_perfil) 
                    VALUES ('$email', '$pass', '$first_name', '$last_name', '$perfil')";
      _Q($SQLInsert, $MYSQLI, 1);
      header("Location: usuarios.php?success=1");
      exit;
  }
}

// Actualizar contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_pass'], $_POST['id_usuario'])) {
    $newPass = _clean($_POST['nueva_pass'], $MYSQLI);
    $userId = (int) $_POST['id_usuario'];
    $SQLUpdatePass = "UPDATE users SET pass = '$newPass' WHERE id = $userId";
    _Q($SQLUpdatePass, $MYSQLI, 1);
    header("Location: usuarios.php?pass_updated=1");
    exit;
}

// Consultas
$SQL = "SELECT u.*,p.name_perfil as perfil FROM users U INNER JOIN perfil p ON u.id_perfil = p.id;";
$RESULT = _Q($SQL, $MYSQLI, 2);
$SQLPerfiles = "SELECT * FROM perfil;";
$PerfilesData = _Q($SQLPerfiles, $MYSQLI, 2);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Usuarios</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <link href="../assets/css/usuarios.css" rel="stylesheet" />
</head>

<body>

<!-- LOGOS -->
<div class="logos-container">
  <div class="logos">
    <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../assets/img/CRBH3.png" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="Marca" class="logo-img">
  </div>
</div>

<!-- CARD DE MENÚ -->
<section class="card-hero">
  <div class="hero-box">
    <h2>Sección Usuarios</h2>
    <div class="btn-group">
      <a href="admin.php" class="btn-stbh btn-lg bi bi-arrow-left-circle">Regresar al Menú Principal</a>
      <button class="btn-stbh btn-lg btn_Alta ">Nuevo Usuario</button>
    </div>
  </div>
</section>

<!-- ALERTAS -->
<div class="container mt-4">
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-auto-close text-center">Usuario agregado correctamente.</div>
  <?php endif; ?>
  <?php if (isset($_GET['pass_updated'])): ?>
    <div class="alert alert-info alert-auto-close text-center">Contraseña actualizada correctamente.</div>
  <?php endif; ?>
</div>
<!-- FILTROS -->
<div class="container mb-3 text-center">
  <div class="row justify-content-center g-3 align-items-center">
    <div class="col-md-4">
      <label>Filtrar por correo:</label>
      <input type="text" id="filtroCorreo" class="form-control" placeholder="Correo">
    </div>
    <div class="col-md-4">
      <label>Filtrar por perfil:</label>
      <select id="filtroPerfil" class="form-select">
        <option value="">Todos</option>
        <?php foreach ($PerfilesData as $value) echo "<option value='{$value['name_perfil']}'>{$value['name_perfil']}</option>"; ?>
      </select>
    </div>
  </div>
</div>
<!-- TABLA DE USUARIOS -->
<section class="users p-4">
  <div class="container">
    <div class="table-responsive">
      <table class="table table-bordered text-center table-stbh">
        <thead>
          <tr>
            <th>Email</th>
            <th>Nombre completo</th>
            <th>Perfil</th>
            <th>Contraseña</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($RESULT as $value): ?>
          <tr>
            <td><?= $value['email'] ?></td>
            <td><?= $value['first_name'] . ' ' . $value['last_name'] ?></td>
            <td><?= $value['perfil'] ?></td>
            <td>
              <button class="btn-stbh btn-sm btn_CambiarPass" data-id="<?= $value['id'] ?>">
                Cambiar contraseña
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- FORM NUEVO USUARIO -->
<section id="fondo">
  <div id="form_alta">
    <span class="cerrar">&times;</span>
    <form class="usuarioAlta" method="POST" action="usuarios.php">
      <h2>USUARIO NUEVO</h2>
      <div class="mb-3">
        <label>Nombre(s)</label>
        <input class="form-control" type="text" name="first_name" required>
      </div>
      <div class="mb-3">
        <label>Apellidos</label>
        <input class="form-control" type="text" name="last_name" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input class="form-control" type="text" name="email" required>
      </div>
      <div class="mb-3">
        <label>Contraseña</label>
        <input class="form-control" type="text" name="pass" required>
      </div>
      <div class="mb-3">
        <label>Perfil</label>
        <select class="form-control" name="perfil" required>
          <option value="" disabled selected>Selecciona un perfil</option>
          <?php foreach ($PerfilesData as $value): ?>
            <option value="<?= $value['id'] ?>"><?= $value['name_perfil'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn-stbh">Guardar</button>
    </form>
  </div>
</section>

<!-- FORM CAMBIAR CONTRASEÑA -->
<section id="fondo_cambiar">
  <div id="form_cambiar_pass">
    <span class="cerrar_cambiar">&times;</span>
    <form method="POST" action="usuarios.php">
      <h2>CAMBIAR CONTRASEÑA</h2>
      <input type="hidden" name="id_usuario" id="id_usuario_cambiar">
      <div class="mb-3">
        <label>Nueva contraseña</label>
        <input class="form-control" type="text" name="nueva_pass" id="nueva_pass" required>
      </div>
      <button type="submit" class="btn-stbh">Actualizar</button>
    </form>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer py-4 text-center text-secondary">
  STBH © <script>document.write(new Date().getFullYear())</script> | Todos los derechos reservados
</footer>

<!-- JS -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const fondo = document.getElementById('fondo');
  const fondo_cambiar = document.getElementById('fondo_cambiar');
  const cerrar = document.querySelector('.cerrar');
  const cerrar_cambiar = document.querySelector('.cerrar_cambiar');
  const btnAlta = document.querySelector('.btn_Alta');
  const idInput = document.getElementById('id_usuario_cambiar');

  if (btnAlta) {
    btnAlta.addEventListener('click', () => fondo.style.display = 'block');
  }
  if (cerrar) {
    cerrar.addEventListener('click', () => fondo.style.display = 'none');
  }
  if (cerrar_cambiar) {
    cerrar_cambiar.addEventListener('click', () => fondo_cambiar.style.display = 'none');
  }

  document.querySelectorAll('.btn_CambiarPass').forEach(btn => {
    btn.addEventListener('click', () => {
      idInput.value = btn.getAttribute('data-id');
      fondo_cambiar.style.display = 'block';
    });
  });

  const alerta = document.querySelector('.alert-auto-close');
  if (alerta) {
    setTimeout(() => {
      alerta.style.display = 'none';
    }, 5000);
  }
});
</script>
<script>
// Filtro de correo y perfil en la tabla
document.addEventListener('DOMContentLoaded', () => {
  const inputCorreo = document.getElementById('filtroCorreo');
  const selectPerfil = document.getElementById('filtroPerfil');
  const filas = document.querySelectorAll('table tbody tr');

  function aplicarFiltros() {
    const filtroCorreo = inputCorreo.value.toLowerCase();
    const filtroPerfil = selectPerfil.value;

    filas.forEach(fila => {
      const correo = fila.children[0].textContent.toLowerCase();
      const perfil = fila.children[2].textContent;

      const coincideCorreo = correo.includes(filtroCorreo);
      const coincidePerfil = !filtroPerfil || perfil === filtroPerfil;

      if (coincideCorreo && coincidePerfil) {
        fila.style.display = '';
      } else {
        fila.style.display = 'none';
      }
    });
  }

  inputCorreo.addEventListener('input', aplicarFiltros);
  selectPerfil.addEventListener('change', aplicarFiltros);
});
</script>

</body>
</html>
