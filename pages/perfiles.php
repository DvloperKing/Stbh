<?php
session_start();
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();
$SQL="SELECT * FROM stbh.perfil;";
$registros = false;
$RESULT = _Q($SQL, $MYSQLI, 2);

$email       = isset($_POST['email']) ? _clean($_POST['email'], $MYSQLI) : '';
$permissions = isset($_POST['name_permissions']) ? $_POST['name_permissions'] : [];
$perfil      = isset($_POST['perfil']) ? _clean($_POST['perfil'], $MYSQLI) : '';
$permissionsxprofile = [];

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
              <div class="perfil_usuario_caja-Perfil">
               
               <div class="contentPerfiles">
               <table class="content-table">
                   <thead>
                       <tr>
                           <th>Perfiles</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php
                       $tabla  =   ""; 
                       foreach ($RESULT as $key => $value) {
                           $tabla  .=  "<tr>"; 
                           $tabla  .=  "<td><button class='permissionsxprofileEvento mb-2 btn btn-primary' id='".$value['id']."'>".$value['name_perfil']."</button></td>";
                           $tabla  .=  "</tr>";
                       }
                       echo $tabla;
                       ?>
                   </tbody>
               </table>
               </div>
               <div id="permissionsxprofile">

               </div>
            </div> 
            </div>
        </section>
        
        <script>
    $("#permissionsxprofile").delegate(".perfilesForm","submit",function(e){
        e.preventDefault();
        // console.log(this);
        var formData = $(this).serialize(); 
                
                $.ajax({ 
                    url: './Core/API/savePerfiles.php', 
                    type: 'POST', 
                    data: formData, 
                    success: function (response) { 
                        // console.log('Your form has been sent successfully.'); 
                        Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: "Se guardo el perfil con exito",
                                    showConfirmButton: false,
                                    timer: 1500
                                    });
                    }, 
                    error: function (jqXHR, textStatus, errorThrown) { 
                        console.log('Your form was not sent successfully.'); 
                    } 
                }); 

    })
       

$(".permissionsxprofileEvento").click(function(){
    // console.log($(this).attr("Id"));
    Perfiles($(this).attr("Id"))
})

function Perfiles(id) {
    
    // console.log(id);
    const formData = new FormData();

    
    
    formData.append("id", id);

    getPermisos(formData,id);
 }

async function getPermisos(formData,id) {
    try {
      const response = await fetch("../Core/API/getPermissionsXprofile.php", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      if(result.code==1 ){
        //window.location.href="./main.php";
        PermisosXPerfil = document.getElementById('permissionsxprofile');
        var permisosInputs = "<table><thead><tr><th colspan='2'>Permisos Asignados</th></tr></thead></table><form class='perfilesForm'><input type='hidden' name='perfilId' value='"+id+"'>";
        
        result.permisos.forEach(function(value){
            // console.log(value.Id,result.permisosAsignados.includes(value.Id),result.permisosAsignados);

            if(result.permisosAsignados.includes(value.Id))
            {
                permisosInputs += `<div><label
                ><input type="checkbox" name="permissions[]" value="${value.Id}" checked id="cbox1" value="first_checkbox" /> ${value.Nombre}</label
                ></div>`;
                
            }else{
                permisosInputs += `<div><label
                ><input type="checkbox" name="permisos[]" value="${value.Id}"  id="cbox1" value="first_checkbox" /> ${value.Nombre}</label
                ></div>`;
                
            }
        })
        permisosInputs += "<div><button type='submit' id='btnGuardar' class='hidden btn btn-primary'>Guardar</button></div> </form>";
        PermisosXPerfil.innerHTML   = "";
        PermisosXPerfil.innerHTML   =   permisosInputs;
      }
    //   console.log("Success:", result,permisosInputs);
    } catch (error) {
      console.error("Error:", error);
    }
  }
    </script>
</body>
</html>
