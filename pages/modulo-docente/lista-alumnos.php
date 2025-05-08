<?php
session_start(); // Iniciar sesión antes de cualquier salida
require_once 'conexion.php';

// Obtener el grupo desde la URL
$grupo = $_GET['grupo'] ?? null;
$alumnos = [];
$infoGrupo = [];

if ($grupo) {
    // Verificar que el grupo exista
    $checkGrupo = $pdo->prepare("SELECT COUNT(*) FROM group_subject_assignment WHERE id_group = ?");
    $checkGrupo->execute([$grupo]);
    $existeGrupo = $checkGrupo->fetchColumn();

    if ($existeGrupo) {
        // Obtener nombre de la materia (una de ellas) y grupo
        $stmtGrupo = $pdo->prepare("
            SELECT gsa.id_group, g.name AS group_name, s.name_subject
            FROM group_subject_assignment gsa
            JOIN subjects s ON gsa.id_subject = s.id
            JOIN grupos g ON g.id = gsa.id_group
            WHERE gsa.id_group = ?

        ");
        $stmtGrupo->execute([$grupo]);
        $infoGrupo = $stmtGrupo->fetch(PDO::FETCH_ASSOC);

        // Obtener alumnos del grupo (inscritos en alguna materia del grupo)
        $stmt = $pdo->prepare("
            SELECT DISTINCT stu.control_number, u.first_name, u.last_name
            FROM student_subject_enrollment e
            JOIN students stu ON e.id_user = stu.id_user
            JOIN users u ON u.id = stu.id_user
            JOIN group_subject_assignment gsa ON gsa.id_group = ?
            WHERE e.id_subject = gsa.id_subject
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
  <link id="pagestyle" href="../../assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
  <style>
    .logos-container {
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #fff;
      padding: 12px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      gap: 30px;
    }
    .logo-img {
      height: 90px;
      width: auto;
    }
    .card-hero {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 30px 15px 0;
    }
    .hero-box {
      background: #ffffff;
      padding: 25px 35px;
      border-radius: 15px;
      border: 1px solid #eee;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
      text-align: center;
      max-width: 500px;
      width: 100%;
    }
    .hero-box h2 {
      font-size: 1.6rem;
      font-weight: 700;
      color: #0b0146;
      margin-bottom: 10px;
    }
    .btn-stbh {
      background-color: #0b0146;
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 5px;
      transition: background-color 0.3s, transform 0.2s;
      font-weight: 500;
      text-decoration: none;
      display: inline-block;
    }
    .btn-stbh:hover {
      background-color: #12025a;
      transform: translateY(-2px);
    }
    .card h4 {
      color: #0b0146;
    }
    /* .text-primary {
      color: #0b0146 !important;
    } */

  </style>
</head>

<body>
  <div class="logos-container">
    <img src="../../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../../assets/img/CRBH3.png" alt="CRBH" class="logo-img">
    <img src="../../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../../assets/img/logo2.png" alt="Marca" class="logo-img">
  </div>

  <main>
    <!-- CARD DE REGRESO AL MÓDULO DE INICIO -->
    <section class="card-hero d-flex justify-content-center mt-4">
    <div class="d-flex flex-column align-items-center align-content-center">
          <p><a href="../M_docente.php" class="btn btn-outline-[#0b0146]">
            <i class="bi bi-arrow-left-circle"></i> Volver al módulo de inicio
          </a></p>
        </div>
    </section>
    
    <?php if (!empty($infoGrupo)): ?>
    <section class="container my-4">
      <div class="text-center">
        <h2 class="text-primary"><?= htmlspecialchars($infoGrupo['name_subject']) ?></h2>
        <p class="text-muted">Grupo: <?= htmlspecialchars($infoGrupo['group_name']) ?></p>

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
            $grupoId = $_GET['grupo'];
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
