<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/icon_stbh.png">
  <title>
    STBH | Procóro García Hernández
  </title>
  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
  <link id="pagestyle" href="./assets/css/soft-ui-dashboard.css?v=1.0.8" rel="stylesheet" />
  <style>
    .move-up {
      margin-top: -50px;
    }
    .image-container {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .oblique-image {
      max-width: 100%;
      height: auto;
    }
    .logos-container {
      background-color: #fff;
      padding: 12px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      gap: 20px;
    }
    .logos img.rounded {
      width: auto;
      height: 80px;
    }
    @media (max-width: 768px) {
      .logos img.rounded {
        height: 60px;
      }
      .move-up {
        margin-top: 0;
      }
    }
  </style>
</head>

<body>
  <div class="logos-container">
    <div class="logos">
      <img class="rounded" src="./assets/img/cnbm.png" alt="CNBM Logo">
      <img class="rounded" src="./assets/img/CRBH2.png" alt="CRBH Logo">
      <img class="rounded" src="./assets/img/stbm.png" alt="STBM Logo">
    </div>
  </div>

  <main class="main-content mt-0">
    <section>
      <div class="page-header min-vh-75 move-up d-flex align-items-center">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-5 col-md-6 mx-auto">
              <div class="card card-plain">
                <div class="card-header pb-0 text-center bg-transparent">
                  <h3 class="font-weight-bolder text-info text-gradient">Bienvenido!</h3>
                  <p class="mb-0">Seleccione su Perfil</p>
                </div>
                <div class="card-body">
                  <form role="form">
                    <div class="d-grid gap-3">
                      <button type="button" onclick="location.href='./pages/loginPersonal.php'" class="btn bg-gradient-info">Administración</button>
                      <button type="button" onclick="location.href='./pages/loginPersonal.php'" class="btn bg-gradient-info">Docente</button>
                      <button type="button" onclick="location.href='./pages/loginAlum.php'" class="btn bg-gradient-info">Alumno</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-lg-6 d-flex justify-content-center">
              <img class="oblique-image" src="./assets/img/curved-images/logo2.png" alt="Logo">
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <footer class="footer py-5">
    <div class="container text-center">
      <p class="mb-0 text-secondary">
        STBH © <script>document.write(new Date().getFullYear())</script> | Todos los derechos Reservados
      </p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
