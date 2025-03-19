<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es personal de laboratorio
// if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'laboratorio') {
//     header('Location: ../../login.php');
//     exit();
// }

// Filtros
$estado = isset($_GET['estado']) ? $_GET['estado'] : 'pendiente';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-d');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');

// Construir consulta
$query = "SELECT 
    es.*, 
    te.nombre as nombre_examen,
    te.requiere_ayuno,
    CONCAT(p.nombre, ' ', p.apellidos) as nombre_paciente,
    p.fecha_nacimiento,
    u.nombre as nombre_doctor
FROM examenes_solicitados es
JOIN tipos_examenes te ON es.tipo_examen_id = te.id
JOIN consultas c ON es.consulta_id = c.id
JOIN pacientes p ON c.paciente_id = p.id
JOIN usuarios u ON c.doctor_id = u.id
WHERE DATE(es.fecha_solicitud) BETWEEN '$fecha_inicio' AND '$fecha_fin'";

if ($estado !== 'todos') {
    $query .= " AND es.estado = '$estado'";
}

$query .= " ORDER BY es.fecha_solicitud DESC";
$resultado = mysqli_query($conexion, $query);

if (!$resultado) {
    $_SESSION['error'] = "Error al obtener solicitudes: " . mysqli_error($conexion);
    header('Location: gestionar_solicitudes.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Solicitudes de Exámenes</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div class="container-fluid mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Solicitudes de Exámenes</h4>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <form class="row mb-4">
                    <div class="col-md-3">
                        <label>Estado</label>
                        <select name="estado" class="form-select" onchange="this.form.submit()">
                            <option value="todos" <?php echo $estado === 'todos' ? 'selected' : ''; ?>>Todos</option>
                            <option value="pendiente" <?php echo $estado === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="programado" <?php echo $estado === 'programado' ? 'selected' : ''; ?>>Programado</option>
                            <option value="en_proceso" <?php echo $estado === 'en_proceso' ? 'selected' : ''; ?>>En Proceso</option>
                            <option value="completado" <?php echo $estado === 'completado' ? 'selected' : ''; ?>>Completado</option>
                            <option value="cancelado" <?php echo $estado === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio; ?>" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3">
                        <label>Fecha Fin</label>
                        <input type="date" name="fecha_fin" class="form-control" value="<?php echo $fecha_fin; ?>" onchange="this.form.submit()">
                    </div>
                </form>

                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <?php if (mysqli_num_rows($resultado) == 0): ?>
                    <div class="alert alert-warning">No hay exámenes registrados.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped" id="tablaSolicitudes">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Paciente</th>
                                    <th>Examen</th>
                                    <th>Doctor</th>
                                    <th>Estado</th>
                                    <th>Prioridad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($solicitud = mysqli_fetch_assoc($resultado)): ?>
                                    <tr>
                                        <td>
                                            <?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])); ?>
                                            <?php if ($solicitud['fecha_programada']): ?>
                                                <br>
                                                <small class="text-muted">
                                                    Programado: <?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_programada'])); ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($solicitud['nombre_paciente']); ?>
                                            <br>
                                            <small class="text-muted">
                                                <?php 
                                                $edad = date_diff(date_create($solicitud['fecha_nacimiento']), date_create('today'))->y;
                                                echo $edad . " años";
                                                ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($solicitud['nombre_examen']); ?>
                                            <?php if ($solicitud['requiere_ayuno']): ?>
                                                <br>
                                                <span class="badge bg-warning">Requiere Ayuno</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($solicitud['nombre_doctor']); ?></td>
                                        <td>
                                            <span class="badge <?php 
                                                switch ($solicitud['estado']) {
                                                    case 'pendiente': echo 'bg-warning'; break;
                                                    case 'programado': echo 'bg-info'; break;
                                                    case 'en_proceso': echo 'bg-primary'; break;
                                                    case 'completado': echo 'bg-success'; break;
                                                    case 'cancelado': echo 'bg-secondary'; break;
                                                }
                                            ?>">
                                                <?php echo ucfirst($solicitud['estado']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $solicitud['prioridad'] === 'urgente' ? 'bg-danger' : 'bg-info'; ?>">
                                                <?php echo ucfirst($solicitud['prioridad']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($solicitud['estado'] === 'pendiente'): ?>
                                                <button class="btn btn-primary btn-sm" onclick="programarExamen(<?php echo $solicitud['id']; ?>)">
                                                    <i class="fas fa-calendar"></i> Programar
                                                </button>
                                            <?php endif; ?>
                                            
                                            <?php if (in_array($solicitud['estado'], ['pendiente', 'programado'])): ?>
                                                <button class="btn btn-success btn-sm" onclick="iniciarProceso(<?php echo $solicitud['id']; ?>)">
                                                    <i class="fas fa-play"></i> Iniciar
                                                </button>
                                            <?php endif; ?>
                                            
                                            <?php if ($solicitud['estado'] === 'en_proceso'): ?>
                                                <button class="btn btn-info btn-sm" onclick="registrarResultados(<?php echo $solicitud['id']; ?>)">
                                                    <i class="fas fa-clipboard-check"></i> Resultados
                                                </button>
                                            <?php endif; ?>
                                            
                                            <?php if ($solicitud['estado'] === 'completado'): ?>
                                                <button class="btn btn-secondary btn-sm" onclick="verResultados(<?php echo $solicitud['id']; ?>)">
                                                    <i class="fas fa-eye"></i> Ver
                                                </button>
                                            <?php endif; ?>
                                            
                                            <?php if (in_array($solicitud['estado'], ['pendiente', 'programado'])): ?>
                                                <button class="btn btn-danger btn-sm" onclick="cancelarExamen(<?php echo $solicitud['id']; ?>)">
                                                    <i class="fas fa-times"></i> Cancelar
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaSolicitudes').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                },
                order: [[0, 'desc']]
            });
        });

        function programarExamen(id) {
            window.location.href = 'programar_examen.php?id=' + id;
        }

        function iniciarProceso(id) {
            if (confirm('¿Está seguro de iniciar el proceso de este examen?')) {
                window.location.href = 'iniciar_proceso.php?id=' + id;
            }
        }

        function registrarResultados(id) {
            window.location.href = 'registrar_resultados.php?id=' + id;
        }

        function verResultados(id) {
            window.location.href = 'ver_resultados.php?id=' + id;
        }

        function cancelarExamen(id) {
            if (confirm('¿Está seguro de cancelar este examen?')) {
                window.location.href = 'cancelar_examen.php?id=' + id;
            }
        }
    </script>
</body>
</html> 