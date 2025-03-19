<?php
include "../../config/conexion.php";

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Consulta para obtener los datos de la receta
    $query = "SELECT * FROM recetas WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);
    
    // Verificar si se encontró la receta
    if (mysqli_num_rows($resultado) > 0) {
        $receta = mysqli_fetch_assoc($resultado);
    } else {
        echo "<div class='alert alert-danger'>Receta no encontrada.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>ID de receta no especificado.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de la Receta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Detalles de la Receta</h2>
        <table class="table">
            <tr>
                <th>ID</th>
                <td><?php echo htmlspecialchars($receta['id']); ?></td>
            </tr>
            <tr>
                <th>ID Paciente</th>
                <td><?php echo htmlspecialchars($receta['paciente_id']); ?></td>
            </tr>
            <tr>
                <th>Medicamento</th>
                <td><?php echo htmlspecialchars($receta['medicamento_id']); ?></td>
            </tr>
            <tr>
                <th>Instrucciones</th>
                <td><?php echo htmlspecialchars($receta['frecuencia']); ?></td>
            </tr>
            <tr>
                <th>Fecha</th>
                <td><?php echo htmlspecialchars($receta['fecha_emision']); ?></td>
            </tr>
        </table>
        <a href="recetas.php" class="btn btn-primary">Volver a la lista</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 