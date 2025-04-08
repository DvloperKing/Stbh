<?php
session_start();

// Verificar que el usuario sea alumno
if (!isset($_SESSION['alumnos'])) {
    header("Location: ../loginAlum.php");
    exit;
}

// Conexión a la base de datos
include_once "../core/Constantes.php";
include_once "../core/estructura_bd.php";
$MYSQLI = _DB_HDND();

// Obtener datos del alumno
$id_alumno = $_SESSION['alumnos']['id'];
$id_user = $_SESSION['alumnos']['id_user'];

// 1. Obtener materias asignadas al alumno
$sql_materias = "SELECT s.* FROM student_subjects ss 
                JOIN subjects s ON ss.id_subject = s.id 
                WHERE ss.id_user = $id_user";
$materias_alumno = _Q($sql_materias, $MYSQLI, 2);

// 2. Obtener horarios del alumno
$sql_horarios = "SELECT sh.*, s.name_subject FROM student_horarios sh
                JOIN subjects s ON sh.id_subject = s.id
                WHERE sh.id_user = $id_user
                ORDER BY sh.dia_semana, sh.hora_inicio";
$horarios_alumno = _Q($sql_horarios, $MYSQLI, 2);

// 3. Obtener calificaciones del alumno
$sql_calificaciones = "SELECT sc.*, s.name_subject FROM student_calificaciones sc
                     JOIN subjects s ON sc.id_subject = s.id
                     WHERE sc.id_user = $id_user
                     ORDER BY s.semester, s.name_subject";
$calificaciones_alumno = _Q($sql_calificaciones, $MYSQLI, 2);

