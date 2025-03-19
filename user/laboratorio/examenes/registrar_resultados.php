<?php
// session_start();
// include '../../config/conexion.php';

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
    te.id as tipo_examen_id,
    te.requiere_ayuno,
    CONCAT(p.nombre, ' ', p.apellidos) as nombre_paciente,
    p.fecha_nacimiento,
    p.genero,
    m.codigo_barras,
    m.tipo_muestra
FROM examenes_solicitados es
JOIN tipos_examenes te ON es.tipo_examen_id = te.id
JOIN pacientes p ON es.paciente_id = p.id
LEFT JOIN muestras m ON es.id = m.examen_id
WHERE es.id = $id";

$resultado = mysqli_query($conexion, $query);

if (!($examen = mysqli_fetch_assoc($resultado))) {
    $_SESSION['error'] = "Examen no encontrado";
    header('Location: gestionar_solicitudes.php');
    exit();
}

// Obtener valores de referencia
$query = "SELECT * FROM valores_referencia 
WHERE tipo_examen_id = {$examen['tipo_examen_id']}
AND (genero = '{$examen['genero']}' OR genero = 'ambos')";
$resultado_valores = mysqli_query($conexion, $query);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado_texto = mysqli_real_escape_string($conexion, $_POST['resultado']);
    $observaciones = mysqli_real_escape_string($conexion, $_POST['observaciones']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
    
    // Manejar archivo adjunto
    $archivo_adjunto = '';
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
        $archivo_adjunto = uniqid() . '.' . $extension;
        $ruta_destino = '../../uploads/resultados/' . $archivo_adjunto;
        
        if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_destino)) {
            $_SESSION['error'] = "Error al subir el archivo";
            $archivo_adjunto = '';
        }
    }
    
    // Iniciar transacción
    mysqli_begin_transaction($conexion);
    
    try {
        // Registrar resultado
        $query = "INSERT INTO resultados_examenes (
            examen_id, tecnico_id, resultado, observaciones,
            archivo_adjunto, estado
        ) VALUES (
            $id, {$_SESSION['usuario_id']}, '$resultado_texto', '$observaciones',
            '$archivo_adjunto', '$estado'
        )";
        mysqli_query($conexion, $query);
        
        // Actualizar estado del examen si el resultado es final
        if ($estado === 'final') {
            $query = "UPDATE examenes_solicitados SET estado = 'completado' WHERE id = $id";
            mysqli_query($conexion, $query);
            
            // Actualizar estado de la muestra
            $query = "UPDATE muestras SET estado = 'procesada' WHERE examen_id = $id";
            mysqli_query($conexion, $query);
        }
        
        mysqli_commit($conexion);
        $_SESSION['mensaje'] = "Resultados registrados correctamente";
        header('Location: gestionar_solicitudes.php');
        exit();
        
    } catch (Exception $e) {
        mysqli_rollback($conexion);
        if ($archivo_adjunto && file_exists($ruta_destino)) {
            unlink($ruta_destino);
        }
        $_SESSION['error'] = "Error al registrar los resultados: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Resultados</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Registrar Resultados</h4>
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
                                    echo $edad . " años - " . ucfirst($examen['genero']);
                                    ?>
                                </small>
                            </dd>
                            
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

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="resultado">Resultado</label>
                        <textarea class="form-control" id="resultado" name="resultado" rows="5" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="archivo">Archivo Adjunto</label>
                        <input type="file" class="form-control" id="archivo" name="archivo">
                        <small class="text-muted">Formatos permitidos: PDF, JPG, PNG</small>
                    </div>

                    <div class="mb-3">
                        <label for="estado">Estado del Resultado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="preliminar">Preliminar</option>
                            <option value="final">Final</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Registrar Resultados
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 