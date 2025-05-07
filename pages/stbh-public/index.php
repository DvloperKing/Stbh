<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STBH | Public</title>
  <link rel="icon" type="image/png" href="../../assets/img/icon_stbh.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/css/public.css">
  <!-- AOS Animaciones -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">

  <!-- Logos superiores -->
  <div class="logos-container text-center py-3 bg-white border-bottom">
    <img src="../../assets/img/cnbm.png" alt="CNBM" class="logo-img mx-2" style="max-height: 60px;">
    <img src="../../assets/img/CRBH3.png" alt="CRBH" class="logo-img mx-2" style="max-height: 60px;">
    <img src="../../assets/img/stbm.png" alt="STBM" class="logo-img mx-2" style="max-height: 60px;">
    <img src="../../assets/img/logo2.png" alt="Marca" class="logo-img mx-2" style="max-height: 60px;">
  </div>

  <!-- Barra de navegación -->
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0b0146;">
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="#quienes-somos">¿Quiénes Somos?</a></li>
          <li class="nav-item"><a class="nav-link" href="#oferta">Oferta Académica</a></li>
          <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
          <li class="nav-item">
            <a class="btn btn-outline-light ms-2" href="../../index.php">Acceso Plataforma</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Inicio pantalla completa con fondo visible -->
<section id="inicio" class="position-relative text-white" style="min-height: 100vh; overflow: hidden;">
  <!-- Imagen de fondo menos difuminada y más visible -->
  <div class="position-absolute top-0 start-0 w-100 h-100" style="
      background: url('../../assets/img/stbh_fondo.jpg') center center / cover no-repeat;
      filter: blur(3px) brightness(0.5);
      z-index: 1;
  "></div>

  <!-- Contenido centrado con letras más grandes -->
  <div class="position-relative d-flex flex-column justify-content-center align-items-center h-100 text-center px-3" style="z-index: 2;">
    <h1 class="fw-bold" style="font-size: 3.2rem;">Seminario Teológico Bautista Las Huastecas</h1>
    <p class="lead" style="font-size: 1.6rem;">“Prócoro García Hernández”<br>Capacitando líderes para formar siervos</p>
  </div>
</section>


  <!-- ¿Quiénes Somos? -->
  <section id="quienes-somos" class="py-5 bg-light" data-aos="fade-up">
    <div class="container">
      <h2 class="text-center mb-4">¿Quiénes Somos?</h2>
      <h4 class="text-center">Somos una institución cristiana enfocada en la formación bíblica, teológica y ministerial de siervos comprometidos con el Reino de Dios. Desde hace años, nuestra misión es preparar líderes transformadores con un enfoque práctico y espiritual.</h4>
    </div>
  </section>

  <!-- Misión y Visión -->
  <section id="mision-vision" class="py-5 bg-light">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-5 mb-4" data-aos="fade-up">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center">
              <h5 class="card-title">Misión</h5>
              <p class="card-text">Proveer una educación Teológica y Bíblica, que ayude a los obreros e Iglesias en su crecimiento integral ministerial.</p>
            </div>
          </div>
        </div>
        <div class="col-md-5 mb-4" data-aos="fade-up" data-aos-delay="200">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center">
              <h5 class="card-title">Visión</h5>
              <p class="card-text">Ser una institución altamente competitiva y saludable cuyo único fin es: la formación de siervos que sean capaces de sembrar, establecer y multiplicar iglesias.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Oferta Académica -->
  <section id="oferta" class="py-5" data-aos="fade-up">
    <div class="container">
      <h2 class="text-center mb-4">Oferta Académica</h2>
      <div class="row text-center">
        <div class="col-md-4">
          <h5>Adiestramiento Ministerial (Nivel Básico)</h5>
          <li>Introducción al A.T. I</li>
          <li>Homilética I</li>
          <li>Fundamentos de Dirección de Cantos I</li>
          <li>Introducción al N.T.</li>
          <li>Iglecrecimiento I</li>
        </div>
        <div class="col-md-4">
          <h5>Bachillerato En Teología</h5>
          <li>Evangelios Sinópticos</li>
          <li>Ministrando a la familia I</li>
          <li>Teología Sistemática</li>
          <li>Taller de enseñanza bíblica</li>
          <li>Epístolas Generales</li>
        </div>
        <div class="col-md-4">
          <h5>Modalidades</h5>
          <li>Internado</li>
          <li>Sabatino</li>
          <li>Online</li>
        </div>
      </div>
    </div>
  </section>

  <!-- Contacto -->
  <section id="contacto" class="py-5" data-aos="fade-up">
    <div class="container text-center">
      <h2 class="mb-4">Contáctanos</h2>
      <p>email: contacto@stbh.edu.mx | Tel: +52 (846)102-9084</p>
      <p>Las Mesillas San Gabriel, Tantoyuca Veracruz</p>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center text-white py-3" style="background-color: #0b0146;">
    <p class="mb-0">&copy; 2025 STBH - Todos los derechos reservados</p>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      once: true,
      duration: 1000
    });
  </script>

</body>
</html>
