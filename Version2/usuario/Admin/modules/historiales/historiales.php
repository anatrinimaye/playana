<?php
include "../../config/conexion.php";

// Eliminar historial clínico si se recibe la solicitud
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $query = "DELETE FROM historiales_clinicos WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        echo "<div class='alert alert-success'>Historial clínico eliminado correctamente</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar el historial clínico: " . mysqli_error($conexion) . "</div>";
    }
}

// Búsqueda de historiales clínicos
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$query = "SELECT h.*, p.nombre as paciente_nombre, m.nombre as medico_nombre 
          FROM historiales_clinicos h 
          LEFT JOIN pacientes p ON h.paciente_id = p.id 
          LEFT JOIN personal m ON h.medico_id = m.id 
          WHERE 
          p.nombre LIKE '%$busqueda%' OR 
          m.nombre LIKE '%$busqueda%' 
          ORDER BY h.fecha_consulta DESC";

$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Historiales Clínicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Lista de Historiales Clínicos</h2>
        <a href="formuhistorial.php" class="btn btn-primary mb-3">Nuevo Historial Clínico</a>

        <div class="search-box mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar historial clínico...">
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Fecha de Consulta</th>
                    <th>Diagnóstico</th>
                    <th>Tratamiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($historial = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($historial['id']); ?></td>
                            <td><?php echo htmlspecialchars($historial['paciente_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($historial['medico_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($historial['fecha_consulta']); ?></td>
                            <td><?php echo htmlspecialchars($historial['diagnostico']); ?></td>
                            <td><?php echo htmlspecialchars($historial['tratamiento']); ?></td>
                            <td>
                                <a href="formuhistorial.php?id=<?php echo $historial['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="?eliminar=<?php echo $historial['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este historial clínico?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay historiales clínicos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 