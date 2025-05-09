<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['users']) || $_SESSION['users']['id_perfil'] != 1) {
  header("Location: ../pages/loginPersonal.php");
  exit;
}
?>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <title>STBH | Procóro García Hernández</title>
  <link rel="stylesheet" href="../assets/css/usuarios.css">
  <link rel="stylesheet" href="../styles/global.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.2.1" rel="stylesheet" />
  <link href="../assets/css/container.css" rel="stylesheet" />
</head>

<body class="bg-light">
  <!-- LOGOS -->
  <div class="logos-container">
  <div class="logos ">
    <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../assets/img/CRBH3.png" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="Marca" class="logo-img">
  </div>
</div>

  <!-- BOTONES EN TARJETA -->
  <div class="container my-5">
    <div class="menu-card mx-auto" style="max-width: 1000px;">
      <h3 class="text-center mb-4 text-dark">MENU ADMINISTRATIVO</h3>
      <div class="row g-3 text-center">
        <div class="col-6 col-md-4 col-lg-3">
          <a href="../pages/inscripciones.php" class="btn menu-btn text-white w-100"
            style="background-color: rgba(11, 1, 70, 1);">
            <i class="bi bi-journal-check me-2"></i>Inscripciones
          </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <a href="../pages/materias.php" class="btn menu-btn text-white w-100"
            style="background-color: rgba(11, 1, 70, 1);">
            <i class="bi bi-book-fill me-2"></i>Materias
          </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <a href="../pages/grupos.php" class="btn menu-btn text-white w-100"
            style="background-color: rgba(11, 1, 70, 1);">
            <i class="bi bi-diagram-3-fill me-2"></i>Grupos
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <a href="../pages/horarios.php" class="btn menu-btn text-white w-100"
            style="background-color: rgba(11, 1, 70, 1);">
            <i class="bi bi bi-clock me-2"></i>Horarios
          </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <a href="../pages/usuarios.php" class="btn menu-btn text-white w-100"
            style="background-color: rgba(11, 1, 70, 1);">
            <i class="bi bi-people-fill me-2"></i>Usuarios
          </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <a href="../pages/docente.php" class="btn menu-btn text-white w-100"
            style="background-color: rgba(11, 1, 70, 1);">
            <i class="bi bi-person-workspace me-2"></i>Docentes
          </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <a href="../pages/alumno.php" class="btn menu-btn text-white w-100"
            style="background-color: rgba(11, 1, 70, 1);">
            <i class="bi bi-person-lines-fill me-2"></i>Alumnos
          </a>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <a href="../logout.php" class="btn menu-btn text-white w-100"
            style="background-color: #8b0000;">
            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="footer py-4">
    <div class="container">
      <div class="row">
        <div class="col-10 mx-auto text-center">
          <p class="mb-0 text-secondary">
            STBH © <script>document.write(new Date().getFullYear())</script> | Todos los derechos Reservados
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- JS -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), { damping: '0.5' });
    }
  </script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
</body>

</html>
