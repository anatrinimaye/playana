<?php
session_start();
include '../../config/conexion.php';

// Verificar permisos
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['usuario_id'] ?? '';
    $username = mysqli_real_escape_string($conexion, $_POST['username']);
    $password = $_POST['password'] ?? '';
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $rol = mysqli_real_escape_string($conexion, $_POST['rol']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);

    if ($id) {
        // Actualizar usuario existente
        if (!empty($password)) {
            $query = "UPDATE usuarios SET 
                     username = '$username',
                     password = '$password',
                     nombre = '$nombre',
                     rol = '$rol',
                     estado = '$estado'
                     WHERE id = $id";
        } else {
            $query = "UPDATE usuarios SET 
                     username = '$username',
                     nombre = '$nombre',
                     rol = '$rol',
                     estado = '$estado'
                     WHERE id = $id";
        }
    } else {
        // Crear nuevo usuario
        $query = "INSERT INTO usuarios (username, password, nombre, rol, estado) 
                 VALUES ('$username', '$password', '$nombre', '$rol', '$estado')";
    }

    if (mysqli_query($conexion, $query)) {
        header('Location: index.php?mensaje=Usuario guardado exitosamente');
    } else {
        header('Location: index.php?error=Error al guardar el usuario: ' . mysqli_error($conexion));
    }
}
?> 