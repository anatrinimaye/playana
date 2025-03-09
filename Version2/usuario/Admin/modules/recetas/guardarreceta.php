<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $paciente_id = intval($_POST['paciente_id']);
    $medico_id = intval($_POST['medico_id']);
    $medicamento_id = intval($_POST['medicamento_id']);
    $historial_id = intval($_POST['historial_id']);
    $dosis = mysqli_real_escape_string($conexion, $_POST['dosis']);
    $frecuencia = mysqli_real_escape_string($conexion, $_POST['frecuencia']);
    $duracion = mysqli_real_escape_string($conexion, $_POST['duracion']);
    $fecha_emision = mysqli_real_escape_string($conexion, $_POST['fecha_emision']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);

    if ($id > 0) {
        // Actualizar
        $query = "UPDATE recetas SET paciente_id=$paciente_id, medico_id=$medico_id, medicamento_id=$medicamento_id, historial_id=$historial_id, dosis='$dosis', frecuencia='$frecuencia', duracion='$duracion', fecha_emision='$fecha_emision', estado='$estado' WHERE id=$id";
    } else {
        // Crear
        $query = "INSERT INTO recetas (paciente_id, medico_id, medicamento_id, historial_id, dosis, frecuencia, duracion, fecha_emision, estado) VALUES ($paciente_id, $medico_id, $medicamento_id, $historial_id, '$dosis', '$frecuencia', '$duracion', '$fecha_emision', '$estado')";
    }

    if (mysqli_query($conexion, $query)) {
        header("Location: recetas.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?> 