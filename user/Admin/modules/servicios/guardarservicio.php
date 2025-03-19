<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $duracion = intval($_POST['duracion']);
    $precio = floatval($_POST['precio']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);

    if ($id > 0) {
        // Actualizar
        $query = "UPDATE servicios SET nombre='$nombre', descripcion='$descripcion', duracion=$duracion, precio=$precio, estado='$estado' WHERE id=$id";
    } else {
        // Crear
        $query = "INSERT INTO servicios (nombre, descripcion, duracion, precio, estado) VALUES ('$nombre', '$descripcion', $duracion, $precio, '$estado')";
    }

    if (mysqli_query($conexion, $query)) {
        header("Location: servicios.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>