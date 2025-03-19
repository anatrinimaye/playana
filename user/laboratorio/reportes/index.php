<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es personal de laboratorio
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'laboratorio') {
    header('Location: ../../login.php');
    exit();
}

// Filtros
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-d', strtotime('-30 days'));
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');
$tipo_reporte = isset($_GET['tipo_reporte']) ? $_GET['tipo_reporte'] : 'examenes';

// Construir consulta según el tipo de reporte
switch ($tipo_reporte) {
    case 'examenes':
        $query = "SELECT 
            es.fecha_solicitud,
            te.nombre as tipo_examen,
            CONCAT(p.nombres, ' ', p.apellidos) as paciente,
            CONCAT(d.nombres, ' ', d.apellidos) as doctor,
            es.estado,
            es.prioridad,
            COALESCE(re.fecha_resultado, 'N/A') as fecha_resultado,
            CONCAT(t.nombres, ' ', t.apellidos) as tecnico
        FROM examenes_solicitados es
        JOIN tipos_examenes te ON es.tipo_examen_id = te.id
        JOIN pacientes p ON es.paciente_id = p.id
        JOIN usuarios d ON es.doctor_id = d.id
        LEFT JOIN resultados_examenes re ON es.id = re.examen_id
        LEFT JOIN usuarios t ON re.tecnico_id = t.id
        WHERE DATE(es.fecha_solicitud) BETWEEN '$fecha_inicio' AND '$fecha_fin'
        ORDER BY es.fecha_solicitud DESC";
        break;

    case 'productividad':
        $query = "SELECT 
            CONCAT(u.nombres, ' ', u.apellidos) as tecnico,
            COUNT(re.id) as total_examenes,
            COUNT(CASE WHEN es.prioridad = 'urgente' THEN 1 END) as urgentes,
            AVG(TIMESTAMPDIFF(HOUR, es.fecha_solicitud, re.fecha_resultado)) as tiempo_promedio
        FROM usuarios u
        JOIN resultados_examenes re ON u.id = re.tecnico_id
        JOIN examenes_solicitados es ON re.examen_id = es.id
        WHERE DATE(es.fecha_solicitud) BETWEEN '$fecha_inicio' AND '$fecha_fin'
        GROUP BY u.id
        ORDER BY total_examenes DESC";
        break;

    case 'muestras':
        $query = "SELECT 
            m.codigo_barras,
            m.tipo_muestra,
            te.nombre as tipo_examen,
            CONCAT(p.nombres, ' ', p.apellidos) as paciente,
            m.estado,
            DATE_FORMAT(m.fecha_recoleccion, '%d/%m/%Y %H:%i') as fecha_recoleccion,
            CONCAT(u.nombres, ' ', u.apellidos) as recolector
        FROM muestras m
        JOIN examenes_solicitados es ON m.examen_id = es.id
        JOIN tipos_examenes te ON es.tipo_examen_id = te.id
        JOIN pacientes p ON es.paciente_id = p.id
        JOIN usuarios u ON m.recolectado_por = u.id
        WHERE DATE(m.fecha_recoleccion) BETWEEN '$fecha_inicio' AND '$fecha_fin'
        ORDER BY m.fecha_recoleccion DESC";
        break;
}

$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Laboratorio</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
</head>
<body>
    <div class="container-fluid mt-5">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Reportes de Laboratorio</h4>
            </div>
            <div class="card-body">
                <form class="row mb-4">
                    <div class="col-md-3">
                        <label>Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" 
                            value="<?php echo $fecha_inicio; ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Fecha Fin</label>
                        <input type="date" name="fecha_fin" class="form-control" 
                            value="<?php echo $fecha_fin; ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Tipo de Reporte</label>
                        <select name="tipo_reporte" class="form-select">
                            <option value="examenes" <?php echo $tipo_reporte === 'examenes' ? 'selected' : ''; ?>>
                                Exámenes Realizados
                            </option>
                            <option value="productividad" <?php echo $tipo_reporte === 'productividad' ? 'selected' : ''; ?>>
                                Productividad por Técnico
                            </option>
                            <option value="muestras" <?php echo $tipo_reporte === 'muestras' ? 'selected' : ''; ?>>
                                Control de Muestras
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block">
                            <i class="fas fa-search"></i> Generar Reporte
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped" id="tablaReporte">
                        <thead>
                            <tr>
                                <?php if ($tipo_reporte === 'examenes'): ?>
                                    <th>Fecha Solicitud</th>
                                    <th>Examen</th>
                                    <th>Paciente</th>
                                    <th>Doctor</th>
                                    <th>Estado</th>
                                    <th>Prioridad</th>
                                    <th>Fecha Resultado</th>
                                    <th>Técnico</th>
                                <?php elseif ($tipo_reporte === 'productividad'): ?>
                                    <th>Técnico</th>
                                    <th>Total Exámenes</th>
                                    <th>Urgentes</th>
                                    <th>Tiempo Promedio (horas)</th>
                                <?php else: ?>
                                    <th>Código</th>
                                    <th>Tipo Muestra</th>
                                    <th>Examen</th>
                                    <th>Paciente</th>
                                    <th>Estado</th>
                                    <th>Fecha Recolección</th>
                                    <th>Recolector</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                                <tr>
                                    <?php if ($tipo_reporte === 'examenes'): ?>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_solicitud'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['tipo_examen']); ?></td>
                                        <td><?php echo htmlspecialchars($row['paciente']); ?></td>
                                        <td><?php echo htmlspecialchars($row['doctor']); ?></td>
                                        <td>
                                            <span class="badge <?php 
                                                switch ($row['estado']) {
                                                    case 'pendiente': echo 'bg-warning'; break;
                                                    case 'programado': echo 'bg-info'; break;
                                                    case 'en_proceso': echo 'bg-primary'; break;
                                                    case 'completado': echo 'bg-success'; break;
                                                    case 'cancelado': echo 'bg-secondary'; break;
                                                }
                                            ?>">
                                                <?php echo ucfirst($row['estado']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $row['prioridad'] === 'urgente' ? 'bg-danger' : 'bg-info'; ?>">
                                                <?php echo ucfirst($row['prioridad']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $row['fecha_resultado']; ?></td>
                                        <td><?php echo htmlspecialchars($row['tecnico']); ?></td>
                                    <?php elseif ($tipo_reporte === 'productividad'): ?>
                                        <td><?php echo htmlspecialchars($row['tecnico']); ?></td>
                                        <td><?php echo $row['total_examenes']; ?></td>
                                        <td><?php echo $row['urgentes']; ?></td>
                                        <td><?php echo round($row['tiempo_promedio'], 2); ?></td>
                                    <?php else: ?>
                                        <td><?php echo htmlspecialchars($row['codigo_barras']); ?></td>
                                        <td><?php echo ucfirst($row['tipo_muestra']); ?></td>
                                        <td><?php echo htmlspecialchars($row['tipo_examen']); ?></td>
                                        <td><?php echo htmlspecialchars($row['paciente']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $row['estado'] === 'procesada' ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo ucfirst($row['estado']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $row['fecha_recoleccion']; ?></td>
                                        <td><?php echo htmlspecialchars($row['recolector']); ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaReporte').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-info'
                    }
                ]
            });
        });
    </script>
</body>
</html> 