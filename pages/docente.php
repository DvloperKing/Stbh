<?php 
$mainblue = 0;
$data = json_decode(file_get_contents('./modulo-docente/data.json'), true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <title>
    STBH | Procóro García Hernández
  </title>
  <link rel="stylesheet" href="../styles/global.css">
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>

  <style>
    .move-up {
      margin-top: -50px; /* Ajusta este valor según sea necesario */
    }

    .image-container {
      position: absolute;
      top: 125px;
      right: 0;
      bottom: 0;
      display: flex;
      align-items: center;
    }

    .oblique-image {
    width: 100%;
    height: 370px;
    }
    .logos-container {
      display: flex;
      justify-content: center; /* Centra horizontalmente */
      align-items: center; /* Centra verticalmente */
      background-color: #fff; /* Color de fondo */
      padding: 12px; /* Espacio interno */
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra */
    }
    .logos {
      display: flex;
      justify-content: center; /* Centra horizontalmente */
      align-items: center; /* Centra verticalmente */
    }

    .logos img.rounded {
      width: 100px; /* Tamaño de las imágenes */
      margin-right: 20px; /* Espacio entre las imágenes */
    }
    .centered-image {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 60vh;
    }

    .centered-image img {
      width: 50%; /* Ajusta este valor según sea necesario */
      opacity: 0.3; /* 70% de transparencia */
    }
  </style>
</head>
    
<body class="">
<div class="logos-container">
    <!-- Imágenes de la clase .logos -->
    <div class="logos">
      <img class="rounded" src="../assets/img/cnbm.png"  alt="CNBM Logo"  style="width: 300px;">
      <img class="rounded" src="../assets/img/CRBH2.png"  alt="CRBH Logo"  style="width: 100px;">
      <img class="rounded" src="../assets/img/stbm.png"  alt="STBM Logo"  style="width: 300px;">
    </div>
  </div>

  <nav class="navbar navbar-expand-lg bg-body-tertiary" style="background-color: rgba(11, 1, 70, 1);">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarScroll">
  <!--Aqui iran los botones-->
           <!-- <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                <li class="nav-item dropdown">
                    <!-- <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Calificaciones
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Subir Calificaciones</a></li>
                        <li><a class="dropdown-item" href="#">Consultar Calificaciones</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Manuales
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Subir Manual</a></li>
                        <li><a class="dropdown-item" href="#">Gestionar Manuales</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Tareas
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Asignar Tarea</a></li>
                        <li><a class="dropdown-item" href="#">Revisar Tarea</a></li>
                    </ul>
                </li>
            </ul> -->
        </div>
    </div>
</nav>

    <section class="container-fluid pt-lg mt-md-2  flex-col px-0">
    <?php foreach ($data['materias'] as $materia): ?>
    <div class="d-flex flex-column container-fluid my-4">
        <h2><?= $materia['nombre'] ?></h2>
        <h3><?= $materia['nombre'] ?></h3>
        <div class="row">
            <?php foreach ($materia['grupos'] as $grupo): ?>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card h-100 d-flex flex-column justify-content-between border-2" style="min-height: 250px;">
                    <div>
                        <h4 class="text-center mt-3"><?= $grupo['nombre'] ?></h4>
                    </div>
                    <a href="./modulo-docente/lista-alumnos.php" 
                       class="btn-link text-white rounded-1 border-1 py-2 text-decoration-none justify-content-center" 
                       style="background-color: rgba(11, 1, 70, 1);">
                        Ir <span class="bi bi-arrow-right-circle"></span>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
    </section>
</main>

<style>
  .dropdown-menu {
    background-color: #ffffff; /* Fondo blanco para mejor contraste */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra sutil */
  }

  .dropdown-item {
    color: #333333; /* Texto oscuro para mejor legibilidad */
    font-size: 16px; /* Aumentar tamaño de la fuente */
    font-weight: 500; /* Aumentar el peso de la fuente */
  }

  .dropdown-item:hover {
    background-color: rgba(11, 1, 70, 0.1); /* Fondo ligeramente oscuro al pasar el ratón */
  }

  .main{
    display: flex;
    flex-direction: row;
  }

  .card{
    flex-wrap: 1;
  }
</style>

  <!--
<div class="centered-image">
    <img class="oblique-image" src="../assets/img/curved-images/logo2.png" alt="Logo">
  </div>
          </div>
        </div>
      </div>
    </section>
  </main> -->
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">        
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
          STBH © <script>
              document.write(new Date().getFullYear())
            </script>  | Todos los derechos Reservados
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
</body>

</html>
