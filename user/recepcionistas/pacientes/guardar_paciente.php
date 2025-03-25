<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0; // Verifica si se envió un ID
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $genero = $_POST['genero'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $estado = $_POST['estado'];

    if ($id > 0) {
        // Si se envió un ID, actualiza el paciente existente
        $query = "UPDATE pacientes SET nombre = ?, apellidos = ?, dni = ?, fecha_nacimiento = ?, genero = ?, telefono = ?, email = ?, direccion = ?, estado = ? WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sssssssssi", $nombre, $apellidos, $dni, $fecha_nacimiento, $genero, $telefono, $email, $direccion, $estado, $id);

        if ($stmt->execute()) {
            echo "<script>
                alert('Paciente actualizado correctamente.');
                window.location.href = 'indexpacientes.php';
            </script>";
        } else {
            echo "<script>
                alert('Error al actualizar el paciente.');
                window.location.href = 'indexpacientes.php';
            </script>";
        }
    } else {
        // Si no se envió un ID, inserta un nuevo paciente
        $query = "INSERT INTO pacientes (nombre, apellidos, dni, fecha_nacimiento, genero, telefono, email, direccion, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sssssssss", $nombre, $apellidos, $dni, $fecha_nacimiento, $genero, $telefono, $email, $direccion, $estado);

        if ($stmt->execute()) {
            echo "<script>
                alert('Paciente registrado correctamente.');
                window.location.href = 'indexpacientes.php';
            </script>";
        } else {
            echo "<script>
                alert('Error al registrar el paciente.');
                window.location.href = 'indexpacientes.php';
            </script>";
        }
    }

    $stmt->close();
    $conexion->close();
}
?>