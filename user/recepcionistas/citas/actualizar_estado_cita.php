<?php
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cita_id = mysqli_real_escape_string($conexion, $_POST['id']);
    $nuevo_estado = mysqli_real_escape_string($conexion, $_POST['estado']);

    $query = "UPDATE citas SET estado = '$nuevo_estado' WHERE id = '$cita_id'";
    if (mysqli_query($conexion, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método de solicitud no permitido']);
}
?>