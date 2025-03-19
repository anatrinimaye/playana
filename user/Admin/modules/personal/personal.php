<?php

include '../../config/conexion.php';

// Eliminar personal si se recibe la solicitud
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    
    // Eliminar registros dependientes
    $query_eliminar_recetas = "DELETE FROM recetas WHERE medico_id = $id";
    mysqli_query($conexion, $query_eliminar_recetas);
    
    // Ahora eliminar el personal
    $query = "DELETE FROM personal WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        $_SESSION['mensaje'] = "Personal eliminado correctamente";
        header('Location: personal.php');
        exit();
    } else {
        $_SESSION['error'] = "Error al eliminar el personal: " . mysqli_error($conexion);
    }
}

// Búsqueda de personal
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$query = "SELECT p.* 
          FROM personal p 
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
    <title>Gestión de Personal</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2><i class="fas fa-user-md"></i> Gestión de Personal</h2>
        <button class="btn btn-primary" onclick="window.location.href='formupersonal.php'">
            <i class="fas fa-plus"></i> Nuevo Personal
        </button>

        <div class="search-box mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar personal...">
        </div>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Tipo</th>
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
                            <td>
                                <?php if ($personal['foto']): ?>
                                    <img src="../../uploads/<?php echo htmlspecialchars($personal['foto']); ?>" alt="Foto" width="50">
                                <?php else: ?>
                                    Sin foto
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($personal['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($personal['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($personal['tipo']); ?></td>
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
                        <td colspan="10" class="text-center">No hay personal registrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>