<?php
// session_start(); // Iniciar sesión

// Verificar si el usuario está logueado
// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: login.php'); // Redirigir a la página de inicio de sesión
//     exit();
// }

// Obtener información de la receta

include "../../config/conexion.php"; // Incluir archivo de conexión a la base de datos

// Verificar si el ID de la receta es válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de receta no válido");
}

$id = intval($_GET['id']);

// Consulta para obtener la información de la receta
$query = "SELECT r.*, m.nombre as medicamento, m.stock,
          p.nombre as paciente, d.nombre as doctor,
          r.fecha_emision as fecha_consulta
          FROM recetas r
          INNER JOIN medicamentos m ON r.medicamento_id = m.id
          INNER JOIN pacientes p ON r.paciente_id = p.id
          INNER JOIN usuarios d ON r.medico_id = d.id
          WHERE r.id = $id";

$resultado = mysqli_query($conexion, $query);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

$receta = mysqli_fetch_assoc($resultado);

if (!$receta) {
    die("Receta no encontrada para el ID: $id");
}

// Verificar el estado de la receta
if ($receta['estado'] === 'completada') {
    $_SESSION['error'] = "Esta receta ya ha sido completada.";
    header('Location: listar_recetas.php');
    exit();
} elseif ($receta['estado'] === 'cancelada') {
    $_SESSION['error'] = "Esta receta ha sido cancelada y no puede ser dispensada.";
    header('Location: listar_recetas.php');
    exit();
}

// Procesar dispensación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../../config/conexion.php';

    // Verificar si se envió el ID de la receta
    if (!isset($_POST['receta_id']) || !is_numeric($_POST['receta_id'])) {
        echo "ID de receta no válido";
        exit();
    }

    $receta_id = intval($_POST['receta_id']);
    $medicamento_id = intval($_POST['medicamento_id']);
    $cantidad = intval($_POST['cantidad']);

    // Depuración: Mostrar los datos recibidos
    echo "ID de receta recibido: $receta_id<br>";
    echo "ID de medicamento recibido: $medicamento_id<br>";
    echo "Cantidad recibida: $cantidad<br>";

    // Aquí puedes agregar la lógica para actualizar el stock del medicamento y cambiar el estado de la receta
    $query = "UPDATE medicamentos SET stock = stock - $cantidad WHERE id = $medicamento_id";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        echo "Stock actualizado correctamente.<br>";
        $query_receta = "UPDATE recetas SET estado = 'dispensada' WHERE id = $receta_id";
        $resultado_receta = mysqli_query($conexion, $query_receta);

        if ($resultado_receta) {
            echo "Receta dispensada correctamente.";
        } else {
            echo "Error al actualizar el estado de la receta: " . mysqli_error($conexion);
        }
    } else {
        echo "Error al actualizar el stock del medicamento: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dispensar Receta</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h4>Dispensar Receta</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php endif; ?>

                        <?php if (isset($receta)): ?>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Información del Paciente</h6>
                                    <p>Nombre: <strong><?php echo htmlspecialchars($receta['paciente']); ?></strong></p>
                                    <p>Doctor: <strong><?php echo htmlspecialchars($receta['doctor']); ?></strong></p>
                                    <p>Fecha Consulta: <strong><?php echo date('d/m/Y', strtotime($receta['fecha_consulta'])); ?></strong></p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Información del Medicamento</h6>
                                    <p>Medicamento: <strong><?php echo htmlspecialchars($receta['medicamento']); ?></strong></p>
                                    <p>Stock Disponible: <strong><?php echo $receta['stock']; ?></strong></p>
                                    <p>Cantidad Recetada: <strong><?php echo $receta['cantidad']; ?></strong></p>
                                </div>
                            </div>

                            <?php if ($receta['stock'] < $receta['cantidad']): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    No hay suficiente stock para dispensar esta receta.
                                </div>
                            <?php else: ?>
                                <form method="POST" action="dispensar_receta.php" class="mt-4">
                                    <input type="hidden" name="receta_id" value="<?php echo $receta['id']; ?>">
                                    <input type="hidden" name="medicamento_id" value="<?php echo $receta['medicamento_id']; ?>">
                                    <input type="hidden" name="cantidad" value="<?php echo $receta['dosis']; ?>">

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        ¿Está seguro de dispensar esta receta? Esta acción reducirá el stock del medicamento.
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-check"></i> Confirmar Dispensación
                                        </button>
                                        <a href="listar_recetas.php" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Volver
                                        </a>
                                    </div>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-danger">No se encontraron detalles de la receta.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>