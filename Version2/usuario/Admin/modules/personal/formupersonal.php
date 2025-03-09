<?php
include "../../config/conexion.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$personal = null;

if ($id > 0) {
    $query = "SELECT * FROM personal WHERE id = $id";
    $result = mysqli_query($conexion, $query);
    $personal = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id > 0 ? 'Editar Personal' : 'Nuevo Personal'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2><?php echo $id > 0 ? 'Editar Personal' : 'Nuevo Personal'; ?></h2>
        <form action="guardar_personal.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $personal['id'] ?? ''; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($personal['nombre'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($personal['apellidos'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select" id="tipo" name="tipo" required>
                    <option value="Doctor" <?php echo (isset($personal['tipo']) && $personal['tipo'] == 'Doctor') ? 'selected' : ''; ?>>Doctor</option>
                    <option value="Enfermero" <?php echo (isset($personal['tipo']) && $personal['tipo'] == 'Enfermero') ? 'selected' : ''; ?>>Enfermero</option>
                    <option value="Farmaceutico" <?php echo (isset($personal['tipo']) && $personal['tipo'] == 'Farmaceutico') ? 'selected' : ''; ?>>Farmaceutico</option>
                    <option value="Otro" <?php echo (isset($personal['tipo']) && $personal['tipo'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="especialidad_id" class="form-label">Especialidad</label>
                <select class="form-select" id="especialidad_id" name="especialidad_id">
                    <option value="">Seleccione una especialidad</option>
                    <?php
                    // Obtener especialidades para el select
                    $especialidades_query = "SELECT * FROM especialidades";
                    $especialidades_result = mysqli_query($conexion, $especialidades_query);
                    while ($especialidad = mysqli_fetch_assoc($especialidades_result)) {
                        $selected = (isset($personal['especialidad_id']) && $personal['especialidad_id'] == $especialidad['id']) ? 'selected' : '';
                        echo "<option value='{$especialidad['id']}' $selected>{$especialidad['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" class="form-control" id="dni" name="dni" value="<?php echo htmlspecialchars($personal['dni'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($personal['telefono'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($personal['email'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($personal['direccion'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="Activo" <?php echo (isset($personal['estado']) && $personal['estado'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                    <option value="Inactivo" <?php echo (isset($personal['estado']) && $personal['estado'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $id > 0 ? 'Actualizar' : 'Guardar'; ?></button>
            <a href="personal.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 