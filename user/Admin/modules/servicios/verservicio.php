<?php
include "../../config/conexion.php";

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Consulta para obtener los datos del servicio
    $query = "SELECT * FROM servicios WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);
    
    // Verificar si se encontró el servicio
    if (mysqli_num_rows($resultado) > 0) {
        $servicio = mysqli_fetch_assoc($resultado);
    } else {
        echo "<div class='alert alert-danger'>Servicio no encontrado.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>ID de servicio no especificado.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Detalles del Servicio</h2>
        <table class="table">
            <tr>
                <th>ID</th>
                <td><?php echo htmlspecialchars($servicio['id']); ?></td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td><?php echo htmlspecialchars($servicio['nombre']); ?></td>
            </tr>
            <tr>
                <th>Descripción</th>
                <td><?php echo htmlspecialchars($servicio['descripcion']); ?></td>
            </tr>
            <tr>
                <th>Precio</th>
                <td><?php echo htmlspecialchars($servicio['precio']); ?></td>
            </tr>
        </table>
        <a href="servicios.php" class="btn btn-primary">Volver a la lista</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 