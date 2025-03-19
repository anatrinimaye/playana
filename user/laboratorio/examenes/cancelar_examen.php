<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es personal de laboratorio
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'laboratorio') {
    header('Location: ../../login.php');
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID de examen no proporcionado";
    header('Location: gestionar_solicitudes.php');
    exit();
}

$id = intval($_GET['id']);

// Verificar que el examen exista y esté en estado válido para cancelar
$query = "SELECT estado FROM examenes_solicitados WHERE id = $id";
$resultado = mysqli_query($conexion, $query);

if ($examen = mysqli_fetch_assoc($resultado)) {
    if (in_array($examen['estado'], ['pendiente', 'programado'])) {
        // Iniciar transacción
        mysqli_begin_transaction($conexion);
        
        try {
            // Actualizar estado del examen
            $query = "UPDATE examenes_solicitados SET estado = 'cancelado' WHERE id = $id";
            mysqli_query($conexion, $query);
            
            // Actualizar estado de la muestra si existe
            $query = "UPDATE muestras SET estado = 'descartada' WHERE examen_id = $id";
            mysqli_query($conexion, $query);
            
            mysqli_commit($conexion);
            $_SESSION['mensaje'] = "Examen cancelado correctamente";
            
        } catch (Exception $e) {
            mysqli_rollback($conexion);
            $_SESSION['error'] = "Error al cancelar el examen: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "El examen no puede ser cancelado en su estado actual";
    }
} else {
    $_SESSION['error'] = "Examen no encontrado";
}

header('Location: gestionar_solicitudes.php'); 