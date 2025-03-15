<?php
include "../../config/conexion.php";

// Eliminar personal si se recibe la solicitud
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $query = "DELETE FROM personal WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        echo "<div class='alert alert-success'>Personal eliminado correctamente</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar el personal: " . mysqli_error($conexion) . "</div>";
    }
}

// Búsqueda de personal
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$query = "SELECT p.*, e.nombre as especialidad_nombre 
          FROM personal p 
          LEFT JOIN especialidades e ON p.especialidad_id = e.id 
          WHERE 
          p.nombre LIKE '%$busqueda%' OR 
          p.apellidos LIKE '%$busqueda%' 
          ORDER BY p.fecha_registro DESC";

$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Personal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Lista de Personal</h2>
        <a href="formupersonal.php" class="btn btn-primary mb-3">Nuevo Personal</a>

        <div class="search-box mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar personal...">
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Tipo</th>
                    <th>Especialidad</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($personal = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($personal['id']); ?></td>
                            <td><?php echo htmlspecialchars($personal['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($personal['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($personal['tipo']); ?></td>
                            <td><?php echo htmlspecialchars($personal['especialidad_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($personal['dni']); ?></td>
                            <td><?php echo htmlspecialchars($personal['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($personal['email']); ?></td>
                            <td><?php echo htmlspecialchars($personal['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($personal['estado']); ?></td>
                            <td>
                                <a href="formupersonal.php?id=<?php echo $personal['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="?eliminar=<?php echo $personal['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este personal?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center">No hay personal registrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>