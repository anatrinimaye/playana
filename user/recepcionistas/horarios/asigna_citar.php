<?php
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $horario_id = (int)$_POST['horario_id'];
    $cita_id = (int)$_POST['cita_id']; // ID de la cita pendiente

    // Depuración: Verifica los valores recibidos
    echo "Horario ID: $horario_id<br>";
    echo "Cita ID: $cita_id<br>";

    // Obtener los datos del horario
    $query_horario = "SELECT * FROM horarios WHERE id = $horario_id";
    $resultado_horario = mysqli_query($conexion, $query_horario);
    $horario = mysqli_fetch_assoc($resultado_horario);

    if ($horario) {
        $medico_id = $horario['medico_id'];

        // Actualizar la cita en la base de datos con estado "Confirmada"
        $query_actualizar_cita = "UPDATE citas SET medico_id = $medico_id, estado = 'Confirmada' WHERE id = $cita_id";
        if (mysqli_query($conexion, $query_actualizar_cita)) {
            // Redirigir a la interfaz de citas con un mensaje de éxito
            header('Location: ../citas/indexcitas.php?mensaje=Cita confirmada correctamente');
            exit();
        } else {
            // Mostrar un mensaje de error si la actualización falla
            echo "Error al confirmar la cita: " . mysqli_error($conexion);
        }
    } else {
        // Mostrar un mensaje si el horario no se encuentra
        echo "Horario no encontrado.";
    }
}
?>
