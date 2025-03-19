<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $medico_id = intval($_POST['medico_id']);
    $dia_semana = intval($_POST['dia_semana']);
    $hora_inicio = mysqli_real_escape_string($conexion, $_POST['hora_inicio']);
    $hora_fin = mysqli_real_escape_string($conexion, $_POST['hora_fin']);

    // Validar que las horas estén en el rango correcto y que hora_inicio < hora_fin
    if ($hora_inicio < 0 || $hora_inicio > 24 || $hora_fin < 0 || $hora_fin > 24 || $hora_inicio >= $hora_fin) {
        $_SESSION['error'] = "Las horas deben estar entre 0 y 24 y hora de inicio debe ser menor que hora de fin.";
        header('Location: guardar_horario.php');
        exit();
    }

    if ($id > 0) {
        // Actualizar
        $query = "UPDATE horarios SET medico_id=$medico_id, dia_semana=$dia_semana, hora_inicio='$hora_inicio', hora_fin='$hora_fin' WHERE id=$id";
    } else {
        // Crear
        $query = "INSERT INTO horarios (medico_id, dia_semana, hora_inicio, hora_fin) VALUES ($medico_id, $dia_semana, '$hora_inicio', '$hora_fin')";
    }

    if (mysqli_query($conexion, $query)) {
        $_SESSION['mensaje'] = "Horario guardado correctamente";
        header("Location: horarios.php");
        exit();
    } else {
        $_SESSION['error'] = "Error al guardar el horario: " . mysqli_error($conexion);
    }
}
?> 