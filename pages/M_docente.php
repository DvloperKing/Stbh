<?php
session_start();
require './modulo-docente/conexion.php';

try {
    $sql = "
        SELECT 
            s.id AS subject_id,
            s.name_subject,
            g.name AS id_grupo,
            m.name_modality,
            e.name_level,
            '' AS horario
        FROM group_subject_assignment ga
        JOIN grupos g ON ga.id_group = g.id
        JOIN modality_level ml ON g.id_modality_level = ml.id
        JOIN modalities m ON ml.id_modality = m.id
        JOIN education_levels e ON ml.id_level = e.id
        JOIN subjects s ON ga.id_subject = s.id
        ORDER BY s.id, g.name
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];

    foreach ($resultados as $fila) {
        $idMateria = $fila['subject_id'];
        $nombreMateria = $fila['name_subject'];

        if (!isset($data[$idMateria])) {
            $data[$idMateria] = [
                'nombre' => $nombreMateria,
                'grupos' => []
            ];
        }

        $data[$idMateria]['grupos'][] = [
            'id_grupo' => $fila['id_grupo'],
            'modalidad' => $fila['name_modality'],
            'nivel' => $fila['name_level'],
            'horario' => $fila['horario'] ?? 'No asignado'
        ];
    }
} catch (PDOException $e) {
    echo "Error al obtener grupos: " . $e->getMessage();
}

$grupo = $_GET['grupo'] ?? null;

if ($grupo) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id_grupo = ?");
    $stmt->execute([$grupo]);
    $alumnos = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Procóro García Hernández</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <link href="../styles/global.css" rel="stylesheet" />

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
    .text-primary {
    color: #0b0146 !important;
}
  </style>
</head>

<body>

<!-- LOGOS -->
<div class="logos-container">
  <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
  <img src="../assets/img/CRBH3.png" alt="CRBH" class="logo-img">
  <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
  <img src="../assets/img/logo2.png" alt="Marca" class="logo-img">
</div>

<!-- CARD DE BIENVENIDA -->
<section class="card-hero">
  <div class="hero-box">
    <h2>Panel de Grupos</h2>
    <p>Seleccione un grupo para gestionar sus alumnos</p>
    <div class="d-flex justify-content-center mx-auto mt-3">
      <a href="../logout.php" class="btn menu-btn text-white w-100" style="background-color: #8b0000;">
        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
      </a>
    </div>
  </div>
</section>

<!-- MATERIAS Y GRUPOS -->
<section class="container my-4">
  <?php foreach ($data as $materia): ?>
  <div class="mb-5">
    <h2 class="text-primary"><?= htmlspecialchars($materia['nombre']) ?></h2>
    <div class="row">
      <?php foreach ($materia['grupos'] as $grupo): ?>
      <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card h-100 p-3 shadow-sm border-2 d-flex flex-column justify-content-between">
          <div class="mb-3 text-center">
            <h5><?= htmlspecialchars($grupo['id_grupo']) ?></h5>
            <p class="text-muted mb-1"><?= htmlspecialchars($grupo['modalidad']) ?></p>
            <p class="text-muted mb-1"><?= htmlspecialchars($grupo['nivel']) ?></p>
            <p class="text-muted"><?= htmlspecialchars($grupo['horario']) ?></p>
          </div>
          <a href="./modulo-docente/lista-alumnos.php?grupo=<?= $grupo['id_grupo'] ?>" class="btn-stbh text-center">
            Ir <i class="bi bi-arrow-right-circle ms-2"></i>
            </a>

        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; ?>
</section>



<!-- FOOTER -->
<footer class="footer py-4 text-center text-secondary">
  STBH © <?= date("Y") ?> | Todos los derechos reservados
</footer>

<!-- SCRIPTS -->
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
<script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
</body>
</html>
