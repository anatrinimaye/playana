<?php
include '../../config/conexion.php';

// Obtener estadísticas generales
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-d', strtotime('-30 days'));
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');

// Total de exámenes por estado
$query = "SELECT estado, COUNT(*) as total FROM examenes_solicitados 
WHERE DATE(fecha_solicitud) BETWEEN '$fecha_inicio' AND '$fecha_fin'
GROUP BY estado";
$resultado_estados = mysqli_query($conexion, $query);
$estados = [];
while ($row = mysqli_fetch_assoc($resultado_estados)) {
    $estados[$row['estado']] = $row['total'];
}

// Exámenes por día
$query = "SELECT DATE(fecha_solicitud) as fecha, COUNT(*) as total 
FROM examenes_solicitados 
WHERE DATE(fecha_solicitud) BETWEEN '$fecha_inicio' AND '$fecha_fin'
GROUP BY DATE(fecha_solicitud)
ORDER BY fecha_solicitud";
$resultado_diario = mysqli_query($conexion, $query);
$examenes_diarios = [];
while ($row = mysqli_fetch_assoc($resultado_diario)) {
    $examenes_diarios[$row['fecha']] = $row['total'];
}

// Top 5 exámenes más solicitados
$query = "SELECT te.nombre, COUNT(*) as total 
FROM examenes_solicitados es
JOIN tipos_examenes te ON es.tipo_examen_id = te.id
WHERE DATE(es.fecha_solicitud) BETWEEN '$fecha_inicio' AND '$fecha_fin'
GROUP BY te.id, te.nombre
ORDER BY total DESC
LIMIT 5";
$resultado_top = mysqli_query($conexion, $query);

// Tiempo promedio de procesamiento
$query = "SELECT AVG(TIMESTAMPDIFF(HOUR, es.fecha_solicitud, re.fecha_resultado)) as promedio
FROM examenes_solicitados es
JOIN resultados_examenes re ON es.id = re.examen_id
WHERE DATE(es.fecha_solicitud) BETWEEN '$fecha_inicio' AND '$fecha_fin'
AND es.estado = 'completado'";
$resultado_tiempo = mysqli_query($conexion, $query);
$tiempo_promedio = mysqli_fetch_assoc($resultado_tiempo)['promedio'];

// Exámenes pendientes urgentes
$query = "SELECT COUNT(*) as total
FROM examenes_solicitados
WHERE estado IN ('pendiente', 'programado')";
$resultado_urgentes = mysqli_query($conexion, $query);
$pendientes_urgentes = mysqli_fetch_assoc($resultado_urgentes)['total'];

// Manejo de errores de MySQL
if (mysqli_error($conexion)) {
    echo "Error en la consulta: " . mysqli_error($conexion);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Laboratorio</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container-fluid mt-5">
        <div class="row">
            <!-- Menú de navegación lateral -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="../tipos_examenes/">Registrar Resultados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../examenes/gestionar_solicitudes.php">Gestionar Solicitudes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../tipos_examenes/gestionar_tipos.php">+ gestionar tipos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../examenes/iniciar_proceso.php">Iniciar Proceso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../examenes/programar_examen.php">Programar Examen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../examenes/ver_resultados.php">Ver Resultados</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Dashboard de Laboratorio</h4>
                    </div>
                    <div class="card-body">
                        <form class="row mb-4">
                            <div class="col-md-4">
                                <label>Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" 
                                    value="<?php echo $fecha_inicio; ?>" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-4">
                                <label>Fecha Fin</label>
                                <input type="date" name="fecha_fin" class="form-control" 
                                    value="<?php echo $fecha_fin; ?>" onchange="this.form.submit()">
                            </div>
                        </form>

                        <div class="row">
                            <!-- Tarjetas de resumen -->
                            <div class="col-md-3 mb-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Total Exámenes</h6>
                                        <h2 class="mb-0"><?php echo array_sum($estados); ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body">
                                        <h6 class="card-title">Pendientes</h6>
                                        <h2 class="mb-0"><?php echo isset($estados['pendiente']) ? $estados['pendiente'] : 0; ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Completados</h6>
                                        <h2 class="mb-0"><?php echo isset($estados['completado']) ? $estados['completado'] : 0; ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Urgentes Pendientes</h6>
                                        <h2 class="mb-0"><?php echo $pendientes_urgentes; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Gráfico de exámenes por día -->
                            <div class="col-md-8 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Exámenes por Día</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="graficoExamenes"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Gráfico de estados -->
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Estados de Exámenes</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="graficoEstados"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Top 5 exámenes -->
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Exámenes Más Solicitados</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Examen</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($top = mysqli_fetch_assoc($resultado_top)): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($top['nombre']); ?></td>
                                                            <td><?php echo $top['total']; ?></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Indicadores de rendimiento -->
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Indicadores de Rendimiento</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            <strong>Tiempo Promedio de Procesamiento:</strong>
                                            <?php 
                                            if ($tiempo_promedio) {
                                                $horas = floor($tiempo_promedio);
                                                $minutos = round(($tiempo_promedio - $horas) * 60);
                                                echo "$horas horas $minutos minutos";
                                            } else {
                                                echo "No hay datos";
                                            }
                                            ?>
                                        </p>
                                        <p>
                                            <strong>Tasa de Completitud:</strong>
                                            <?php 
                                            $total = array_sum($estados);
                                            $completados = isset($estados['completado']) ? $estados['completado'] : 0;
                                            if ($total > 0) {
                                                $tasa = round(($completados / $total) * 100, 2);
                                                echo "$tasa%";
                                            } else {
                                                echo "No hay datos";
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gráfico de exámenes por día
        new Chart(document.getElementById('graficoExamenes'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_keys($examenes_diarios)); ?>,
                datasets: [{
                    label: 'Exámenes',
                    data: <?php echo json_encode(array_values($examenes_diarios)); ?>,
                    borderColor: '#2196f3',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(33, 150, 243, 0.1)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Gráfico de estados
        new Chart(document.getElementById('graficoEstados'), {
            type: 'doughnut',
            data: {
                labels: ['Pendiente', 'Programado', 'En Proceso', 'Completado', 'Cancelado'],
                datasets: [{
                    data: [
                        <?php echo isset($estados['pendiente']) ? $estados['pendiente'] : 0; ?>,
                        <?php echo isset($estados['programado']) ? $estados['programado'] : 0; ?>,
                        <?php echo isset($estados['en_proceso']) ? $estados['en_proceso'] : 0; ?>,
                        <?php echo isset($estados['completado']) ? $estados['completado'] : 0; ?>,
                        <?php echo isset($estados['cancelado']) ? $estados['cancelado'] : 0; ?>
                    ],
                    backgroundColor: [
                        '#ffc107', // Pendiente
                        '#17a2b8', // Programado
                        '#007bff', // En Proceso
                        '#28a745', // Completado
                        '#dc3545'  // Cancelado
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html> 