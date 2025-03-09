<?php
include "../../config/conexion.php";

// Eliminar especialidad si se recibe la solicitud
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $query = "DELETE FROM especialidades WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        echo "<div class='alert alert-success'>Especialidad eliminada correctamente</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar la especialidad: " . mysqli_error($conexion) . "</div>";
    }
}

// Búsqueda de especialidades
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$query = "SELECT * FROM especialidades WHERE 
          nombre LIKE '%$busqueda%' 
          ORDER BY nombre";

$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Especialidades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Lista de Especialidades</h2>
        <a href="formuespecialidad.php" class="btn btn-primary mb-3">Nueva Especialidad</a>

        <div class="search-box mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar especialidad...">
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($especialidad = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($especialidad['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($especialidad['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($especialidad['estado']); ?></td>
                            <td>
                                <a href="formuespecialidad.php?id=<?php echo $especialidad['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="?eliminar=<?php echo $especialidad['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta especialidad?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay especialidades registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 