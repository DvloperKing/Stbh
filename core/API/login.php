// include_once "../Constantes.php";
// include_once "../estructura_bd.php";

// $MYSQLI = _DB_HDND();

// $email = _clean($_POST['email'], $MYSQLI);
// $pass = _clean($_POST['pass'], $MYSQLI);
// $tipo = _clean($_POST['tipo'], $MYSQLI);
// var_dump($_POST);
// if($tipo==1){
// $SQL = "SELECT u.*,p.name_perfil as perfil from users u inner join perfil p on u.id_perfil = p.id;";
// $registros = false;
// $RESULT = _Q($SQL, $MYSQLI, 2);
// foreach ($RESULT as $key => $value) {
// if ($value['email'] == $email && $value['pass'] == $pass) {
// $registros=$value;
// }
// }

// if ($registros) {
// session_start();
// $SQLP = "SELECT id_permissions FROM permissionsxprofile WHERE id_perfil = " . $registros['id_perfil'];

// $RESULT_P = _Q($SQLP, $MYSQLI, 2);
// $_SESSION ['users'] = $registros;
// $_SESSION ['permisos'] = $RESULT_P;
// $response = ["code" =>1, "response"=>$registros, "permisos"=>$RESULT_P];
// }
// else{
// $response = ["code"=>0, "response"=>$registros];
// }
// echo json_encode($response, JSON_UNESCAPED_UNICODE);
// }else if ($tipo==2){
// $SQL = "SELECT u.*,s.* FROM users u INNER JOIN students s ON u.id=s.id_user;";
// $registros = false;
// $RESULT = _Q($SQL, $MYSQLI, 2);
// foreach ($RESULT as $key => $value) {
// if($value['email'] == $email && $value['pass'] == $pass){
// $registros=$value;
// }
// }

// if($registros){
// session_start();
// $_SESSION['alumnos'] = $registros;
// $response = ["code"=>2, "response"=>$registros];
// }else{
// $response = ["code"=>0, "response"=>$registros];
// }
// echo json_encode($response, JSON_UNESCAPED_UNICODE);
// }

<?php
session_start();

// Incluir archivos necesarios
include_once "../Constantes.php";
include_once "../estructura_bd.php";

// Conectar a la base de datos
$MYSQLI = _DB_HDND();
if (!$MYSQLI) {
    header("Location: ../../loginAlum.php?error=2"); // Error de conexión a la BD
    exit();
}

// Limpiar y obtener datos del formulario
$email = isset($_POST['email']) ? _clean($_POST['email'], $MYSQLI) : '';
$pass = isset($_POST['pass']) ? _clean($_POST['pass'], $MYSQLI) : '';
$tipo = isset($_POST['tipo']) ? _clean($_POST['tipo'], $MYSQLI) : '';

// Verificar que el tipo sea para alumnos
if ($tipo == 2) {
    // Consulta para verificar las credenciales
    $SQL = "SELECT u.id, u.email, s.id AS student_id, s.first_name, s.last_name, s.control_number, s.semester 
            FROM users u 
            INNER JOIN students s ON u.id = s.id_user 
            WHERE u.email = '$email' AND u.pass = '$pass'";
    $result = _Q($SQL, $MYSQLI, 2); // Usar TIPO 2 para SELECT con resultados asociativos

    if ($result && !empty($result)) {
        // Guardar datos del alumno en la sesión
        $_SESSION['alumnos'] = [
            'id' => $result[0]['student_id'], // ID del estudiante
            'id_user' => $result[0]['id'],    // ID del usuario
            'first_name' => $result[0]['first_name'],
            'last_name' => $result[0]['last_name'],
            'control_number' => $result[0]['control_number'],
            'semester' => $result[0]['semester']
        ];
        header("Location: ../../pages/Modulo_Alumnos.php");
        exit();
    } else {
        header("Location: ../../loginAlum.php?error=1"); // Credenciales incorrectas
        exit();
    }
} else {
    // Si el tipo no es 2, redirigir con error o manejar otro caso
    header("Location: ../../loginAlum.php?error=3"); // Tipo inválido
    exit();
}

// Cerrar conexión (opcional, pero buena práctica)
UNDB($MYSQLI);
?>