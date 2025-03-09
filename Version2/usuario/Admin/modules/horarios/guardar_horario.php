<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $medico_id = intval($_POST['medico_id']);
    $dia_semana = intval($_POST['dia_semana']);
    $hora_inicio = mysqli_real_escape_string($conexion, $_POST['hora_inicio']);
    $hora_fin = mysqli_real_escape_string($conexion, $_POST['hora_fin']);

    if ($id > 0) {
        // Actualizar
        $query = "UPDATE horarios SET medico_id=$medico_id, dia_semana=$dia_semana, hora_inicio='$hora_inicio', hora_fin='$hora_fin' WHERE id=$id";
    } else {
        // Crear
        $query = "INSERT INTO horarios (medico_id, dia_semana, hora_inicio, hora_fin) VALUES ($medico_id, $dia_semana, '$hora_inicio', '$hora_fin')";
    }

    if (mysqli_query($conexion, $query)) {
        header("Location: horarios.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?> 