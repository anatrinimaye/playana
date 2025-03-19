<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es personal de laboratorio
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'laboratorio') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

if (!isset($_POST['id']) || !isset($_POST['estado'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit();
}

$id = intval($_POST['id']);
$estado = mysqli_real_escape_string($conexion, $_POST['estado']);

if (!in_array($estado, ['activo', 'inactivo'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Estado inválido']);
    exit();
}

$query = "UPDATE tipos_examenes SET estado = '$estado' WHERE id = $id";

if (mysqli_query($conexion, $query)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
}