<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Docentes</title>
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <link href="../assets/css/docentes.css" rel="stylesheet" />
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

<!-- CARD DE MENÚ -->
<section class="card-hero">
  <div class="hero-box">
    <h2>Sección Docentes</h2>
    <div class="btn-group">
      <a href="admin.php" class="btn-stbh btn-lg">Regresar al Menú Principal</a>
      <button class="btn-stbh btn-lg btn_Alta">Nuevo Docente</button>
    </div>
  </div>
</section>

<!-- ALERTAS -->
<div class="container mt-4">
  <div class="alert alert-success alert-auto-close text-center">Docente agregado correctamente.</div>
  <div class="alert alert-info alert-auto-close text-center">Datos del docente actualizados correctamente.</div>
</div>

<!-- TABLA DE DOCENTES -->
<section class="docentes p-4">
  <div class="container">
    <div class="table-responsive">
      <table class="table table-bordered text-center table-stbh">
        <thead>
          <tr>
            <th>Email</th>
            <th>Perfil</th>
            <th>Grado máximo de estudios</th>
            <th>Teléfono</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>docente@stbh.com</td>
            <td>Docente</td>
            <td>Licenciatura</td>
            <td>(555) 123-4567</td>
            <td>
              <button class="btn-stbh btn-sm btn_CambiarDocente" data-id="1" data-degree="Licenciatura" data-phone="(555) 123-4567">
                Cambiar datos
              </button>
            </td>
          </tr>
          <tr>
            <td>docente2@stbh.com</td>
            <td>Docente</td>
            <td>Maestría</td>
            <td>(555) 234-5678</td>
            <td>
              <button class="btn-stbh btn-sm btn_CambiarDocente" data-id="2" data-degree="Maestría" data-phone="(555) 234-5678">
                Cambiar datos
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- FORMULARIO PARA EDITAR DATOS DE DOCENTE -->
<section id="fondo_cambiar">
  <div id="form_cambiar_pass">
    <span class="cerrar_cambiar">&times;</span>
    <form method="POST" action="docentes.php">
      <h2>ACTUALIZAR DATOS DE DOCENTE</h2>
      <input type="hidden" name="id_docente" id="id_docente">
      <div class="mb-3">
        <label>Grado máximo de estudios</label>
        <input class="form-control" type="text" name="max_degree" id="max_degree" required>
      </div>
      <div class="mb-3">
        <label>Número de teléfono</label>
        <input class="form-control" type="text" name="phone_number" id="phone_number" required>
      </div>
      <button type="submit" class="btn-stbh">Actualizar</button>
    </form>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer py-4 text-center text-secondary">
  STBH © <script>document.write(new Date().getFullYear())</script> | Todos los derechos reservados
</footer>

<!-- JS -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const fondo_cambiar = document.getElementById('fondo_cambiar');
  const cerrar_cambiar = document.querySelector('.cerrar_cambiar');
  
  // Evento para mostrar el formulario de edición
  document.querySelectorAll('.btn_CambiarDocente').forEach(btn => {
    btn.addEventListener('click', () => {
      // Llenar los campos con los valores actuales
      document.getElementById('id_docente').value = btn.getAttribute('data-id');
      document.getElementById('max_degree').value = btn.getAttribute('data-degree');
      document.getElementById('phone_number').value = btn.getAttribute('data-phone');
      
      fondo_cambiar.style.display = 'block'; // Mostrar el formulario
    });
  });

  // Evento para cerrar el formulario
  if (cerrar_cambiar) {
    cerrar_cambiar.addEventListener('click', () => fondo_cambiar.style.display = 'none');
  }

  const alerta = document.querySelector('.alert-auto-close');
  if (alerta) {
    setTimeout(() => {
      alerta.style.display = 'none';
    }, 5000);
  }
});
</script>

</body>
</html>
