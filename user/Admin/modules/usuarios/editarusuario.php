<?php
include "../../config/conexion.php";

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Consulta para obtener los datos del usuario
    $query = "SELECT * FROM usuarios WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);
    
    // Verificar si se encontró el usuario
    if (mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
    } else {
        echo "<div class='alert alert-danger'>Usuario no encontrado.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>ID de usuario no especificado.</div>";
    exit;
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    // Actualizar el usuario en la base de datos
    $query = "UPDATE usuarios SET nombre = '$nombre', email = '$email', rol = '$rol' WHERE id = $id";
    if (mysqli_query($conexion, $query)) {
        echo "<div class='alert alert-success'>Usuario actualizado correctamente.</div>";
        header("Location: usuarios.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar el usuario: " . mysqli_error($conexion) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Editar Usuario</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-select" id="rol" name="rol" required>
                    <option value="admin" <?php echo $usuario['rol'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                    <option value="medico" <?php echo $usuario['rol'] == 'medico' ? 'selected' : ''; ?>>Médico</option>
                    <option value="enfermero" <?php echo $usuario['rol'] == 'enfermero' ? 'selected' : ''; ?>>Enfermero</option>
                    <option value="recepcionista" <?php echo $usuario['rol'] == 'recepcionista' ? 'selected' : ''; ?>>Recepcionista</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
            <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 