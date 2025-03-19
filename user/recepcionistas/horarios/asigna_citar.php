<?php
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $horario_id = (int)$_POST['horario_id'];
    $paciente_id = (int)$_POST['paciente_id'];

    // Obtener los datos del horario seleccionado
    $query_horario = "SELECT * FROM horarios WHERE id = $horario_id";
    $resultado_horario = mysqli_query($conexion, $query_horario);
    $horario = mysqli_fetch_assoc($resultado_horario);

    if ($horario) {
        $medico_id = $horario['medico_id'];
        $dia_semana = $horario['dia_semana'];
        $hora_inicio = $horario['hora_inicio'];

        // Insertar la cita en la base de datos con estado "Confirmada"
        $query_cita = "INSERT INTO citas (paciente_id, medico_id, fecha_hora, estado) 
                       VALUES ($paciente_id, $medico_id, NOW(), 'Confirmada')";
        if (mysqli_query($conexion, $query_cita)) {
            // Redirigir a la interfaz de citas con un mensaje de éxito
            header('Location: ../citas/indexcitas.php?mensaje=Cita asignada correctamente');
            exit();
        } else {
            // Mostrar un mensaje de error si la inserción falla
            echo "Error al asignar la cita: " . mysqli_error($conexion);
        }
    } else {
        // Mostrar un mensaje si el horario no se encuentra
        echo "Horario no encontrado.";
    }
}
?>
