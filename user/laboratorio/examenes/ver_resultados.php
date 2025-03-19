<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es personal de laboratorio
// if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'laboratorio') {
//     header('Location: ../../login.php');
//     exit();
// }

if (!isset($_GET['id'])) {
    // $_SESSION['error'] = "ID de examen no proporcionado";
    // header('Location: gestionar_solicitudes.php');
    // exit();
}

$id = intval($_GET['id']);

// Obtener información del examen y su resultado más reciente
$query = "SELECT 
    es.*,
    te.nombre as nombre_examen,
    te.id as tipo_examen_id,
    CONCAT(p.nombres, ' ', p.apellidos) as nombre_paciente,
    p.fecha_nacimiento,
    p.genero,
    m.codigo_barras,
    m.tipo_muestra,
    re.resultado,
    re.observaciones,
    re.archivo_adjunto,
    re.estado as estado_resultado,
    re.fecha_resultado,
    CONCAT(u.nombres, ' ', u.apellidos) as nombre_tecnico,
    CONCAT(d.nombres, ' ', d.apellidos) as nombre_doctor
FROM examenes_solicitados es
JOIN tipos_examenes te ON es.tipo_examen_id = te.id
JOIN pacientes p ON es.paciente_id = p.id
JOIN usuarios d ON es.doctor_id = d.id
LEFT JOIN muestras m ON es.id = m.examen_id
LEFT JOIN resultados_examenes re ON es.id = re.examen_id
LEFT JOIN usuarios u ON re.tecnico_id = u.id
WHERE es.id = $id
ORDER BY re.fecha_resultado DESC
LIMIT 1";

$resultado = mysqli_query($conexion, $query);

if (!($examen = mysqli_fetch_assoc($resultado))) {
    // $_SESSION['error'] = "Examen no encontrado";
    // header('Location: gestionar_solicitudes.php');
    // exit();
}

// Obtener valores de referencia
$query = "SELECT * FROM valores_referencia 
WHERE tipo_examen_id = {$examen['tipo_examen_id']}
AND (genero = '{$examen['genero']}' OR genero = 'ambos')";
$resultado_valores = mysqli_query($conexion, $query);

// Obtener historial de resultados
$query = "SELECT 
    re.*,
    CONCAT(u.nombres, ' ', u.apellidos) as nombre_tecnico
FROM resultados_examenes re
JOIN usuarios u ON re.tecnico_id = u.id
WHERE re.examen_id = $id
ORDER BY re.fecha_resultado DESC";
$historial = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de Examen</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Resultados de Examen</h4>
                <div>
                    <?php if ($examen['archivo_adjunto']): ?>
                        <a href="../../uploads/resultados/<?php echo $examen['archivo_adjunto']; ?>" class="btn btn-info" target="_blank">
                            <i class="fas fa-file-download"></i> Ver Adjunto
                        </a>
                    <?php endif; ?>
                    <a href="gestionar_solicitudes.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Detalles del Examen</h5>
                        <dl class="row">
                            <dt class="col-sm-4">Examen</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($examen['nombre_examen']); ?></dd>
                            
                            <dt class="col-sm-4">Paciente</dt>
                            <dd class="col-sm-8">
                                <?php echo htmlspecialchars($examen['nombre_paciente']); ?>
                                <br>
                                <small class="text-muted">
                                    <?php 
                                    $edad = date_diff(date_create($examen['fecha_nacimiento']), date_create('today'))->y;
                                    echo $edad . " años - " . ucfirst($examen['genero']);
                                    ?>
                                </small>
                            </dd>
                            
                            <dt class="col-sm-4">Doctor</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($examen['nombre_doctor']); ?></dd>
                            
                            <dt class="col-sm-4">Muestra</dt>
                            <dd class="col-sm-8">
                                <?php if ($examen['codigo_barras']): ?>
                                    Código: <?php echo htmlspecialchars($examen['codigo_barras']); ?>
                                    <br>
                                    Tipo: <?php echo ucfirst($examen['tipo_muestra']); ?>
                                <?php else: ?>
                                    <span class="text-danger">No registrada</span>
                                <?php endif; ?>
                            </dd>
                            
                            <dt class="col-sm-4">Fecha Solicitud</dt>
                            <dd class="col-sm-8"><?php echo date('d/m/Y H:i', strtotime($examen['fecha_solicitud'])); ?></dd>
                            
                            <?php if ($examen['fecha_resultado']): ?>
                                <dt class="col-sm-4">Fecha Resultado</dt>
                                <dd class="col-sm-8"><?php echo date('d/m/Y H:i', strtotime($examen['fecha_resultado'])); ?></dd>
                                
                                <dt class="col-sm-4">Técnico</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($examen['nombre_tecnico']); ?></dd>
                            <?php endif; ?>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h5>Valores de Referencia</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Parámetro</th>
                                        <th>Valores</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($valor = mysqli_fetch_assoc($resultado_valores)): ?>
                                        <tr>
                                            <td>
                                                <?php echo htmlspecialchars($valor['parametro']); ?>
                                                <?php if ($valor['unidad']): ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($valor['unidad']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($valor['valor_texto']) {
                                                    echo htmlspecialchars($valor['valor_texto']);
                                                } else {
                                                    if ($valor['valor_minimo'] && $valor['valor_maximo']) {
                                                        echo "{$valor['valor_minimo']} - {$valor['valor_maximo']}";
                                                    } elseif ($valor['valor_minimo']) {
                                                        echo ">= {$valor['valor_minimo']}";
                                                    } elseif ($valor['valor_maximo']) {
                                                        echo "<= {$valor['valor_maximo']}";
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php if ($examen['resultado']): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                Resultado 
                                <span class="badge <?php echo $examen['estado_resultado'] === 'final' ? 'bg-success' : 'bg-warning'; ?>">
                                    <?php echo ucfirst($examen['estado_resultado']); ?>
                                </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <?php echo nl2br(htmlspecialchars($examen['resultado'])); ?>
                            </div>
                            
                            <?php if ($examen['observaciones']): ?>
                                <div class="alert alert-info">
                                    <h6>Observaciones:</h6>
                                    <?php echo nl2br(htmlspecialchars($examen['observaciones'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (mysqli_num_rows($historial) > 1): ?>
                    <h5>Historial de Resultados</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Técnico</th>
                                    <th>Resultado</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                mysqli_data_seek($historial, 1); // Saltar el primer resultado que ya se mostró
                                while ($resultado = mysqli_fetch_assoc($historial)): 
                                ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y H:i', strtotime($resultado['fecha_resultado'])); ?></td>
                                        <td>
                                            <span class="badge <?php echo $resultado['estado'] === 'final' ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo ucfirst($resultado['estado']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($resultado['nombre_tecnico']); ?></td>
                                        <td><?php echo nl2br(htmlspecialchars($resultado['resultado'])); ?></td>
                                        <td><?php echo nl2br(htmlspecialchars($resultado['observaciones'])); ?></td>
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
</body>
</html>