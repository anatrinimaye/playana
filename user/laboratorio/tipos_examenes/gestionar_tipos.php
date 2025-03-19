<?php
//session_start();
include '../../config/conexion.php';
/*
// Verificar si el usuario está logueado y es personal de laboratorio
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'laboratorio') {
    header('Location: ../../login.php');
    exit();
}
*/
// Procesar formulario de creación/edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $tiempo_estimado = mysqli_real_escape_string($conexion, $_POST['tiempo_estimado']);
    $requiere_ayuno = isset($_POST['requiere_ayuno']) ? 1 : 0;
    $instrucciones = mysqli_real_escape_string($conexion, $_POST['instrucciones']);
    
    if ($id) {
        // Actualizar
        $query = "UPDATE tipos_examenes SET 
            nombre = '$nombre',
            descripcion = '$descripcion',
            precio = $precio,
            tiempo_estimado = '$tiempo_estimado',
            requiere_ayuno = $requiere_ayuno,
            instrucciones_previas = '$instrucciones'
            WHERE id = $id";
    } else {
        // Insertar nuevo
        $query = "INSERT INTO tipos_examenes (
            nombre, descripcion, precio, tiempo_estimado, 
            requiere_ayuno, instrucciones_previas, estado
        ) VALUES (
            '$nombre', '$descripcion', $precio, '$tiempo_estimado',
            $requiere_ayuno, '$instrucciones', 'activo'
        )";
    }
    
    if (mysqli_query($conexion, $query)) {
        $_SESSION['mensaje'] = $id ? "Tipo de examen actualizado correctamente" : "Tipo de examen creado correctamente";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conexion);
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Obtener lista de tipos de exámenes
$query = "SELECT * FROM tipos_examenes ORDER BY nombre";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Tipos de Exámenes</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div class="container-fluid mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Tipos de Exámenes</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalExamen">
                    <i class="fas fa-plus"></i> Nuevo Tipo de Examen
                </button>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped" id="tablaExamenes">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Tiempo Estimado</th>
                                <th>Requiere Ayuno</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($tipo = mysqli_fetch_assoc($resultado)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($tipo['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($tipo['descripcion']); ?></td>
                                    <td>$<?php echo number_format($tipo['precio'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($tipo['tiempo_estimado']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $tipo['requiere_ayuno'] ? 'bg-warning' : 'bg-success'; ?>">
                                            <?php echo $tipo['requiere_ayuno'] ? 'Sí' : 'No'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $tipo['estado'] === 'activo' ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo ucfirst($tipo['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="editarTipo(<?php echo htmlspecialchars(json_encode($tipo)); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="valores_referencia.php?tipo_id=<?php echo $tipo['id']; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-list"></i> Valores
                                        </a>
                                        <button class="btn btn-sm btn-danger" onclick="cambiarEstado(<?php echo $tipo['id']; ?>, '<?php echo $tipo['estado'] === 'activo' ? 'inactivo' : 'activo'; ?>')">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar tipo de examen -->
    <div class="modal fade" id="modalExamen" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tipo de Examen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        
                        <div class="mb-3">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precio">Precio</label>
                                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tiempo_estimado">Tiempo Estimado</label>
                                    <input type="text" class="form-control" id="tiempo_estimado" name="tiempo_estimado" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="requiere_ayuno" name="requiere_ayuno">
                                <label class="form-check-label" for="requiere_ayuno">Requiere Ayuno</label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="instrucciones">Instrucciones Previas</label>
                            <textarea class="form-control" id="instrucciones" name="instrucciones" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaExamenes').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                }
            });
        });

        function editarTipo(tipo) {
            $('#id').val(tipo.id);
            $('#nombre').val(tipo.nombre);
            $('#descripcion').val(tipo.descripcion);
            $('#precio').val(tipo.precio);
            $('#tiempo_estimado').val(tipo.tiempo_estimado);
            $('#requiere_ayuno').prop('checked', tipo.requiere_ayuno == 1);
            $('#instrucciones').val(tipo.instrucciones_previas);
            $('#modalExamen').modal('show');
        }

        function cambiarEstado(id, nuevoEstado) {
            if (confirm('¿Está seguro de cambiar el estado de este tipo de examen?')) {
                fetch('cambiar_estado.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}&estado=${nuevoEstado}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error al cambiar el estado');
                    }
                });
            }
        }
    </script>
</body>
</html> 