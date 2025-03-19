<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conexion, $_POST['apellidos']);
    $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
    $especialidad_id = intval($_POST['especialidad_id']);
    $dni = mysqli_real_escape_string($conexion, $_POST['dni']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);

    if ($id > 0) {
        // Actualizar
        $query = "UPDATE personal SET nombre='$nombre', apellidos='$apellidos', tipo='$tipo', especialidad_id=$especialidad_id, dni='$dni', telefono='$telefono', email='$email', direccion='$direccion', estado='$estado' WHERE id=$id";
    } else {
        // Crear
        $query = "INSERT INTO personal (nombre, apellidos, tipo, especialidad_id, dni, telefono, email, direccion, estado) VALUES ('$nombre', '$apellidos', '$tipo', $especialidad_id, '$dni', '$telefono', '$email', '$direccion', '$estado')";
    }

    if (mysqli_query($conexion, $query)) {
        header("Location: personal.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?> 