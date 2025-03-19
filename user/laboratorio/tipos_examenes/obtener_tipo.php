<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es personal de laboratorio
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'laboratorio') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID no proporcionado']);
    exit();
}

$id = intval($_GET['id']);

// Obtener información del tipo de examen
$query = "SELECT * FROM tipos_examenes WHERE id = $id";
$resultado = mysqli_query($conexion, $query);

if ($tipo = mysqli_fetch_assoc($resultado)) {
    // Obtener valores de referencia
    $query = "SELECT * FROM valores_referencia WHERE tipo_examen_id = $id";
    $resultado_valores = mysqli_query($conexion, $query);
    
    $valores_referencia = [];
    while ($valor = mysqli_fetch_assoc($resultado_valores)) {
        $valores_referencia[] = $valor;
    }
    
    $tipo['valores_referencia'] = $valores_referencia;
    
    echo json_encode($tipo);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Tipo de examen no encontrado']);
} 