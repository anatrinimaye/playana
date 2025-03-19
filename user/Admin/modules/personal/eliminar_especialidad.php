<?php
session_start();
include '../../config/conexion.php';

// Verificar permisos
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Verificar si la especialidad está siendo utilizada por algún personal
    $query_check = "SELECT COUNT(*) as total FROM personal_especialidades WHERE especialidad_id = $id";
    $result_check = mysqli_query($conexion, $query_check);
    $row = mysqli_fetch_assoc($result_check);
    
    if ($row['total'] > 0) {
        $_SESSION['error'] = "No se puede eliminar la especialidad porque está asignada a personal activo";
    } else {
        // Eliminar la especialidad
        $query = "DELETE FROM especialidades WHERE id = $id";
        if (mysqli_query($conexion, $query)) {
            $_SESSION['mensaje'] = "Especialidad eliminada correctamente";
        } else {
            $_SESSION['error'] = "Error al eliminar la especialidad: " . mysqli_error($conexion);
        }
    }
}

header('Location: especialidades.php');
exit(); 