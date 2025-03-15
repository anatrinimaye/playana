<?php
include "../../config/conexion.php";

// Eliminar cita si se recibe la solicitud
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $query = "DELETE FROM citas WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        echo "<div class='alert alert-success'>Cita eliminada correctamente</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar la cita: " . mysqli_error($conexion) . "</div>";
    }
}

// Búsqueda de citas
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$query = "SELECT c.*, p.nombre as paciente_nombre, m.nombre as medico_nombre 
          FROM citas c 
          LEFT JOIN pacientes p ON c.paciente_id = p.id 
          LEFT JOIN personal m ON c.medico_id = m.id 
          WHERE 
          p.nombre LIKE '%$busqueda%' OR 
          m.nombre LIKE '%$busqueda%' 
          ORDER BY c.fecha_hora DESC";

$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Lista de Citas</h2>
        <a href="formucita.php" class="btn btn-primary mb-3">Nueva Cita</a>

        <div class="search-box mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar cita...">
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Fecha y Hora</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($cita = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cita['id']); ?></td>
                            <td><?php echo htmlspecialchars($cita['paciente_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($cita['medico_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($cita['fecha_hora']); ?></td>
                            <td><?php echo htmlspecialchars($cita['motivo']); ?></td>
                            <td><?php echo htmlspecialchars($cita['estado']); ?></td>
                            <td>
                                <a href="formucita.php?id=<?php echo $cita['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="?eliminar=<?php echo $cita['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta cita?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay citas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 