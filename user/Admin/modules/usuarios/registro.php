<?php
include "../../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $query = "INSERT INTO usuarios (nombre, email, password, rol) VALUES ('$nombre', '$email', '$password', '$rol')";
    
    if (mysqli_query($conexion, $query)) {
        echo "<div class='alert alert-success'>Usuario registrado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al registrar el usuario: " . mysqli_error($conexion) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        <form id="usuarioForm" method="POST" action="procesar_usuario.php">
            <!-- Campo oculto para el ID del usuario -->
            <input type="hidden" id="usuario_id" name="usuario_id">
            
            <!-- Campo para el nombre -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            
            <!-- Campo para el email -->
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <!-- Campo para la contraseña -->
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="text-muted">Dejar en blanco para mantener la contraseña actual</small>
            </div>
            
            <!-- Campo para la fecha de creación (solo lectura) -->
            <div class="mb-3">
                <label for="fecha_creacion" class="form-label">Fecha de Creación</label>
                <input type="text" class="form-control" id="fecha_creacion" name="fecha_creacion" readonly>
            </div>
            
            <!-- Campo para el rol -->
            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-control" id="rol" name="rol" required>
                    <option value="administrador">Administrador</option>
                    <option value="doctor">Doctor</option>
                    <option value="recepcionista">Recepcionista</option>
                    <option value="enfermero">Enfermera</option>
                    <option value="farmaceutico">Farmacéutico</option>
                </select>
            </div>

            <!-- Botones del modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>