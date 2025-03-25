<?php
include "../../config/conexion.php";

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Consulta para obtener los datos del servicio
    $query = "SELECT * FROM servicios WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);
    
    // Verificar si se encontró el servicio
    if (mysqli_num_rows($resultado) > 0) {
        $servicio = mysqli_fetch_assoc($resultado);
    } else {
        echo "<div class='alert alert-danger'>Servicio no encontrado.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>ID de servicio no especificado.</div>";
    exit;
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $estado = $_POST['estado'];
    $imagen = $servicio['imagen']; // Imagen actual por defecto

    // Manejar la subida de la nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = $_FILES['imagen']['name'];
        $rutaTemporal = $_FILES['imagen']['tmp_name'];
        $directorioDestino = "../../uploads/";

        // Crear el directorio si no existe
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0755, true);
        }

        // Mover el archivo al directorio de destino
        $rutaDestino = $directorioDestino . $nombreArchivo;
        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            $imagen = $nombreArchivo; // Actualizar la imagen si se subió correctamente
        } else {
            echo "<div class='alert alert-danger'>Error al subir la imagen.</div>";
            exit;
        }
    }

    // Actualizar el servicio en la base de datos
    $query = "UPDATE servicios SET nombre = '$nombre', descripcion = '$descripcion', precio = '$precio', estado = '$estado', imagen = '$imagen' WHERE id = $id";
    if (mysqli_query($conexion, $query)) {
        echo "<div class='alert alert-success'>Servicio actualizado correctamente.</div>";
        header("Location: servicios.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar el servicio: " . mysqli_error($conexion) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Editar Servicio</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($servicio['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo htmlspecialchars($servicio['descripcion']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" value="<?php echo htmlspecialchars($servicio['precio']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="Activo" <?php echo ($servicio['estado'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                    <option value="Inactivo" <?php echo ($servicio['estado'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
                <?php if (!empty($servicio['imagen'])): ?>
                    <p>Imagen actual:</p>
                    <img src="../../uploads/<?php echo htmlspecialchars($servicio['imagen']); ?>" alt="Imagen del servicio" style="width: 100px; height: auto;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Servicio</button>
            <a href="servicios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>