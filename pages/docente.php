<?php
session_start();
if (!isset($_SESSION['users']) || $_SESSION['users']['id_perfil'] != 1) {
  header("Location: ../pages/loginPersonal.php");
  exit;
}
include_once "../Core/constantes.php";
include_once "../Core/estructura_bd.php";
$MYSQLI = _DB_HDND();
// insertar datos de docente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_user'], $_POST['degree'], $_POST['phone'])) {
  $id_user = (int) $_POST['id_user'];
  $degree = $MYSQLI->real_escape_string($_POST['degree']);
  $phone = $MYSQLI->real_escape_string($_POST['phone']);

  $stmt_check = $MYSQLI->prepare("SELECT id FROM teaching WHERE id_user = ?");
  $stmt_check->bind_param("i", $id_user);
  $stmt_check->execute();
  $result_check = $stmt_check->get_result();

  if ($result_check && $result_check->num_rows === 0) {
    $stmt_insert = $MYSQLI->prepare("INSERT INTO teaching (id_user, highest_degree, phone_number) VALUES (?, ?, ?)");
    $stmt_insert->bind_param("iss", $id_user, $degree, $phone);
    $stmt_insert->execute();
    header("Location: docente.php?success=1");
    exit;
  }
}

// obtener usuarios con perfil docente
$SQL = "
  SELECT u.id, u.email, u.first_name, u.last_name, t.highest_degree, t.phone_number
  FROM users u
  LEFT JOIN teaching t ON u.id = t.id_user
  WHERE u.id_perfil = 2
  ORDER BY u.first_name, u.last_name
";
$RESULT = $MYSQLI->query($SQL);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>STBH | Docentes</title>
	<link rel="icon" type="image/png" href="../assets/img/icon_stbh.png">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
	<link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
	<link href="../assets/css/soft-ui-dashboard.css?v=1.2.2" rel="stylesheet" />
	<link href="../assets/css/usuarios.css?v=1.1.1" rel="stylesheet" />
	<link href="../assets/css/container.css" rel="stylesheet" />
</head>
<body class="bg-light">
	<div class="logos-container">
		<div class="logos">
			<img src="../assets/img/cnbm.png" alt="CNBM" class="logo-img">
			<img src="../assets/img/CRBH3.png" alt="CRBH" class="logo-img">
			<img src="../assets/img/stbm.png" alt="STBM" class="logo-img">
			<img src="../assets/img/logo2.png" alt="STBH" class="logo-img">
		</div>
	</div>

	<section class="card-hero d-flex justify-content-center mt-4">
		<div class="d-flex flex-column align-items-center align-content-center">
			<p>
				<a href="admin.php" class="btn btn-lg bg-white custom-btn px25 px-4 fs-4">
					<i class="bi bi-arrow-left-circle me-2" style="font-size: 25px;"></i> Regresar al Menú Principal
				</a>
			</p>
		</div>
	</section>

	<div class="container mt-4">
		<?php if (isset($_GET['success'])): ?>
			<div class="alert alert-success text-center alert-auto-close">Información del docente guardada correctamente.</div>
		<?php endif; ?>
	</div>

	<div class="card-container">
		<div class="card shadow-sm border-0">
			<section class="users p-4">
				<div class="container">
					<div class="table-responsive">
						<table class="table table-bordered text-center table-stbh">
							<thead>
								<tr>
									<th>Email</th>
									<th>Nombre</th>
									<th>Grado Académico</th>
									<th>Teléfono</th>
									<th>Acción</th>
								</tr>
							</thead>
							<tbody>
								<?php while ($row = $RESULT->fetch_assoc()): ?>
									<tr>
										<td><?= $row['email'] ?></td>
										<td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
										<td><?= $row['highest_degree'] ?? '-' ?></td>
										<td><?= $row['phone_number'] ?? '-' ?></td>
										<td>
											<?php if (!$row['highest_degree']): ?>
												<button class="btn-stbh btn-sm btn_Completar" data-id="<?= $row['id'] ?>">Completar</button>
											<?php else: ?> - <?php endif; ?>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</div>

	<!-- FORMULARIO PARA COMPLETAR DATOS DE DOCENTE -->
	<section id="fondo">
		<div id="form_alta">
			<span class="cerrar">&times;</span>
			<form method="POST" action="docente.php">
				<h2>Completar Información</h2>
				<input type="hidden" name="id_user" id="id_user_input">
				<div class="mb-3">
					<label>Grado Académico</label>
					<input class="form-control" type="text" name="degree" required>
				</div>
				<div class="mb-3">
					<label>Teléfono</label>
					<input class="form-control" type="text" name="phone" required>
				</div>
				<button type="submit" class="btn-stbh">Guardar</button>
			</form>
		</div>
	</section>

	<!-- FOOTER -->
	<footer class="footer py-4">
		<div class="container">
			<div class="row">
				<div class="col-10 mx-auto text-center">
					<p class="mb-0 text-secondary">
						STBH © <script>document.write(new Date().getFullYear())</script> | Todos los derechos Reservados
					</p>
				</div>
			</div>
		</div>
	</footer>

	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const fondo = document.getElementById('fondo');
			const cerrar = document.querySelector('.cerrar');
			const idInput = document.getElementById('id_user_input');

			document.querySelectorAll('.btn_Completar').forEach(btn => {
				btn.addEventListener('click', () => {
					idInput.value = btn.getAttribute('data-id');
					fondo.style.display = 'block';
				});
			});

			cerrar.addEventListener('click', () => fondo.style.display = 'none');
		});

		const alertas = document.querySelectorAll('.alert-auto-close');
		alertas.forEach(alerta => {
			setTimeout(() => {
				alerta.style.opacity = '0';
				setTimeout(() => alerta.remove(), 500); // Da tiempo para el fade
			}, 5000);
		});
	</script>
</body>
</html>
