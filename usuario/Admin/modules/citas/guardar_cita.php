<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $paciente_id = intval($_POST['paciente_id']);
    $medico_id = intval($_POST['medico_id']);
    $fecha_hora = mysqli_real_escape_string($conexion, $_POST['fecha_hora']);
    $motivo = mysqli_real_escape_string($conexion, $_POST['motivo']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);

    if ($id > 0) {
        // Actualizar
        $query = "UPDATE citas SET paciente_id=$paciente_id, medico_id=$medico_id, fecha_hora='$fecha_hora', motivo='$motivo', estado='$estado' WHERE id=$id";
    } else {
        // Crear
        $query = "INSERT INTO citas (paciente_id, medico_id, fecha_hora, motivo, estado) VALUES ($paciente_id, $medico_id, '$fecha_hora', '$motivo', '$estado')";
    }

    if (mysqli_query($conexion, $query)) {
        header("Location: citas.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?> 