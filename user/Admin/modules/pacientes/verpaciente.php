<?php
include "../../config/conexion.php";

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Consulta para obtener los datos del paciente
    $query = "SELECT * FROM pacientes WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);
    
    // Verificar si se encontró el paciente
    if (mysqli_num_rows($resultado) > 0) {
        $paciente = mysqli_fetch_assoc($resultado);
    } else {
        echo "<div class='alert alert-danger'>Paciente no encontrado.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>ID de paciente no especificado.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Detalles del Paciente</h2>
        <table class="table">
            <tr>
                <th>ID</th>
                <td><?php echo htmlspecialchars($paciente['id']); ?></td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td><?php echo htmlspecialchars($paciente['nombre']); ?></td>
            </tr>
            <tr>
                <th>Apellidos</th>
                <td><?php echo htmlspecialchars($paciente['apellidos']); ?></td>
            </tr>
            <tr>
                <th>DNI</th>
                <td><?php echo htmlspecialchars($paciente['dni']); ?></td>
            </tr>
            <tr>
                <th>Fecha de Nacimiento</th>
                <td><?php echo htmlspecialchars($paciente['fecha_nacimiento']); ?></td>
            </tr>
            <tr>
                <th>Género</th>
                <td><?php echo htmlspecialchars($paciente['genero']); ?></td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td><?php echo htmlspecialchars($paciente['telefono']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($paciente['email']); ?></td>
            </tr>
            <tr>
                <th>Dirección</th>
                <td><?php echo htmlspecialchars($paciente['direccion']); ?></td>
            </tr>
            <tr>
                <th>Estado</th>
                <td><?php echo htmlspecialchars($paciente['estado']); ?></td>
            </tr>
        </table>
        <a href="pacientes.php" class="btn btn-primary">Volver a la lista</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 