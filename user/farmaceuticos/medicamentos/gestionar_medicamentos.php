<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es farmacéutico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'farmaceutico') {
    header('Location: ../../login.php');
    exit();
}

// Procesar formulario de nuevo medicamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $principio_activo = mysqli_real_escape_string($conexion, $_POST['principio_activo']);
        $concentracion = mysqli_real_escape_string($conexion, $_POST['concentracion']);
        $presentacion = mysqli_real_escape_string($conexion, $_POST['presentacion']);
        $laboratorio = mysqli_real_escape_string($conexion, $_POST['laboratorio']);
        $stock = intval($_POST['stock']);
        $stock_minimo = intval($_POST['stock_minimo']);
        $precio = floatval($_POST['precio']);
        
        if ($_POST['accion'] === 'crear') {
            $query = "INSERT INTO medicamentos (
                nombre, principio_activo, concentracion, presentacion, 
                laboratorio, stock, stock_minimo, precio, estado
            ) VALUES (
                '$nombre', '$principio_activo', '$concentracion', '$presentacion',
                '$laboratorio', $stock, $stock_minimo, $precio, 'disponible'
            )";
        } else if ($_POST['accion'] === 'editar') {
            $id = intval($_POST['medicamento_id']);
            $query = "UPDATE medicamentos SET 
                nombre = '$nombre',
                principio_activo = '$principio_activo',
                concentracion = '$concentracion',
                presentacion = '$presentacion',
                laboratorio = '$laboratorio',
                stock = $stock,
                stock_minimo = $stock_minimo,
                precio = $precio
                WHERE id = $id";
        }
        
        if (mysqli_query($conexion, $query)) {
            $_SESSION['mensaje'] = "Medicamento " . ($_POST['accion'] === 'crear' ? 'agregado' : 'actualizado') . " correctamente";
        } else {
            $_SESSION['error'] = "Error al " . ($_POST['accion'] === 'crear' ? 'agregar' : 'actualizar') . " el medicamento: " . mysqli_error($conexion);
        }
    }
}

// Obtener lista de medicamentos
$query = "SELECT * FROM medicamentos ORDER BY nombre";
$resultado = mysqli_query($conexion, $query);

// Obtener medicamentos con stock bajo
$query_stock_bajo = "SELECT * FROM medicamentos WHERE stock <= stock_minimo ORDER BY nombre";
$resultado_stock_bajo = mysqli_query($conexion, $query_stock_bajo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Medicamentos</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Inventario de Medicamentos</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#medicamentoModal">
                            <i class="fas fa-plus"></i> Nuevo Medicamento
                        </button>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['mensaje'])): ?>
                            <div class="alert alert-success"><?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php endif; ?>

                        <table class="table table-striped" id="tablaMedicamentos">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Principio Activo</th>
                                    <th>Concentración</th>
                                    <th>Presentación</th>
                                    <th>Laboratorio</th>
                                    <th>Stock</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($med = mysqli_fetch_assoc($resultado)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($med['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($med['principio_activo']); ?></td>
                                        <td><?php echo htmlspecialchars($med['concentracion']); ?></td>
                                        <td><?php echo htmlspecialchars($med['presentacion']); ?></td>
                                        <td><?php echo htmlspecialchars($med['laboratorio']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $med['stock'] <= $med['stock_minimo'] ? 'bg-danger' : 'bg-success'; ?>">
                                                <?php echo $med['stock']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo number_format($med['precio'], 2); ?></td>
                                        <td>
                                            <span class="badge <?php echo $med['estado'] === 'disponible' ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo ucfirst($med['estado']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" onclick="editarMedicamento(<?php echo htmlspecialchars(json_encode($med)); ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-info btn-sm" onclick="ajustarStock(<?php echo $med['id']; ?>)">
                                                <i class="fas fa-boxes"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Alertas de Stock -->
                <div class="card mb-3">
                    <div class="card-header bg-warning text-dark">
                        <h5><i class="fas fa-exclamation-triangle"></i> Alertas de Stock</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php while ($med = mysqli_fetch_assoc($resultado_stock_bajo)): ?>
                                <div class="list-group-item">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($med['nombre']); ?></h6>
                                    <p class="mb-1">Stock actual: <strong><?php echo $med['stock']; ?></strong></p>
                                    <p class="mb-1">Stock mínimo: <strong><?php echo $med['stock_minimo']; ?></strong></p>
                                    <button class="btn btn-primary btn-sm" onclick="ajustarStock(<?php echo $med['id']; ?>)">
                                        Ajustar Stock
                                    </button>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="card">
                    <div class="card-header">
                        <h5>Estadísticas</h5>
                    </div>
                    <div class="card-body">
                        <!-- Aquí irán las estadísticas -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Medicamento -->
    <div class="modal fade" id="medicamentoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Medicamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="medicamentoForm" method="POST">
                        <input type="hidden" name="accion" value="crear">
                        <input type="hidden" name="medicamento_id" id="medicamento_id">
                        
                        <div class="mb-3">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="principio_activo">Principio Activo</label>
                            <input type="text" class="form-control" id="principio_activo" name="principio_activo" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="concentracion">Concentración</label>
                                    <input type="text" class="form-control" id="concentracion" name="concentracion" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="presentacion">Presentación</label>
                                    <input type="text" class="form-control" id="presentacion" name="presentacion" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="laboratorio">Laboratorio</label>
                            <input type="text" class="form-control" id="laboratorio" name="laboratorio" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="stock">Stock</label>
                                    <input type="number" class="form-control" id="stock" name="stock" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="stock_minimo">Stock Mínimo</label>
                                    <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="precio">Precio</label>
                                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
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
            $('#tablaMedicamentos').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                }
            });
        });

        function editarMedicamento(medicamento) {
            document.getElementById('medicamento_id').value = medicamento.id;
            document.getElementById('nombre').value = medicamento.nombre;
            document.getElementById('principio_activo').value = medicamento.principio_activo;
            document.getElementById('concentracion').value = medicamento.concentracion;
            document.getElementById('presentacion').value = medicamento.presentacion;
            document.getElementById('laboratorio').value = medicamento.laboratorio;
            document.getElementById('stock').value = medicamento.stock;
            document.getElementById('stock_minimo').value = medicamento.stock_minimo;
            document.getElementById('precio').value = medicamento.precio;
            
            document.querySelector('[name="accion"]').value = 'editar';
            var modal = new bootstrap.Modal(document.getElementById('medicamentoModal'));
            modal.show();
        }

        function ajustarStock(id) {
            // Implementar ajuste de stock
            window.location.href = 'ajustar_stock.php?id=' + id;
        }
    </script>
</body>
</html> 