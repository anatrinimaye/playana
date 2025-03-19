<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es personal de laboratorio
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'laboratorio') {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

if (!isset($_GET['id'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'ID no proporcionado']);
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM tipos_examenes WHERE id = $id";
$resultado = mysqli_query($conexion, $query);

if ($tipo = mysqli_fetch_assoc($resultado)) {
    echo json_encode($tipo);
} else {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'Tipo de examen no encontrado']);
} 