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
    CONCAT(p.nombres, ' ', p.apellidos) as nombre_paciente
FROM examenes_solicitados es
JOIN tipos_examenes te ON es.tipo_examen_id = te.id
JOIN pacientes p ON es.paciente_id = p.id
WHERE es.id = $id";

$resultado = mysqli_query($conexion, $query);

if (!($examen = mysqli_fetch_assoc($resultado))) {
    $_SESSION['error'] = "Examen no encontrado";
    header('Location: gestionar_solicitudes.php');
    exit();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_muestra = mysqli_real_escape_string($conexion, $_POST['tipo_muestra']);
    $observaciones = mysqli_real_escape_string($conexion, $_POST['observaciones']);
    
    // Generar código de barras único
    do {
        $codigo_barras = date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $check = mysqli_query($conexion, "SELECT id FROM muestras WHERE codigo_barras = '$codigo_barras'");
    } while (mysqli_num_rows($check) > 0);
    
    // Iniciar transacción
    mysqli_begin_transaction($conexion);
    
    try {
        // Actualizar estado del examen
        $query = "UPDATE examenes_solicitados SET estado = 'en_proceso' WHERE id = $id";
        mysqli_query($conexion, $query);
        
        // Registrar muestra
        $query = "INSERT INTO muestras (
            examen_id, tipo_muestra, codigo_barras, 
            recolectado_por, estado, observaciones
        ) VALUES (
            $id, '$tipo_muestra', '$codigo_barras',
            {$_SESSION['usuario_id']}, 'recolectada', '$observaciones'
        )";
        mysqli_query($conexion, $query);
        
        mysqli_commit($conexion);
        $_SESSION['mensaje'] = "Proceso iniciado y muestra registrada correctamente";
        header('Location: gestionar_solicitudes.php');
        exit();
        
    } catch (Exception $e) {
        mysqli_rollback($conexion);
        $_SESSION['error'] = "Error al iniciar el proceso: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Proceso de Examen</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Iniciar Proceso de Examen</h4>
                <a href="gestionar_solicitudes.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <h5>Detalles del Examen</h5>
                    <p>
                        <strong>Examen:</strong> <?php echo htmlspecialchars($examen['nombre_examen']); ?><br>
                        <strong>Paciente:</strong> <?php echo htmlspecialchars($examen['nombre_paciente']); ?><br>
                        <strong>Prioridad:</strong> 
                        <span class="badge <?php echo $examen['prioridad'] === 'urgente' ? 'bg-danger' : 'bg-info'; ?>">
                            <?php echo ucfirst($examen['prioridad']); ?>
                        </span>
                    </p>
                </div>

                <form method="POST">
                    <div class="mb-3">
                        <label for="tipo_muestra">Tipo de Muestra</label>
                        <select class="form-select" id="tipo_muestra" name="tipo_muestra" required>
                            <option value="">Seleccione...</option>
                            <option value="sangre">Sangre</option>
                            <option value="orina">Orina</option>
                            <option value="heces">Heces</option>
                            <option value="saliva">Saliva</option>
                            <option value="tejido">Tejido</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-vial"></i> Iniciar Proceso y Registrar Muestra
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 