// Directorio base para material didáctico
$directorioBase = "archivos/";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo Alumno | STBH</title>
    <link rel="stylesheet" href="../assets/css/soft-ui-dashboard.css?v=1.0.8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .card-modulo {
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card-modulo:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #0b0146;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .nav-tabs .nav-link.active {
            background-color: #0b0146;
            color: white;
            border-color: #0b0146;
        }

        .nav-tabs .nav-link {
            color: #0b0146;
        }

        .table-horarios th {
            background-color: #0b0146;
            color: white;
        }

        .badge-semester {
            background-color: #6f42c1;
        }

        .btn-download {
            background-color: #28a745;
            color: white;
        }

        .btn-view {
            background-color: #17a2b8;
            color: white;
        }

        .welcome-section {
            background: linear-gradient(135deg, #0b0146 0%, #1a237e 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg" style="background-color: rgba(11, 1, 70, 1);">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">
                <i class="fas fa-user-graduate me-2"></i>STBH | Módulo Alumno
            </a>
            <div class="d-flex">
                <span class="text-white me-3"><?= $_SESSION['alumnos']['first_name'] ?></span>
                <a href="../logout.php" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mt-4">
        <!-- Bienvenida -->
        <div class="welcome-section">
            <div class="row">
                <div class="col-md-8">
                    <h2><i class="fas fa-user-graduate me-2"></i> Bienvenido, <?= $_SESSION['alumnos']['first_name'] ?> <?= $_SESSION['alumnos']['last_name'] ?></h2>
                    <p class="mb-0">Número de control: <?= $_SESSION['alumnos']['control_number'] ?> | Semestre: <?= $_SESSION['alumnos']['semester'] ?></p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-inline-block bg-white p-2 rounded">
                        <small class="text-dark">Fecha: <?= date('d/m/Y') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pestañas -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="horarios-tab" data-bs-toggle="tab" data-bs-target="#horarios" type="button" role="tab">
                    <i class="fas fa-calendar-alt me-2"></i>Horarios
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="calificaciones-tab" data-bs-toggle="tab" data-bs-target="#calificaciones" type="button" role="tab">
                    <i class="fas fa-clipboard-list me-2"></i>Calificaciones
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="material-tab" data-bs-toggle="tab" data-bs-target="#material" type="button" role="tab">
                    <i class="fas fa-book me-2"></i>Material Didáctico
                </button>
            </li>
        </ul>

        <!-- Contenido de las pestañas -->
        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="myTabContent">
            <!-- Pestaña Horarios -->
            <div class="tab-pane fade show active" id="horarios" role="tabpanel">
                <h3 class="mb-4"><i class="fas fa-calendar-alt me-2"></i> Mis Horarios</h3>

                <?php if (!empty($horarios_alumno)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-horarios">
                            <thead>
                                <tr>
                                    <th>Día</th>
                                    <th>Hora</th>
                                    <th>Materia</th>
                                    <th>Aula</th>
                                    <th>Docente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($horarios_alumno as $horario): ?>
                                    <tr>
                                        <td><?= obtenerDiaSemana($horario['dia_semana']) ?></td>
                                        <td><?= $horario['hora_inicio'] ?> - <?= $horario['hora_fin'] ?></td>
                                        <td><?= $horario['name_subject'] ?></td>
                                        <td><?= $horario['aula'] ?></td>
                                        <td><?= obtenerNombreDocente($horario['id_teacher'], $MYSQLI) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No tienes horarios asignados todavía.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pestaña Calificaciones -->
            <div class="tab-pane fade" id="calificaciones" role="tabpanel">
                <h3 class="mb-4"><i class="fas fa-clipboard-list me-2"></i> Mis Calificaciones</h3>

                <?php if (!empty($calificaciones_alumno)): ?>
                    <?php
                    // Agrupar por semestre
                    $calificaciones_por_semestre = [];
                    foreach ($calificaciones_alumno as $calificacion) {
                        $semestre = $calificacion['semester'];
                        $calificaciones_por_semestre[$semestre][] = $calificacion;
                    }
                    ?>

                    <?php foreach ($calificaciones_por_semestre as $semestre => $calificaciones): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4 class="mb-0">Semestre <?= $semestre ?></h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Materia</th>
                                                <th>Parcial 1</th>
                                                <th>Parcial 2</th>
                                                <th>Parcial 3</th>
                                                <th>Promedio</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($calificaciones as $calificacion): ?>
                                                <tr>
                                                    <td><?= $calificacion['name_subject'] ?></td>
                                                    <td><?= $calificacion['parcial1'] ?? 'N/A' ?></td>
                                                    <td><?= $calificacion['parcial2'] ?? 'N/A' ?></td>
                                                    <td><?= $calificacion['parcial3'] ?? 'N/A' ?></td>
                                                    <td><strong><?= calcularPromedio($calificacion) ?></strong></td>
                                                    <td><?= obtenerEstado(calcularPromedio($calificacion)) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        No hay calificaciones registradas todavía.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pestaña Material Didáctico -->
            <div class="tab-pane fade" id="material" role="tabpanel">
                <h3 class="mb-4"><i class="fas fa-book me-2"></i> Material Didáctico</h3>

                <?php if (!empty($materias_alumno)): ?>
                    <div class="row">
                        <?php foreach ($materias_alumno as $materia): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card card-modulo">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-book-open me-2"></i>
                                            <?= $materia['name_subject'] ?>
                                            <span class="badge badge-semester float-end">Sem <?= $materia['semester'] ?></span>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        $rutaMateria = $directorioBase . $materia['code'] . "/";

                                        if (is_dir($rutaMateria)) {
                                            $archivos = array_diff(scandir($rutaMateria), array('..', '.'));

                                            if (!empty($archivos)) {
                                                echo '<ul class="list-group">';
                                                foreach ($archivos as $archivo) {
                                                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                                    echo '<span><i class="fas fa-file me-2"></i>' . $archivo . '</span>';
                                                    echo '<div>';
                                                    echo '<a href="' . $rutaMateria . $archivo . '" class="btn btn-download btn-sm me-1" download><i class="fas fa-download"></i></a>';

                                                    if (preg_match('/\.(jpg|png|pdf)$/i', $archivo)) {
                                                        echo '<a href="' . $rutaMateria . $archivo . '" class="btn btn-view btn-sm" target="_blank"><i class="fas fa-eye"></i></a>';
                                                    }
                                                    echo '</div>';
                                                    echo '</li>';
                                                }
                                                echo '</ul>';
                                            } else {
                                                echo '<div class="alert alert-info mb-0">No hay material disponible para esta materia.</div>';
                                            }
                                        } else {
                                            echo '<div class="alert alert-info mb-0">No hay material disponible para esta materia.</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No tienes materias asignadas todavía.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer py-4 bg-light mt-5">
        <div class="container text-center">
            <p class="mb-0">STBH © <script>
                    document.write(new Date().getFullYear())
                </script> | Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Activar tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Guardar la pestaña activa en localStorage
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                localStorage.setItem('ultimaPestañaActiva', $(e.target).attr('href'));
            });

            // Recuperar la última pestaña activa
            var ultimaPestañaActiva = localStorage.getItem('ultimaPestañaActiva');
            if (ultimaPestañaActiva) {
                $('[href="' + ultimaPestañaActiva + '"]').tab('show');
            }
        });
    </script>
</body>

</html>

<?php
// Funciones auxiliares
function obtenerDiaSemana($numeroDia)
{
    $dias = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado'
    ];
    return $dias[$numeroDia] ?? 'Desconocido';
}

function obtenerNombreDocente($id_teacher, $MYSQLI)
{
    $sql = "SELECT first_name, last_name FROM teaching WHERE id = $id_teacher";
    $docente = _Q($sql, $MYSQLI, 1);
    return $docente ? $docente['first_name'] . ' ' . $docente['last_name'] : 'Por asignar';
}

function calcularPromedio($calificacion)
{
    $suma = 0;
    $contador = 0;

    for ($i = 1; $i <= 3; $i++) {
        if (isset($calificacion['parcial' . $i]) && is_numeric($calificacion['parcial' . $i])) {
            $suma += $calificacion['parcial' . $i];
            $contador++;
        }
    }

    return $contador > 0 ? round($suma / $contador, 1) : 'N/A';
}

function obtenerEstado($promedio)
{
    if (!is_numeric($promedio)) return '';

    if ($promedio >= 6) {
        return '<span class="badge bg-success">Aprobado</span>';
    } else {
        return '<span class="badge bg-danger">Reprobado</span>';
    }
}
?>