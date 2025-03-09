<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $fecha_bloqueo = mysqli_real_escape_string($conexion, $_POST['fecha_bloqueo']);
    $paciente_id = intval($_POST['paciente_id']);
    $hora_inicio = mysqli_real_escape_string($conexion, $_POST['hora_inicio']);
    $hora_fin = mysqli_real_escape_string($conexion, $_POST['hora_fin']);

    if ($id > 0) {
        // Actualizar
        $query = "UPDATE horarios_bloqueados SET fecha_bloqueo='$fecha_bloqueo', paciente_id=$paciente_id, hora_inicio='$hora_inicio', hora_fin='$hora_fin' WHERE id=$id";
    } else {
        // Crear
        $query = "INSERT INTO horarios_bloqueados (fecha_bloqueo, paciente_id, hora_inicio, hora_fin) VALUES ('$fecha_bloqueo', $paciente_id, '$hora_inicio', '$hora_fin')";
    }

    if (mysqli_query($conexion, $query)) {
        header("Location: horarios_bloqueados.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?> 