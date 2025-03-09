<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);

    if ($id > 0) {
        // Actualizar
        $query = "UPDATE medicamentos SET nombre='$nombre', descripcion='$descripcion', precio=$precio, stock=$stock, estado='$estado' WHERE id=$id";
    } else {
        // Crear
        $query = "INSERT INTO medicamentos (nombre, descripcion, precio, stock, estado) VALUES ('$nombre', '$descripcion', $precio, $stock, '$estado')";
    }

    if (mysqli_query($conexion, $query)) {
        header("Location: medicamentos.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?> 