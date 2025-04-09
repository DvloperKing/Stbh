<?php
session_start();
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();
$SQL = "SELECT u.*,p.name_perfil as perfil FROM users U INNER JOIN perfil p ON u.id_perfil = p.id;";
$registros = false;
$RESULT = _Q($SQL, $MYSQLI, 2);

$SQLPerfiles = "SELECT * FROM perfil;";
$PerfilesData = _Q($SQLPerfiles, $MYSQLI, 2);

$email       = isset($_POST['email']) ? _clean($_POST['email'], $MYSQLI) : '';
$permissions = isset($_POST['name_permissions']) ? $_POST['name_permissions'] : [];
$perfil      = isset($_POST['perfil']) ? _clean($_POST['perfil'], $MYSQLI) : '';
$id_perfil   = isset($_POST['id_perfil']) ? _clean($_POST['id_perfil'], $MYSQLI) : '';

$permissionsxprofile = [];

foreach ($permissions as $key => $value) {
    // Si $value es un array, usa esto:
    if (is_array($value) && isset($value['id_permissions'])) {
        $permissionsxprofile[] = $value['id_permissions'];
    }
    // Si $value es un ID directamente:
    elseif (!is_array($value)) {
        $permissionsxprofile[] = $value;
    }
}

?>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
  <title>
    STBH | Usuarios
  </title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../css/forms.css" rel="stylesheet" />
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
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
    #fondo {
    display: none;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1;
}
  </style>
</head>
    
<body class="">
<div class="logos-container">
    <!-- Imágenes de la clase .logos -->
    <div class="logos">
      <img class="rounded" src="../assets/img/cnbm.png"  alt="CNBM Logo"  style="width: 300px;">
      <img class="rounded" src="../assets/img/CRBH.JPG"  alt="CRBH Logo"  style="width: 100px;">
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
                      <?php
                          include_once "botones.php";
                      ?>
                <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                    <li class="nav-item">
                    <a class="nav-link active text-white" aria-current="page" href="../pages/usuarios.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active text-white" aria-current="page" href="../pages/perfiles.php">Permisos</a>
                    </li>
                    <li calss="nav-item">
                    <a class="nav-link active text-white" href="../logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
          <section class="users">
              <div class="users_table">
                  <div class="users_table_caja">
                      <?php
                      if (in_array(1.1, $permissionsxprofile)) {
                          echo "<button type='button' class='btn_Usuarios btn_Alta mb-3'>Nuevo Usuario</button>";
                      }
                      ?>
                      <table class="table">
                          <thead>
                              <th>email</th>
                              <th>Password</th>
                              <th>Perfil</th>
                              <th>Accion</th>
                          </thead>
                          <tbody>
                              <?php
                              $tabla  =   "";
                              foreach ($RESULT as $key => $value) {
                                  $tabla  .=  "<tr class='usuario". $value['id'] ."'>";
                                  $tabla  .=  "<td>" . $value['email'] . "</td>";
                                  $tabla  .=  "<td>" . $value['pass'] . "</td>";
                                  $tabla  .=  "<td>" . $value['perfil'] . "</td>";
                                  if (in_array(1.2, $permissionsxprofile)) {
                                      $tabla  .=  "<td><button type='button' data-id='" . $value['id'] . "' class='btn btn-danger btn_Baja_Usuarios'>Baja</button></td>";
                                  } else {
                                      $tabla  .=  "<td></td>";
                                  }
                                  $tabla  .=  "</tr>";
                              }
                              echo $tabla;
                              ?>
                          </tbody>
                      </table>
                  </div>
            </div>
        </section>
        <section id="fondo">
            <div id="form_alta">
                <span class="cerrar">×</span>
                <form class="usuarioAlta">
                    <h2>USUARIO NUEVO</h2>
                    <div class="row">
                    <div class="mb-3 col-6">
                        <label for="email">Email</label>
                            <input class="form-control" type="text" required id="email" name="email" placeholder="email">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="pass">Password</label>
                        <input class="form-control" type="pass" required id="pass" name="pass" placeholder="Password">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="perfil">Perfil</label>
                        <select class="form-control" name="perfil" id="perfil" required>
                            <option value="" disabled selected>Selecciona un perfil</option>
                           <?php
                            foreach ($PerfilesData as $key => $value) {
                                echo '<option value="'.$value["id"].'" >'.$value["name_perfil"].'</option>';
                            }
                           ?>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn_Guardar">Guardar</button>
                    </div>

                    </form>
                </div>
            </div>

        </section>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
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
