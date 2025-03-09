<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);

    if ($id > 0) {
        // Actualizar
        $query = "UPDATE especialidades SET nombre='$nombre', descripcion='$descripcion', estado='$estado' WHERE id=$id";
    } else {
        // Crear
        $query = "INSERT INTO especialidades (nombre, descripcion, estado) VALUES ('$nombre', '$descripcion', '$estado')";
    }

    if (mysqli_query($conexion, $query)) {
        header("Location: especialidades.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?> 