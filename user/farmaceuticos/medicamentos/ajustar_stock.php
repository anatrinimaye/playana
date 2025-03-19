<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es farmacéutico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'farmaceutico') {
    header('Location: ../../login.php');
    exit();
}

// Obtener información del medicamento
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM medicamentos WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);
    $medicamento = mysqli_fetch_assoc($resultado);

    if (!$medicamento) {
        $_SESSION['error'] = "Medicamento no encontrado";
        header('Location: gestionar_medicamentos.php');
        exit();
    }
}

// Procesar ajuste de stock
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['medicamento_id']);
    $cantidad = intval($_POST['cantidad']);
    $tipo_movimiento = mysqli_real_escape_string($conexion, $_POST['tipo_movimiento']);
    $motivo = mysqli_real_escape_string($conexion, $_POST['motivo']);
    $fecha = date('Y-m-d H:i:s');

    mysqli_begin_transaction($conexion);
    try {
        // Actualizar stock
        if ($tipo_movimiento === 'entrada') {
            $query = "UPDATE medicamentos SET stock = stock + $cantidad WHERE id = $id";
        } else {
            $query = "UPDATE medicamentos SET stock = stock - $cantidad WHERE id = $id";
        }
        mysqli_query($conexion, $query);

        // Registrar movimiento
        $query = "INSERT INTO movimientos_stock (
            medicamento_id, tipo_movimiento, cantidad, motivo, 
            usuario_id, fecha_movimiento
        ) VALUES (
            $id, '$tipo_movimiento', $cantidad, '$motivo',
            {$_SESSION['usuario_id']}, '$fecha'
        )";
        mysqli_query($conexion, $query);

        mysqli_commit($conexion);
        $_SESSION['mensaje'] = "Stock ajustado correctamente";
        header('Location: gestionar_medicamentos.php');
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conexion);
        $_SESSION['error'] = "Error al ajustar el stock: " . $e->getMessage();
    }
}

// Obtener historial de movimientos
$id = intval($_GET['id']);
$query = "SELECT m.*, u.nombre as usuario FROM movimientos_stock m 
          INNER JOIN usuarios u ON m.usuario_id = u.id 
          WHERE m.medicamento_id = $id 
          ORDER BY m.fecha_movimiento DESC 
          LIMIT 10";
$historial = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ajustar Stock - <?php echo htmlspecialchars($medicamento['nombre']); ?></title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Ajustar Stock - <?php echo htmlspecialchars($medicamento['nombre']); ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <h6>Información Actual</h6>
                            <p>Stock actual: <strong><?php echo $medicamento['stock']; ?></strong></p>
                            <p>Stock mínimo: <strong><?php echo $medicamento['stock_minimo']; ?></strong></p>
                        </div>

                        <form method="POST">
                            <input type="hidden" name="medicamento_id" value="<?php echo $medicamento['id']; ?>">
                            
                            <div class="mb-3">
                                <label for="tipo_movimiento">Tipo de Movimiento</label>
                                <select class="form-select" id="tipo_movimiento" name="tipo_movimiento" required>
                                    <option value="entrada">Entrada</option>
                                    <option value="salida">Salida</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="cantidad">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                            </div>

                            <div class="mb-3">
                                <label for="motivo">Motivo</label>
                                <textarea class="form-control" id="motivo" name="motivo" rows="3" required></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Guardar Ajuste</button>
                                <a href="gestionar_medicamentos.php" class="btn btn-secondary">Volver</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Historial de Movimientos</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Cantidad</th>
                                        <th>Usuario</th>
                                        <th>Motivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($mov = mysqli_fetch_assoc($historial)): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y H:i', strtotime($mov['fecha_movimiento'])); ?></td>
                                            <td>
                                                <span class="badge <?php echo $mov['tipo_movimiento'] === 'entrada' ? 'bg-success' : 'bg-danger'; ?>">
                                                    <?php echo ucfirst($mov['tipo_movimiento']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo $mov['cantidad']; ?></td>
                                            <td><?php echo htmlspecialchars($mov['usuario']); ?></td>
                                            <td><?php echo htmlspecialchars($mov['motivo']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 