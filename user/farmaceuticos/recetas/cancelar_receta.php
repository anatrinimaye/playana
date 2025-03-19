<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es farmacéutico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'farmaceutico') {
    header('Location: ../../login.php');
    exit();
}

// Verificar si se proporcionó un ID de receta
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID de receta no proporcionado";
    header('Location: listar_recetas.php');
    exit();
}

$id = intval($_GET['id']);

// Verificar si la receta existe y está pendiente
$query = "SELECT * FROM recetas WHERE id = $id";
$resultado = mysqli_query($conexion, $query);
$receta = mysqli_fetch_assoc($resultado);

if (!$receta) {
    $_SESSION['error'] = "Receta no encontrada";
    header('Location: listar_recetas.php');
    exit();
}

if ($receta['estado'] !== 'pendiente') {
    $_SESSION['error'] = "Solo se pueden cancelar recetas pendientes";
    header('Location: listar_recetas.php');
    exit();
}

// Cancelar la receta
$fecha = date('Y-m-d H:i:s');
$query = "UPDATE recetas SET 
    estado = 'cancelada',
    fecha_dispensacion = '$fecha',
    usuario_dispensador_id = {$_SESSION['usuario_id']}
    WHERE id = $id";

if (mysqli_query($conexion, $query)) {
    $_SESSION['mensaje'] = "Receta cancelada correctamente";
} else {
    $_SESSION['error'] = "Error al cancelar la receta: " . mysqli_error($conexion);
}

header('Location: listar_recetas.php');
exit(); 