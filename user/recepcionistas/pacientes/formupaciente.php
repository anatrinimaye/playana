<?php
include "../../config/conexion.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$paciente = null;

if ($id > 0) {
    $query = "SELECT * FROM pacientes WHERE id = $id";
    $result = mysqli_query($conexion, $query);
    $paciente = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id > 0 ? 'Editar Paciente' : 'Nuevo Paciente'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2><?php echo $id > 0 ? 'Editar Paciente' : 'Nuevo Paciente'; ?></h2>
        <form action="guardar_paciente.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $paciente['id'] ?? ''; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($paciente['nombre'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($paciente['apellidos'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" class="form-control" id="dni" name="dni" value="<?php echo htmlspecialchars($paciente['dni'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($paciente['fecha_nacimiento'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="genero" class="form-label">Género</label>
                <select class="form-select" id="genero" name="genero" required>
                    <option value="M" <?php echo (isset($paciente['genero']) && $paciente['genero'] == 'M') ? 'selected' : ''; ?>>Masculino</option>
                    <option value="F" <?php echo (isset($paciente['genero']) && $paciente['genero'] == 'F') ? 'selected' : ''; ?>>Femenino</option>
                    <option value="Otro" <?php echo (isset($paciente['genero']) && $paciente['genero'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($paciente['telefono'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($paciente['email'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($paciente['direccion'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="Activo" <?php echo (isset($paciente['estado']) && $paciente['estado'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                    <option value="Inactivo" <?php echo (isset($paciente['estado']) && $paciente['estado'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $id > 0 ? 'Actualizar' : 'Guardar'; ?></button>
            <a href="pacientes.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 