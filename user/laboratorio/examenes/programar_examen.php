<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es personal de laboratorio
// if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'laboratorio') {
//     header('Location: ../../login.php');
//     exit();
// }

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID de examen no proporcionado";
    header('Location: gestionar_solicitudes.php');
    exit();
}

$id = intval($_GET['id']);

// Obtener información del examen
$query = "SELECT 
    es.*,
    te.nombre as nombre_examen,
    te.requiere_ayuno,
    te.tiempo_estimado,
    te.instrucciones_previas,
    CONCAT(p.nombres, ' ', p.apellidos) as nombre_paciente,
    p.fecha_nacimiento,
    CONCAT(u.nombres, ' ', u.apellidos) as nombre_doctor
FROM examenes_solicitados es
JOIN tipos_examenes te ON es.tipo_examen_id = te.id
JOIN pacientes p ON es.paciente_id = p.id
JOIN usuarios u ON es.doctor_id = u.id
WHERE es.id = $id";

$resultado = mysqli_query($conexion, $query);

if (!($examen = mysqli_fetch_assoc($resultado))) {
    $_SESSION['error'] = "Examen no encontrado";
    header('Location: gestionar_solicitudes.php');
    exit();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_programada = mysqli_real_escape_string($conexion, $_POST['fecha_programada']);
    $hora_programada = mysqli_real_escape_string($conexion, $_POST['hora_programada']);
    $datetime_programada = $fecha_programada . ' ' . $hora_programada;
    
    $query = "UPDATE examenes_solicitados SET 
        fecha_programada = '$datetime_programada',
        estado = 'programado'
        WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        $_SESSION['mensaje'] = "Examen programado correctamente";
        header('Location: gestionar_solicitudes.php');
        exit();
    } else {
        $_SESSION['error'] = "Error al programar el examen: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Programar Examen</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Programar Examen</h4>
                <a href="gestionar_solicitudes.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
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
                                    echo $edad . " años";
                                    ?>
                                </small>
                            </dd>
                            
                            <dt class="col-sm-4">Doctor</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($examen['nombre_doctor']); ?></dd>
                            
                            <dt class="col-sm-4">Tiempo Estimado</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($examen['tiempo_estimado']); ?></dd>
                            
                            <dt class="col-sm-4">Requiere Ayuno</dt>
                            <dd class="col-sm-8">
                                <span class="badge <?php echo $examen['requiere_ayuno'] ? 'bg-warning' : 'bg-success'; ?>">
                                    <?php echo $examen['requiere_ayuno'] ? 'Sí' : 'No'; ?>
                                </span>
                            </dd>
                            
                            <dt class="col-sm-4">Prioridad</dt>
                            <dd class="col-sm-8">
                                <span class="badge <?php echo $examen['prioridad'] === 'urgente' ? 'bg-danger' : 'bg-info'; ?>">
                                    <?php echo ucfirst($examen['prioridad']); ?>
                                </span>
                            </dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h5>Instrucciones Previas</h5>
                        <div class="alert alert-info">
                            <?php echo nl2br(htmlspecialchars($examen['instrucciones_previas'])); ?>
                        </div>
                        
                        <?php if ($examen['notas_medico']): ?>
                            <h5>Notas del Médico</h5>
                            <div class="alert alert-secondary">
                                <?php echo nl2br(htmlspecialchars($examen['notas_medico'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <form method="POST" class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fecha_programada">Fecha</label>
                            <input type="date" class="form-control" id="fecha_programada" name="fecha_programada" 
                                min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="hora_programada">Hora</label>
                            <input type="time" class="form-control" id="hora_programada" name="hora_programada" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-calendar-check"></i> Programar Examen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>