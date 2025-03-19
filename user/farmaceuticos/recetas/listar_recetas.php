<?php
// session_start(); // Iniciar sesión

// Verificar si el usuario está logueado
// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: login.php'); // Redirigir a la página de inicio de sesión
//     exit();
// }

include "../../config/conexion.php";

// Obtener filtros
$estado = isset($_GET['estado']) ? $_GET['estado'] : 'pendiente';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-d');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');

$query = "SELECT r.*, m.nombre as medicamento, m.stock,
          p.nombre as paciente, d.nombre as doctor,
          r.fecha_emision as fecha_consulta
          FROM recetas r
          INNER JOIN medicamentos m ON r.medicamento_id = m.id
          INNER JOIN pacientes p ON r.paciente_id = p.id
          INNER JOIN usuarios d ON r.medico_id = d.id
          WHERE DATE(r.fecha_emision) BETWEEN '$fecha_inicio' AND '$fecha_fin'";

if ($estado !== 'todos') {
    $query .= " AND r.estado = '$estado'";
}

$query .= " ORDER BY r.fecha_emision DESC";
$resultado = mysqli_query($conexion, $query);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

if (mysqli_num_rows($resultado) == 0) {
    $_SESSION['error'] = "Receta no encontrada";
    header('Location: listar_recetas.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Recetas</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div class="container-fluid mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Gestión de Recetas</h4>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <!-- Filtros -->
                <form class="row mb-4">
                    <div class="col-md-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="todos" <?php echo $estado === 'todos' ? 'selected' : ''; ?>>Todos</option>
                            <option value="pendiente" <?php echo $estado === 'pendiente' ? 'selected' : ''; ?>>Pendientes</option>
                            <option value="dispensada" <?php echo $estado === 'dispensada' ? 'selected' : ''; ?>>Dispensadas</option>
                            <option value="cancelada" <?php echo $estado === 'cancelada' ? 'selected' : ''; ?>>Canceladas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $fecha_fin; ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </form>

                <!-- Tabla de Recetas -->
                <div class="table-responsive">
                    <table class="table table-striped" id="tablaRecetas">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Paciente</th>
                                <th>Doctor</th>
                                <th>Medicamento</th>
                                <th>Cantidad</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th>Dispensador</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($receta = mysqli_fetch_assoc($resultado)): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($receta['fecha_receta'])); ?></td>
                                    <td><?php echo htmlspecialchars($receta['paciente']); ?></td>
                                    <td><?php echo htmlspecialchars($receta['doctor']); ?></td>
                                    <td><?php echo htmlspecialchars($receta['medicamento']); ?></td>
                                    <td><?php echo $receta['cantidad']; ?></td>
                                    <td>
                                        <span class="badge <?php echo $receta['stock'] < $receta['cantidad'] ? 'bg-danger' : 'bg-success'; ?>">
                                            <?php echo $receta['stock']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            <?php 
                                            echo $receta['estado'] === 'pendiente' ? 'bg-warning' : 
                                                ($receta['estado'] === 'dispensada' ? 'bg-success' : 'bg-secondary'); 
                                            ?>">
                                            <?php echo ucfirst($receta['estado']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $receta['dispensador'] ? htmlspecialchars($receta['dispensador']) : '-'; ?></td>
                                    <td>
                                        <?php if ($receta['estado'] === 'pendiente'): ?>
                                            <a href="dispensar_receta.php?id=<?php echo $receta['id']; ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-prescription-bottle-alt"></i> Dispensar
                                            </a>
                                            <button class="btn btn-danger btn-sm" onclick="cancelarReceta(<?php echo $receta['id']; ?>)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php else: ?>
                                            <?php
                                            $_SESSION['error'] = "Esta receta ya ha sido " . $receta['estado'];
                                            header('Location: listar_recetas.php');
                                            exit();
                                            ?>
                                            <button class="btn btn-info btn-sm" onclick="verDetalles(<?php echo $receta['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Detalles -->
    <div class="modal fade" id="detallesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Receta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Los detalles se cargarán dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaRecetas').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                },
                order: [[0, 'desc']]
            });
        });

        function cancelarReceta(id) {
            if (confirm('¿Está seguro de cancelar esta receta?')) {
                window.location.href = 'cancelar_receta.php?id=' + id;
            }
        }

        function verDetalles(id) {
            $.get('obtener_detalles_receta.php?id=' + id, function(data) {
                $('#detallesModal .modal-body').html(data);
                var modal = new bootstrap.Modal(document.getElementById('detallesModal'));
                modal.show();
            });
        }
    </script>
</body>
</html>
