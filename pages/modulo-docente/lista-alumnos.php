<?php
session_start(); // Iniciar sesión antes de cualquier salida
require_once 'conexion.php';

$grupo = $_GET['grupo'] ?? null;
$alumnos = [];
$infoGrupo = [];
$mensajeError = null;

// 1. Validar que se haya recibido un grupo
if ($grupo) {
    // 2. Verificar que el grupo exista en la tabla `grupos`
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM grupos WHERE id = ?");
    $stmt->execute([$grupo]);
    if ($stmt->fetchColumn()) {
        // 3. Obtener el nombre del grupo y la materia (tomando la primera asignación)
        $stmtInfo = $pdo->prepare("
            SELECT 
                grp.name       AS group_name, 
                sub.name_subject
            FROM group_subject_assignment gsa
            JOIN grupos grp     ON gsa.id_group   = grp.id
            JOIN subjects sub   ON gsa.id_subject = sub.id
            WHERE gsa.id_group = ?
            LIMIT 1
        ");
        $stmtInfo->execute([$grupo]);
        $infoGrupo = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        // 4. Obtener los alumnos inscritos en ese grupo
        $stmtAl = $pdo->prepare("
            SELECT 
                u.first_name, 
                u.last_name, 
                s.control_number
            FROM group_subject_assignment gsa
            JOIN student_subjects       ss ON ss.id_subject = gsa.id_subject
            JOIN users                  u  ON ss.id_user    = u.id
            JOIN students               s  ON s.id_user     = u.id
            WHERE gsa.id_group = ?
        ");
        $stmtAl->execute([$grupo]);
        $alumnos = $stmtAl->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $mensajeError = "El grupo especificado no existe.";
    }
} else {
    $mensajeError = "No se especificó ningún grupo.";
}

$tab_activa = $_GET['tab'] ?? 'calificaciones';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>STBH | Lista de Alumnos</title>
  <link rel="icon" type="image/png" href="../../assets/img/icon_stbh.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <link href="./styles/global.css" rel="stylesheet" />
</head>
<body>
  <?php include('components/header.php'); ?>

  <!-- CARD DE REGRESO AL MÓDULO DE INICIO -->
  <section class="card-hero d-flex justify-content-center mt-4">
    <div class="hero-box border border-3 border-primary p-4 rounded text-center">
      <h2>Volver al Panel de Grupos</h2>
      <a href="../M_docente.php" class="btn btn-outline-primary mt-2">
        <i class="bi bi-arrow-left-circle"></i> Inicio
      </a>
    </div>
  </section>

  <!-- Encabezado de materia y grupo -->
  <?php if (!empty($infoGrupo)): ?>
  <section class="container my-4">
    <div class="text-center">
      <h2 class="text-primary"><?= htmlspecialchars($infoGrupo['name_subject']) ?></h2>
      <p class="text-muted">Grupo: <?= htmlspecialchars($infoGrupo['group_name']) ?></p>
    </div>
  </section>
  <?php endif; ?>

  <main>
    <section class="my-4 mx-2">
      <!-- Pestañas -->
      <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
          <a class="nav-link <?= $tab_activa==='calificaciones'?'active':'' ?>"
             href="?grupo=<?=urlencode($grupo)?>&tab=calificaciones">
            <i class="bi bi-journal"></i> Calificaciones
          </a>
          <a class="nav-link <?= $tab_activa==='asistencia'?'active':'' ?>"
             href="?grupo=<?=urlencode($grupo)?>&tab=asistencia">
            <i class="bi bi-journal-check"></i> Asistencia
          </a>
        </div>
      </nav>

      <!-- Mensaje de error -->
      <?php if ($mensajeError): ?>
      <div class="alert alert-warning mt-3"><?= htmlspecialchars($mensajeError) ?></div>
      <?php endif; ?>

      <!-- Contenido de pestañas -->
      <div class="tab-content mt-4">
        <!-- CALIFICACIONES -->
        <div class="tab-pane fade <?= $tab_activa==='calificaciones'?'show active':'' ?>">
          <?php include "./components/formulario-calificaciones.php"; ?>
        </div>

        <!-- ASISTENCIA -->
        <div class="tab-pane fade <?= $tab_activa==='asistencia'?'show active':'' ?>">
          <?php include "./components/formulario-asistencia.php"; ?>
        </div>
      </div>
    </section>
  </main>

  <!-- JS de Bootstrap y Dashboard -->
  <script src="../../assets/js/core/popper.min.js"></script>
  <script src="../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../assets/js/soft-ui-dashboard.min.js?v=1.0.8"></script>
</body>
</html>
