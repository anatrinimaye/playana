<?php
session_start();
include '../../config/conexion.php';

// Verificar permisos
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}

// Procesar el formulario de nueva especialidad
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        
        $query = "INSERT INTO especialidades (nombre, descripcion) VALUES ('$nombre', '$descripcion')";
        if (mysqli_query($conexion, $query)) {
            $mensaje = "Especialidad agregada correctamente";
        } else {
            $error = "Error al agregar la especialidad: " . mysqli_error($conexion);
        }
    }
}

// Obtener todas las especialidades
$query = "SELECT * FROM especialidades ORDER BY nombre";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Especialidades</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <h3>Nueva Especialidad</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Especialidad</button>
                </form>
            </div>
            
            <div class="col-md-8">
                <h3>Especialidades Existentes</h3>
                <?php if (isset($mensaje)): ?>
                    <div class="alert alert-success"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($especialidad = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($especialidad['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($especialidad['descripcion']); ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editarEspecialidad(<?php echo $especialidad['id']; ?>)">Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="eliminarEspecialidad(<?php echo $especialidad['id']; ?>)">Eliminar</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function editarEspecialidad(id) {
        // Implementar edición
    }

    function eliminarEspecialidad(id) {
        if (confirm('¿Está seguro de eliminar esta especialidad?')) {
            window.location.href = `eliminar_especialidad.php?id=${id}`;
        }
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 