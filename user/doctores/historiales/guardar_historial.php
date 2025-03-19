<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $paciente_id = intval($_POST['paciente_id']);
    $medico_id = intval($_POST['medico_id']);
    $fecha_consulta = mysqli_real_escape_string($conexion, $_POST['fecha_consulta']);
    $diagnostico = mysqli_real_escape_string($conexion, $_POST['diagnostico']);
    $tratamiento = mysqli_real_escape_string($conexion, $_POST['tratamiento']);
    $observaciones = mysqli_real_escape_string($conexion, $_POST['observaciones']);

    if ($id > 0) {
        // Actualizar
        $query = "UPDATE historiales_clinicos SET paciente_id=$paciente_id, medico_id=$medico_id, fecha_consulta='$fecha_consulta', diagnostico='$diagnostico', tratamiento='$tratamiento', observaciones='$observaciones' WHERE id=$id";
    } else {
        // Crear
        $query = "INSERT INTO historiales_clinicos (paciente_id, medico_id, fecha_consulta, diagnostico, tratamiento, observaciones) VALUES ($paciente_id, $medico_id, '$fecha_consulta', '$diagnostico', '$tratamiento', '$observaciones')";
    }

    if (mysqli_query($conexion, $query)) {
        header("Location: historiales.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?> 