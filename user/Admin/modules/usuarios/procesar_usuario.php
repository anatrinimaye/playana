<?php
include "../../config/conexion.php";

$id = isset($_POST['usuario_id']) ? intval($_POST['usuario_id']) : 0;
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
$rol = $_POST['rol'];

if ($id > 0) {
    // Actualizar usuario existente
    $query = "UPDATE usuarios SET nombre = '$nombre', email = '$email', rol = '$rol'";
    if ($password) {
        $query .= ", password = '$password'";
    }
    $query .= " WHERE id = $id";
} else {
    // Crear nuevo usuario
    $query = "INSERT INTO usuarios (nombre, email, password, rol, fecha_creacion) 
              VALUES ('$nombre', '$email', '$password', '$rol', NOW())";
}

if (mysqli_query($conexion, $query)) {
    header('Location: index.php?mensaje=Usuario guardado correctamente');
    exit();
} else {
    echo "Error al guardar el usuario: " . mysqli_error($conexion);
}
?>