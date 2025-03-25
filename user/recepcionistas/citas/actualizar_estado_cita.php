<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nuevoEstado = $_POST['estado'];

    // Actualizar el estado de la cita en la base de datos
    $query = "UPDATE citas SET estado = ? WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("si", $nuevoEstado, $id);

    if ($stmt->execute()) {
        // Obtener la información del paciente para enviar el correo
        $queryPaciente = "SELECT p.email, p.nombre, c.fecha_hora FROM citas c
                          LEFT JOIN pacientes p ON c.paciente_id = p.id
                          WHERE c.id = ?";
        $stmtPaciente = $conexion->prepare($queryPaciente);
        $stmtPaciente->bind_param("i", $id);
        $stmtPaciente->execute();
        $resultado = $stmtPaciente->get_result();

        if ($resultado->num_rows > 0) {
            $paciente = $resultado->fetch_assoc();
            $emailPaciente = $paciente['email'];
            $nombrePaciente = $paciente['nombre'];
            $fechaHoraCita = $paciente['fecha_hora'];

            // Enviar el correo electrónico
            $asunto = "Actualización del estado de su cita";
            $mensaje = "Hola $nombrePaciente,\n\n";
            $mensaje .= "Le informamos que el estado de su cita programada para el $fechaHoraCita ha cambiado a: $nuevoEstado.\n\n";
            $mensaje .= "Gracias por confiar en nuestra clínica.\n\n";
            $mensaje .= "Atentamente,\nClínica";

            $headers = "From: placidopabloondo@gmail.com\r\n";
            $headers .= "Reply-To: placidopabloondo@gmail.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            if (mail($emailPaciente, $asunto, $mensaje, $headers)) {
                echo json_encode(['success' => true, 'message' => 'Estado actualizado y correo enviado.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Estado actualizado, pero no se pudo enviar el correo.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'No se encontró información del paciente.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al actualizar el estado de la cita.']);
    }

    $stmt->close();
    $conexion->close();
}
?>
<?php
$to = "destinatario@example.com";
$subject = "Prueba de correo";
$message = "Este es un correo de prueba enviado desde PHP.";
$headers = "From: placidopabloondo@gmail.com";

if (mail($to, $subject, $message, $headers)) {
    echo "Correo enviado correctamente.";
} else {
    echo "Error al enviar el correo.";
}
?>