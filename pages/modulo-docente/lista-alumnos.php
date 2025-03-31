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
            <!-- Pestañas para alternar entre calificaciones y asistencia -->
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-calificaciones-tab" data-bs-toggle="tab" data-bs-target="#nav-calificaciones" type="button" role="tab"><span class="bi bi-journal"></span> Calificaciones</button>
                    <button class="nav-link" id="nav-asistencia-tab" data-bs-toggle="tab" data-bs-target="#nav-asistencia" type="button" role="tab"><span class="bi bi-journal-check"></span> Asistencia</button>
                </div>
            </nav>

            <!-- Contenido de las pestañas -->
            <div class="tab-content" id="nav-tabContent">
                <!-- Pestaña de calificaciones -->
                <div class="tab-pane fade show active" id="nav-calificaciones" role="tabpanel">
                    <?php include("./components/formulario-calificaciones.php"); ?>
                </div>

                <!-- Pestaña de asistencia -->
                <div class="tab-pane fade" id="nav-asistencia" role="tabpanel">
                    <?php include("./components/formulario-asistencia.php"); ?>
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
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
</body>

</html>
