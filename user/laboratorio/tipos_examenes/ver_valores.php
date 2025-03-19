<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es personal de laboratorio
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'laboratorio') {
    header('Location: ../../login.php');
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID no proporcionado";
    header('Location: gestionar_tipos.php');
    exit();
}

$id = intval($_GET['id']);

// Obtener información del tipo de examen
$query = "SELECT * FROM tipos_examenes WHERE id = $id";
$resultado = mysqli_query($conexion, $query);

if (!($tipo = mysqli_fetch_assoc($resultado))) {
    $_SESSION['error'] = "Tipo de examen no encontrado";
    header('Location: gestionar_tipos.php');
    exit();
}

// Obtener valores de referencia
$query = "SELECT * FROM valores_referencia WHERE tipo_examen_id = $id";
$resultado_valores = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Valores de Referencia - <?php echo htmlspecialchars($tipo['nombre']); ?></title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Valores de Referencia - <?php echo htmlspecialchars($tipo['nombre']); ?></h4>
                <a href="gestionar_tipos.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5>Detalles del Examen</h5>
                    <dl class="row">
                        <dt class="col-sm-3">Descripción</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($tipo['descripcion']); ?></dd>
                        
                        <dt class="col-sm-3">Precio</dt>
                        <dd class="col-sm-9">$<?php echo number_format($tipo['precio'], 2); ?></dd>
                        
                        <dt class="col-sm-3">Tiempo Estimado</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($tipo['tiempo_estimado']); ?></dd>
                        
                        <dt class="col-sm-3">Requiere Ayuno</dt>
                        <dd class="col-sm-9">
                            <span class="badge <?php echo $tipo['requiere_ayuno'] ? 'bg-warning' : 'bg-success'; ?>">
                                <?php echo $tipo['requiere_ayuno'] ? 'Sí' : 'No'; ?>
                            </span>
                        </dd>
                        
                        <dt class="col-sm-3">Instrucciones Previas</dt>
                        <dd class="col-sm-9"><?php echo nl2br(htmlspecialchars($tipo['instrucciones_previas'])); ?></dd>
                    </dl>
                </div>

                <h5>Valores de Referencia</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Parámetro</th>
                                <th>Unidad</th>
                                <th>Valor Mínimo</th>
                                <th>Valor Máximo</th>
                                <th>Valor Texto</th>
                                <th>Género</th>
                                <th>Rango de Edad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($valor = mysqli_fetch_assoc($resultado_valores)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($valor['parametro']); ?></td>
                                    <td><?php echo htmlspecialchars($valor['unidad']); ?></td>
                                    <td><?php echo htmlspecialchars($valor['valor_minimo']); ?></td>
                                    <td><?php echo htmlspecialchars($valor['valor_maximo']); ?></td>
                                    <td><?php echo htmlspecialchars($valor['valor_texto']); ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo ucfirst($valor['genero']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        if ($valor['edad_minima'] && $valor['edad_maxima']) {
                                            echo "{$valor['edad_minima']} - {$valor['edad_maxima']} años";
                                        } elseif ($valor['edad_minima']) {
                                            echo "Mayor a {$valor['edad_minima']} años";
                                        } elseif ($valor['edad_maxima']) {
                                            echo "Hasta {$valor['edad_maxima']} años";
                                        } else {
                                            echo "Todas las edades";
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
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>