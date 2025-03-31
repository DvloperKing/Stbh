<?php
session_start();

// Simular base de datos con 5 unidades
$estudiantes = [
    1 => [
        'id' => 1, 
        'nombre' => 'Juan Pérez', 
        'unidad1' => 85, 
        'unidad2' => 90,
        'unidad3' => 78,
        'unidad4' => 92,
        'unidad5' => 88
    ],
    2 => [
        'id' => 2, 
        'nombre' => 'María González', 
        'unidad1' => 92, 
        'unidad2' => 88,
        'unidad3' => 95,
        'unidad4' => 89,
        'unidad5' => 91
    ]
];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editar'])) {
        $_SESSION['modo_edicion'] = true;
    } elseif (isset($_POST['guardar'])) {
        foreach ($_POST['calificaciones'] as $id => $califs) {
            if (isset($estudiantes[$id])) {
                // Validar y actualizar unidades
                $estudiantes[$id]['unidad1'] = max(0, min(100, (int)$califs['unidad1']));
                $estudiantes[$id]['unidad2'] = max(0, min(100, (int)$califs['unidad2']));
                $estudiantes[$id]['unidad3'] = max(0, min(100, (int)$califs['unidad3']));
                $estudiantes[$id]['unidad4'] = max(0, min(100, (int)$califs['unidad4']));
                $estudiantes[$id]['unidad5'] = max(0, min(100, (int)$califs['unidad5']));
                
                // Aquí iría la actualización real en base de datos
            }
        }
        $_SESSION['modo_edicion'] = false;
        $_SESSION['mensaje'] = "Cambios guardados exitosamente!";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } elseif (isset($_POST['cancelar'])) {
        $_SESSION['modo_edicion'] = false;
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

$modo_edicion = $_SESSION['modo_edicion'] ?? false;
unset($_SESSION['modo_edicion']);

// Función para calcular promedio
function calcularPromedio($estudiante) {
    $suma = $estudiante['unidad1'] + $estudiante['unidad2'] + $estudiante['unidad3'] 
          + $estudiante['unidad4'] + $estudiante['unidad5'];
    return number_format($suma / 5, 1);
}
?>

<?php if (isset($_SESSION['mensaje'])): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?= $_SESSION['mensaje'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['mensaje']); endif; ?>

<form method="POST">
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Unidad 1</th>
                    <th>Unidad 2</th>
                    <th>Unidad 3</th>
                    <th>Unidad 4</th>
                    <th>Unidad 5</th>
                    <th>Promedio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $est): ?>
                <tr>
                    <td><?= htmlspecialchars($est['nombre']) ?></td>
                    
                    <!-- Campos editables -->
                    <?php if ($modo_edicion): ?>
                        <td><input type="number" name="calificaciones[<?= $est['id'] ?>][unidad1]" 
                                  value="<?= $est['unidad1'] ?>" class="form-control form-control-sm" min="0" max="100"></td>
                        <td><input type="number" name="calificaciones[<?= $est['id'] ?>][unidad2]" 
                                  value="<?= $est['unidad2'] ?>" class="form-control form-control-sm" min="0" max="100"></td>
                        <td><input type="number" name="calificaciones[<?= $est['id'] ?>][unidad3]" 
                                  value="<?= $est['unidad3'] ?>" class="form-control form-control-sm" min="0" max="100"></td>
                        <td><input type="number" name="calificaciones[<?= $est['id'] ?>][unidad4]" 
                                  value="<?= $est['unidad4'] ?>" class="form-control form-control-sm" min="0" max="100"></td>
                        <td><input type="number" name="calificaciones[<?= $est['id'] ?>][unidad5]" 
                                  value="<?= $est['unidad5'] ?>" class="form-control form-control-sm" min="0" max="100"></td>
                        <td class="text-center"><?= calcularPromedio($est) ?></td>
                    <?php else: ?>
                        <!-- Modo visualización -->
                        <td><?= $est['unidad1'] ?></td>
                        <td><?= $est['unidad2'] ?></td>
                        <td><?= $est['unidad3'] ?></td>
                        <td><?= $est['unidad4'] ?></td>
                        <td><?= $est['unidad5'] ?></td>
                        <td class="text-center fw-bold"><?= calcularPromedio($est) ?></td>
                    <?php endif; ?>
                    
                    <td>
                        <?php if ($modo_edicion): ?>
                            <div class="btn-group">
                                <button type="submit" name="guardar" class="btn btn-sm btn-success" title="Guardar">
                                    <i class="bi bi-save"></i>
                                </button>
                                <button type="submit" name="cancelar" class="btn btn-sm btn-secondary" title="Cancelar">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        <?php else: ?>
                            <button type="submit" name="editar" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</form>