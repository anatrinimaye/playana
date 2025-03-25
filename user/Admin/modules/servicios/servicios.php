<?php
include "../../config/conexion.php";

// Eliminar servicio si se recibe la solicitud
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $query = "DELETE FROM servicios WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        echo "<div class='alert alert-success'>Servicio eliminado correctamente</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar el servicio: " . mysqli_error($conexion) . "</div>";
    }
}

// Búsqueda de servicios
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$query = "SELECT * FROM servicios WHERE nombre LIKE '%$busqueda%' ORDER BY id DESC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Lista de Servicios</h2>
        <a href="formuservicio.php" class="btn btn-primary mb-3">Nuevo Servicio</a>

        <div class="search-box mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar servicio...">
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Duración (min)</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($servicio = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td>
                                <img src="../../uploads/<?php echo htmlspecialchars($servicio['imagen']); ?>" alt="Imagen del servicio" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td><?php echo htmlspecialchars($servicio['nombre']); ?></td>
                            <td style="max-width: 200px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                <?php echo htmlspecialchars($servicio['descripcion']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($servicio['duracion']); ?></td>
                            <td><?php echo htmlspecialchars($servicio['precio']); ?></td>
                            <td><?php echo htmlspecialchars($servicio['estado']); ?></td>
                            <td>
                                <a href="verservicio.php?id=<?php echo $servicio['id']; ?>" class="btn btn-info btn-sm">Ver</a>
                                <a href="editarservicio.php?id=<?php echo $servicio['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="?eliminar=<?php echo $servicio['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este servicio?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay servicios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
