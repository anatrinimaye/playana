<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conexion, $_POST['apellidos']);
    $dni = mysqli_real_escape_string($conexion, $_POST['dni']);
    $fecha_nacimiento = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']);
    $genero = mysqli_real_escape_string($conexion, $_POST['genero']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);

    if ($id > 0) {
        // Actualizar
        $query = "UPDATE pacientes SET nombre='$nombre', apellidos='$apellidos', dni='$dni', fecha_nacimiento='$fecha_nacimiento', genero='$genero', telefono='$telefono', email='$email', direccion='$direccion', estado='$estado' WHERE id=$id";
    } else {
        // Crear
        $query = "INSERT INTO pacientes (nombre, apellidos, dni, fecha_nacimiento, genero, telefono, email, direccion, estado) VALUES ('$nombre', '$apellidos', '$dni', '$fecha_nacimiento', '$genero', '$telefono', '$email', '$direccion', '$estado')";
    }

    if (mysqli_query($conexion, $query)) {
        header("Location: pacientes.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?> 