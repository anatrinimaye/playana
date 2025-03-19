<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es personal de laboratorio
// if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'laboratorio') {
//     header('Location: ../../login.php');
//     exit();
// }

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $tiempo_estimado = mysqli_real_escape_string($conexion, $_POST['tiempo_estimado']);
    $requiere_ayuno = isset($_POST['requiere_ayuno']) ? 1 : 0;
    $instrucciones = mysqli_real_escape_string($conexion, $_POST['instrucciones']);
    
    if (isset($_POST['id'])) {
        // Actualizar
        $id = intval($_POST['id']);
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
        $_SESSION['mensaje'] = isset($_POST['id']) ? "Tipo de examen actualizado" : "Tipo de examen creado";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conexion);
    }
}

// Obtener lista de tipos de exámenes
$query = "SELECT * FROM tipos_examenes ORDER BY nombre";
$resultado = mysqli_query($conexion, $query);

if (!$resultado) {
    $_SESSION['error'] = "Error al obtener tipos de exámenes: " . mysqli_error($conexion);
    header('Location: gestionar_solicitudes.php');
    exit();
}
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
            <div class="card-header">
                <h4>Tipos de Exámenes</h4>
            </div>
            <div class="card-body">
                <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalExamen">
                    <i class="fas fa-plus"></i> Nuevo Tipo de Examen
                </button>

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
                                <th>Tiempo Est.</th>
                                <th>Ayuno</th>
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
                                        <button class="btn btn-sm btn-info" onclick="editarTipo(<?php echo $tipo['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="verValores(<?php echo $tipo['id']; ?>)">
                                            <i class="fas fa-list"></i> Valores
                                        </button>
                                        <button class="btn btn-sm <?php echo $tipo['estado'] === 'activo' ? 'btn-danger' : 'btn-success'; ?>"
                                                onclick="cambiarEstado(<?php echo $tipo['id']; ?>, '<?php echo $tipo['estado']; ?>')">
                                            <i class="fas <?php echo $tipo['estado'] === 'activo' ? 'fa-times' : 'fa-check'; ?>"></i>
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
                    <input type="hidden" name="id" id="examen_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nombre</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Precio</label>
                                <input type="number" step="0.01" class="form-control" name="precio" id="precio" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label>Descripción</label>
                            <textarea class="form-control" name="descripcion" id="descripcion" rows="2"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Tiempo Estimado</label>
                                <input type="text" class="form-control" name="tiempo_estimado" id="tiempo_estimado" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-4">
                                    <input type="checkbox" class="form-check-input" name="requiere_ayuno" id="requiere_ayuno">
                                    <label class="form-check-label">Requiere Ayuno</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label>Instrucciones Previas</label>
                            <textarea class="form-control" name="instrucciones" id="instrucciones" rows="3"></textarea>
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

        function editarTipo(id) {
            fetch('obtener_tipo.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('examen_id').value = data.id;
                    document.getElementById('nombre').value = data.nombre;
                    document.getElementById('descripcion').value = data.descripcion;
                    document.getElementById('precio').value = data.precio;
                    document.getElementById('tiempo_estimado').value = data.tiempo_estimado;
                    document.getElementById('requiere_ayuno').checked = data.requiere_ayuno == 1;
                    document.getElementById('instrucciones').value = data.instrucciones_previas;
                    
                    new bootstrap.Modal(document.getElementById('modalExamen')).show();
                });
        }

        function verValores(id) {
            window.location.href = 'ver_valores.php?id=' + id;
        }

        function cambiarEstado(id, estado_actual) {
            if (confirm('¿Está seguro de cambiar el estado de este tipo de examen?')) {
                window.location.href = 'cambiar_estado.php?id=' + id + '&estado=' + 
                    (estado_actual === 'activo' ? 'inactivo' : 'activo');
            }
        }
    </script>
</body>
</html> 