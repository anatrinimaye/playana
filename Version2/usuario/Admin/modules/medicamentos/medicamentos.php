<?php
include '../../config/conexion.php';

// Eliminar medicamento si se recibe la solicitud

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $query = "DELETE FROM medicamentos WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        echo "<div class='alert alert-success'>Medicamento eliminado correctamente</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar el medicamento: " . mysqli_error($conexion) . "</div>";
    }
}

// Búsqueda de medicamentos
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$query = "SELECT * FROM medicamentos WHERE nombre LIKE '%$busqueda%' ORDER BY nombre ASC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Medicamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Lista de Medicamentos</h2>
        <a href="formumedicamento.php" class="btn btn-primary mb-3">Nuevo Medicamento</a>

        <div class="search-box mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar medicamento...">
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($medicamento = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($medicamento['id']); ?></td>
                            <td><?php echo htmlspecialchars($medicamento['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($medicamento['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($medicamento['precio']); ?></td>
                            <td><?php echo htmlspecialchars($medicamento['stock']); ?></td>
                            <td><?php echo htmlspecialchars($medicamento['estado']); ?></td>
                            <td>
                                <a href="formumedicamento.php?id=<?php echo $medicamento['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="?eliminar=<?php echo $medicamento['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este medicamento?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay medicamentos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 