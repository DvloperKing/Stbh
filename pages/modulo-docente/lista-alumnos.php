<?php
session_start(); // Iniciar sesión antes de cualquier salida
require_once 'conexion.php';

// Obtener el grupo desde la URL
$grupo = $_GET['grupo'] ?? null;
$alumnos = [];



if ($grupo) {
    // Verificar que el grupo exista
    $checkGrupo = $pdo->prepare("SELECT COUNT(*) FROM subject_group WHERE id_grupo = ?");
    $checkGrupo->execute([$grupo]);
    $existeGrupo = $checkGrupo->fetchColumn();

if ($existeGrupo) {
    // Obtener nombre de la materia y grupo
    $stmtGrupo = $pdo->prepare("
        SELECT g.id_grupo, m.name_subject
        FROM subject_group g
        INNER JOIN subjects m ON g.id_subjects = m.id
        WHERE g.id_grupo = ?
    ");
    $stmtGrupo->execute([$grupo]);
    $infoGrupo = $stmtGrupo->fetch(PDO::FETCH_ASSOC);

    // Obtener alumnos del grupo
    $stmt = $pdo->prepare("
        SELECT s.control_number, u.first_name, u.last_name
        FROM students s
        JOIN users u ON s.id_user = u.id
        JOIN student_subjects ss ON ss.id_user = u.id
        JOIN subject_group sg ON sg.id = ?
        WHERE ss.id_subject = sg.id_subjects
    ");
    $stmt->execute([$grupo]);
    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $mensajeError = "El grupo especificado no existe.";
}
}

// Obtener la pestaña activa desde la URL
$tab_activa = $_GET['tab'] ?? 'calificaciones';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../assets/img/icon_stbh.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="./styles/global.css">
  <title>STBH | Procóro García Hernández</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
</head>

<body>
    <div class="logos-container">
    <div class="logos">
      <img class="rounded" src="../../assets/img/cnbm.png" alt="CNBM Logo">
      <img class="rounded" src="../../assets/img/CRBH3.png" alt="CRBH Logo">
      <img class="rounded" src="../../assets/img/stbm.png" alt="STBM Logo">
    </div>
  </div>

  <main>
<!-- CARD DE REGRESO AL MÓDULO DE INICIO -->
<section class="card-hero d-flex justify-content-center mt-4">
  <div class=" border border-[#0b0146]-8 p-4 rounded-2 d-flex flex-column justify-conent-center">
    <h2>Regresar a la página principal.</h2>
    <div class="d-flex flex-column align-items-center align-content-center">
      <p><a href="../M_docente.php" class="btn btn-outline-[#0b0146]">
      <i class="bi bi-arrow-left-circle"></i> Volver al módulo de inicio
    </a></p>
    </div>
    
  </div>
</section>
<?php if (!empty($infoGrupo)): ?>
<section class="container my-4">
  <div class="text-center">
    <h2 class="text-primary"><?= htmlspecialchars($infoGrupo['name_subject']) ?></h2>
    <p class="text-muted">Grupo: <?= htmlspecialchars($infoGrupo['id_grupo']) ?></p>
  </div>
</section>
<?php endif; ?>



    <section class="my-4 mx-2">
      <!-- Pestañas como enlaces -->
      <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
          <a class="nav-link <?= $tab_activa === 'calificaciones' ? 'active' : '' ?>" 
             href="?grupo=<?= urlencode($grupo) ?>&tab=calificaciones"
             role="tab">
            <span class="bi bi-journal"></span> Calificaciones
          </a>
          <a class="nav-link <?= $tab_activa === 'asistencia' ? 'active' : '' ?>" 
             href="?grupo=<?= urlencode($grupo) ?>&tab=asistencia"
             role="tab">
            <span class="bi bi-journal-check"></span> Asistencia
          </a>
        </div>
      </nav>

      <!-- Validación y mensajes -->
      <div class="mt-3">
        <?php if (isset($mensajeError)): ?>
          <div class="alert alert-warning"><?= $mensajeError ?></div>
        <?php endif; ?>
      </div>



      <!-- Contenido de las pestañas -->
      <div class="tab-content mt-4">
        <!-- Pestaña de calificaciones -->
        <div class="tab-pane fade <?= $tab_activa === 'calificaciones' ? 'show active' : '' ?>" role="tabpanel">
          <?php 
          if ($tab_activa === 'calificaciones') {
            include("./components/formulario-calificaciones.php"); 
          }
          ?>
        </div>

        <!-- Pestaña de asistencia -->
        <div class="tab-pane fade <?= $tab_activa === 'asistencia' ? 'show active' : '' ?>" role="tabpanel">
          <?php 
         if ($tab_activa === 'asistencia' && isset($_GET['grupo'])) {
          $grupoId = $_GET['grupo']; // Esto es CLAVE
          include 'components/formulario-asistencia.php';
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
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), { damping: '0.5' });
    }
  </script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="../../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
</body>

</html>
