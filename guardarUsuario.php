<?php
include "./user/config/conexion.php"; // Asegúrate de que la conexión esté configurada correctamente

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encriptar la contraseña
    $rol = 'usuario'; // Asignar un rol predeterminado (puedes cambiarlo según tu lógica)

    // Insertar el usuario en la base de datos
    $query = "INSERT INTO usuarios (nombre, email, password, fecha_creacion, rol) VALUES (?, ?, ?, NOW(), ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssss", $nombre, $email, $password, $rol);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registro exitoso. Ahora puedes solicitar una cita.');
            window.location.href = './solicitarCita.php';
        </script>";
    } else {
        echo "<script>
            alert('Error al registrar el usuario. Por favor, inténtelo de nuevo.');
            window.location.href = './registroUsuario.php';
        </script>";
    }

    $stmt->close();
    $conexion->close();
}
?>