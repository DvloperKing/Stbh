<?php
session_start();
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();

// CONSULTAR materias con modalidad, semestre y docente asignado
$estructura = $MYSQLI->query("SELECT 
  m.name_modality,
  sml.id_level,
  sub.semester,
  sub.name_subject,
  sub.code,
  CONCAT(u.first_name, ' ', u.last_name) AS docente
FROM teacher_subjects ts
JOIN users u ON u.id = ts.id_user
JOIN subjects sub ON sub.id = ts.id_subject
JOIN subject_modality_level sml ON sml.id_subject = sub.id
JOIN modalities m ON m.id = sml.id_modality
WHERE u.id_perfil = 2
ORDER BY m.name_modality, sub.semester, sub.name_subject");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Grupos</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <link href="../assets/css/usuarios.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="logos-container">
  <div class="logos">
    <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../assets/img/CRBH.JPG" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="Marca" class="logo-img">
  </div>
</div>
<section class="card-hero">
  <div class="hero-box">
    <h3 class="text-center mb-4">Grupos Académicos</h3>
    <div class="btn-group">
      <a href="admin.php" class="btn-stbh btn-lg">Regresar al Menú Principal</a>
    </div>
  </div>
</section>
<div class="container mt-4">
  <div class="table-responsive">
    <table class="table table-bordered text-center bg-white">
      <thead class="table-dark">
        <tr>
          <th>Modalidad</th>
          <th>Semestre</th>
          <th>Materia</th>
          <th>Código</th>
          <th>Docente Asignado</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $estructura->fetch_assoc()): ?>
          <tr>
            <td><?= $row['name_modality'] ?></td>
            <td><?= $row['semester'] ?></td>
            <td><?= $row['name_subject'] ?></td>
            <td><?= $row['code'] ?></td>
            <td><?= $row['docente'] ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
