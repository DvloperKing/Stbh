<?php
session_start();
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();

$SQL = "SELECT * FROM perfil;";
$RESULT = _Q($SQL, $MYSQLI, 2);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Perfiles</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <link href="../assets/css/usuarios.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .card {
      background-color: #ffffff;
      border-radius: 12px;
      border: 1px solid #e0e0e0;
      transition: all 0.3s ease;
    }
    .card:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .form-check-input:checked {
      background-color: #2c2c54;
      border-color: #2c2c54;
    }
  </style>
</head>

<body>

<!-- LOGOS -->
<div class="logos-container">
  <div class="logos">
    <img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
    <img src="../assets/img/CRBH.JPG" alt="CRBH" class="logo-img">
    <img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
    <img src="../assets/img/logo2.png" alt="Marca" class="logo-img">
  </div>
</div>

<!-- CARD TÍTULO Y BOTÓN -->
<section class="card-hero">
  <div class="hero-box">
    <h2>Sección Perfiles</h2>
    <div class="btn-group">
      <a href="admin.php" class="btn-stbh btn-lg">Regresar al Menú Principal</a>
    </div>
  </div>
</section>

<!-- LISTA DE PERFILES -->
<section class="users p-4">
  <div class="container">
    <div class="text-center mb-4">
      <?php foreach ($RESULT as $perfil): ?>
        <button class="btn-stbh btn-sm mb-2 permissionsxprofileEvento" data-id="<?= $perfil['id'] ?>">
          <?= htmlspecialchars($perfil['name_perfil']) ?>
        </button>
      <?php endforeach; ?>
    </div>
    <div id="permissionsxprofile" class="text-center"></div>
  </div>
</section>

<!-- SCRIPTS -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById('permissionsxprofile');

  document.querySelectorAll('.permissionsxprofileEvento').forEach(btn => {
    btn.addEventListener('click', () => {
      const perfilId = btn.getAttribute('data-id');
      fetchPermissions(perfilId);
    });
  });

  function fetchPermissions(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch("../Core/API/getPermissionsXprofile.php", {
      method: "POST",
      body: formData,
    })
    .then(res => res.json())
    .then(data => {
      if (data.code === 1) {
        let html = `<form class='perfilesForm'><input type='hidden' name='perfilId' value='${id}'>`;
        html += `<h5 class="text-center mb-3">Permisos Asignados</h5>`;
        html += `<div class="row justify-content-center g-3">`;

        data.permissions.forEach(perm => {
          const checked = data.permisosAsignados.includes(perm.id) ? 'checked' : '';
          html += `
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <div class="card p-2 shadow-sm">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input me-2" name="permissions[]" value="${perm.id}" ${checked}>
                  ${perm.name_permissions}
                </label>
              </div>
            </div>`;
        });

        html += `</div><div class="text-center mt-3"><button type="submit" class="btn-stbh btn-sm">Guardar</button></div></form>`;
        container.innerHTML = html;
        bindSaveEvent();
      } else {
        container.innerHTML = "<p>Error al cargar permisos.</p>";
      }
    });
  }

  function bindSaveEvent() {
    document.querySelector('.perfilesForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);

      fetch("../Core/API/savePerfiles.php", {
        method: "POST",
        body: formData,
      })
      .then(res => res.json())
      .then(data => {
        if (data.code === 1) {
          Swal.fire({
            icon: 'success',
            title: 'Permisos guardados correctamente',
            timer: 1500,
            showConfirmButton: false,
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error al guardar permisos',
            text: data.message || 'Intente nuevamente.',
          });
        }
      });
    });
  }
});
</script>

</body>
</html>
