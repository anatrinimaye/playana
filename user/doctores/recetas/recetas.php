<?php
include "../../config/conexion.php";

// Eliminar receta si se recibe la solicitud
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $query = "DELETE FROM recetas WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        echo "<div class='alert alert-success'>Receta eliminada correctamente</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar la receta: " . mysqli_error($conexion) . "</div>";
    }
}

// Búsqueda de recetas
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$query = "SELECT r.*, p.nombre as paciente_nombre, m.nombre as medico_nombre 
          FROM recetas r 
          LEFT JOIN pacientes p ON r.paciente_id = p.id 
          LEFT JOIN personal m ON r.medico_id = m.id 
          WHERE 
          p.nombre LIKE '%$busqueda%' OR 
          m.nombre LIKE '%$busqueda%' 
          ORDER BY r.fecha_emision DESC";

$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Recetas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Lista de Recetas</h2>
        <a href="formureceta.php" class="btn btn-primary mb-3">Nueva Receta</a>

        <div class="search-box mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar receta...">
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Medicamento</th>
                    <th>Dosis</th>
                    <th>Frecuencia</th>
                    <th>Duración</th>
                    <th>F.Emisión</th>
                    <th>Estado</th>
                    <th>H.Clínico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($receta = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($receta['id']); ?></td>
                            <td><?php echo htmlspecialchars($receta['paciente_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($receta['medico_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($receta['medicamento_id']); ?></td> <!-- Cambia esto para mostrar el nombre del medicamento -->
                            <td><?php echo htmlspecialchars($receta['dosis']); ?></td>
                            <td><?php echo htmlspecialchars($receta['frecuencia']); ?></td>
                            <td><?php echo htmlspecialchars($receta['duracion']); ?></td>
                            <td><?php echo htmlspecialchars($receta['fecha_emision']); ?></td>
                            <td><?php echo htmlspecialchars($receta['estado']); ?></td>
                            <td><?php echo htmlspecialchars($receta['historial_id']); ?></td>
                            <td>
                                <a href="formureceta.php?id=<?php echo $receta['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="?eliminar=<?php echo $receta['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta receta?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center">No hay recetas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 