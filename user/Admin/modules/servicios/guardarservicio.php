<?php
include "../../config/conexion.php";

$id = intval($_POST['id']);
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$duracion = $_POST['duracion'];
$precio = $_POST['precio'];
$estado = $_POST['estado'];
$imagen = null;

// Manejar la subida de la imagen
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreArchivo = uniqid('img_', true) . '.' . pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $rutaTemporal = $_FILES['imagen']['tmp_name'];
    $directorioDestino = "../../uploads/";

    // Validar el tipo de archivo
    $tipoArchivo = mime_content_type($rutaTemporal);
    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($tipoArchivo, $tiposPermitidos)) {
        echo "<div class='alert alert-danger'>El archivo debe ser una imagen (JPEG, PNG o GIF).</div>";
        exit;
    }

    // Crear el directorio si no existe
    if (!is_dir($directorioDestino)) {
        mkdir($directorioDestino, 0755, true);
    }

    // Mover el archivo al directorio de destino
    $rutaDestino = $directorioDestino . $nombreArchivo;
    if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
        $imagen = $nombreArchivo;

        // Eliminar la imagen antigua si se está actualizando
        if ($id > 0) {
            $queryImagen = "SELECT imagen FROM servicios WHERE id = $id";
            $resultadoImagen = mysqli_query($conexion, $queryImagen);
            if ($resultadoImagen && mysqli_num_rows($resultadoImagen) > 0) {
                $servicioActual = mysqli_fetch_assoc($resultadoImagen);
                $imagenActual = $servicioActual['imagen'];
                if ($imagenActual && file_exists("../../uploads/" . $imagenActual)) {
                    unlink("../../uploads/" . $imagenActual);
                }
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Error al subir la imagen.</div>";
        exit;
    }
}

// Insertar o actualizar el servicio
if ($id > 0) {
    // Actualizar servicio
    $query = "UPDATE servicios SET nombre = '$nombre', descripcion = '$descripcion', duracion = '$duracion', precio = '$precio', estado = '$estado'";
    if ($imagen) {
        $query .= ", imagen = '$imagen'";
    }
    $query .= " WHERE id = $id";
} else {
    // Insertar nuevo servicio
    $query = "INSERT INTO servicios (nombre, descripcion, duracion, precio, estado, imagen) VALUES ('$nombre', '$descripcion', '$duracion', '$precio', '$estado', '$imagen')";
}

if (mysqli_query($conexion, $query)) {
    header("Location: servicios.php");
    exit;
} else {
    echo "<div class='alert alert-danger'>Error al guardar el servicio: " . mysqli_error($conexion) . "</div>";
}
?>