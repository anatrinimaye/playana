<?php
session_start();
include '../../config/conexion.php';

// Verificar permisos
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header('Location: ../../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Evitar que el usuario elimine su propia cuenta
    if ($id == $_SESSION['usuario_id']) {
        header('Location: index.php?error=No puede eliminar su propio usuario');
        exit();
    }
    
    $query = "DELETE FROM usuarios WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        header('Location: index.php?mensaje=Usuario eliminado exitosamente');
    } else {
        header('Location: index.php?error=Error al eliminar el usuario');
    }
}
?> 