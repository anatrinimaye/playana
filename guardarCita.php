<?php
include "./user/config/conexion.php"; // Asegúrate de que la conexión esté configurada correctamente

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servicio = $_POST['servicio'];
    $fecha_hora = $_POST['fecha']; // Capturar la fecha y hora desde el formulario
    $medico_id = $_POST['medico_id']; // Capturar el ID del médico seleccionado
    $email = $_POST['correo']; // Capturar el correo del cliente
    $motivo = $_POST['motivo'];

    // Buscar el paciente en la tabla `pacientes` usando el email
    $queryPaciente = "SELECT id FROM pacientes WHERE email = ?";
    $stmtPaciente = $conexion->prepare($queryPaciente);
    $stmtPaciente->bind_param("s", $email);
    $stmtPaciente->execute();
    $resultadoPaciente = $stmtPaciente->get_result();

    if ($resultadoPaciente->num_rows > 0) {
        // Si el paciente existe, obtener su ID
        $paciente = $resultadoPaciente->fetch_assoc();
        $paciente_id = $paciente['id'];

        // Insertar la cita en la base de datos
        $query = "INSERT INTO citas (servicio, fecha_hora, medico_id, paciente_id, motivo, estado) VALUES (?, ?, ?, ?, ?, 'Pendiente')";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ssiss", $servicio, $fecha_hora, $medico_id, $paciente_id, $motivo);

        if ($stmt->execute()) {
            echo "<script>
                alert('Cita solicitada con éxito. Nos pondremos en contacto con usted.');
                window.location.href = './solicitarCita.php';
            </script>";
        } else {
            echo "<script>
                alert('Error al solicitar la cita. Por favor, inténtelo de nuevo.');
                window.location.href = './solicitarCita.php';
            </script>";
        }

        $stmt->close();
    } else {
        // Si el paciente no existe, redirigir al formulario de registro
        echo "<script>
            alert('No estás registrado como paciente. Por favor,acerquese a nuestras instalaciones para regístrate primero para poder solicitar una cita.');
            window.location.href = './solicitarCita.php';
        </script>";
    }

    $stmtPaciente->close();
    $conexion->close();
}
?>