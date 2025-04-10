<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <title>STBH | Procóro García Hernández</title>

  <link rel="stylesheet" href="../styles/global.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />

  <style>
    body {
      background-color: #f8f9fa;
    }

    .logos-container {
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #fff;
      padding: 12px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .logos img.rounded {
      margin-right: 20px;
    }

    .menu-card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 30px;
    }

    .menu-btn {
      font-size: 1rem;
      font-weight: 600;
      padding: 15px 10px;
      transition: all 0.2s ease-in-out;
    }

    .menu-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    footer p {
      font-size: 0.9rem;
    }
  </style>
</head>

<body>
  <!-- LOGOS -->
  <div class="logos-container">
    <img class="rounded" src="../assets/img/cnbm.png" alt="CNBM Logo" style="width: 300px;">
    <img class="rounded" src="../assets/img/CRBH2.png" alt="CRBH Logo" style="width: 100px;">
    <img class="rounded" src="../assets/img/stbm.png" alt="STBM Logo" style="width: 300px;">
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
          <a href="../pages/asignar_materias.php" class="btn menu-btn text-white w-100"
            style="background-color: rgba(11, 1, 70, 1);">
          <i class="bi bi-link-45deg me-2"></i>Asignar Materias
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
