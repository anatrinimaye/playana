<?php
session_start();
include '../../config/conexion.php';

// Verificar permisos
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $query = "SELECT id, username, nombre, rol, estado 
             FROM usuarios 
             WHERE id = $id";
             
    $resultado = mysqli_query($conexion, $query);
    
    if ($usuario = mysqli_fetch_assoc($resultado)) {
        header('Content-Type: application/json');
        echo json_encode($usuario);
    } else {
        echo json_encode(['error' => 'Usuario no encontrado']);
    }
}
?> 