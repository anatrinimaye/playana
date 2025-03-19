<?php
include "../../config/conexion.php";

// Eliminar paciente si se recibe la solicitud
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $query = "DELETE FROM pacientes WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        echo "<div class='alert alert-success'>Paciente eliminado correctamente</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar el paciente: " . mysqli_error($conexion) . "</div>";
    }
}

// Búsqueda de pacientes
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$query = "SELECT * FROM pacientes 
          WHERE 
          nombre LIKE '%$busqueda%' OR 
          apellidos LIKE '%$busqueda%' 
          ORDER BY fecha_registro DESC";

$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Lista de Pacientes</h2>
        <a href="formupaciente.php" class="btn btn-primary mb-3">Nuevo Paciente</a>

        <div class="search-box mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar paciente...">
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Fecha Nacmto</th>
                    <th>Género</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($paciente = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($paciente['id']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['dni']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['fecha_nacimiento']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['genero']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['email']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['estado']); ?></td>
                            <td>
                                <a href="ver_paciente.php?id=<?php echo $paciente['id']; ?>" class="btn btn-info">Ver</a>
                                <a href="formupaciente.php?id=<?php echo $paciente['id']; ?>" class="btn btn-warning">Editar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center">No hay pacientes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 