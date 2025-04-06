<?php
session_start(); // Iniciar sesión antes de cualquier salida

// Obtener la pestaña activa desde la URL
$tab_activa = isset($_GET['tab']) ? $_GET['tab'] : 'calificaciones';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../assets/img/icon_stbh.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="./styles/global.css">
  <title>
    STBH | Procóro García Hernández
  </title>
  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <!-- Nepcha Analytics -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body>
    <?php include("./components/header.php"); ?> 

    <main>
        <section class="my-4 mx-2">
            <!-- Pestañas como enlaces -->
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link <?= $tab_activa === 'calificaciones' ? 'active' : '' ?>" 
                       href="?tab=calificaciones"
                       role="tab">
                        <span class="bi bi-journal"></span> Calificaciones
                    </a>
                    <a class="nav-link <?= $tab_activa === 'asistencia' ? 'active' : '' ?>" 
                       href="?tab=asistencia"
                       role="tab">
                        <span class="bi bi-journal-check"></span> Asistencia
                    </a>
                </div>
            </nav>

            <!-- Contenido de las pestañas -->
            <div class="tab-content">
                <!-- Pestaña de calificaciones -->
                <div class="tab-pane fade <?= $tab_activa === 'calificaciones' ? 'show active' : '' ?>" 
                     role="tabpanel">
                    <?php 
                    if ($tab_activa === 'calificaciones') {
                        include("./components/formulario-calificaciones.php"); 
                    }
                    ?>
                </div>

                <!-- Pestaña de asistencia -->
                <div class="tab-pane fade <?= $tab_activa === 'asistencia' ? 'show active' : '' ?>" 
                     role="tabpanel">
                    <?php 
                    if ($tab_activa === 'asistencia') {
                        include("./components/formulario-asistencia.php"); 
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

  <script src="../../assets/js/core/popper.min.js"></script>
  <script src="../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
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
  <!-- Control Center for Soft Dashboard -->
  <script src="../../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
</body>

</html>
