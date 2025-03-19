<?php
include "../../config/conexion.php";

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Consulta para obtener los datos del medicamento
    $query = "SELECT * FROM medicamentos WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);
    
    // Verificar si se encontró el medicamento
    if (mysqli_num_rows($resultado) > 0) {
        $medicamento = mysqli_fetch_assoc($resultado);
    } else {
        echo "<div class='alert alert-danger'>Medicamento no encontrado.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>ID de medicamento no especificado.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Medicamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Detalles del Medicamento</h2>
        <table class="table">
            <tr>
                <th>ID</th>
                <td><?php echo htmlspecialchars($medicamento['id']); ?></td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td><?php echo htmlspecialchars($medicamento['nombre']); ?></td>
            </tr>
            <tr>
                <th>Descripción</th>
                <td><?php echo htmlspecialchars($medicamento['descripcion']); ?></td>
            </tr>
            <tr>
                <th>Precio</th>
                <td><?php echo htmlspecialchars($medicamento['precio']); ?></td>
            </tr>
            <tr>
                <th>Stock</th>
                <td><?php echo htmlspecialchars($medicamento['stock']); ?></td>
            </tr>
        </table>
        <a href="medicamentos.php" class="btn btn-primary">Volver a la lista</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 