<?php
include "../../config/conexion.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$cita = null;

if ($id > 0) {
    $query = "SELECT * FROM citas WHERE id = $id";
    $result = mysqli_query($conexion, $query);
    $cita = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id > 0 ? 'Editar Cita' : 'Nueva Cita'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2><?php echo $id > 0 ? 'Editar Cita' : 'Nueva Cita'; ?></h2>
        <form action="guardar_cita.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $cita['id'] ?? ''; ?>">
            <div class="mb-3">
                <label for="paciente_id" class="form-label">Paciente</label>
                <select class="form-select" id="paciente_id" name="paciente_id" required>
                    <option value="">Seleccione un paciente</option>
                    <?php
                    // Obtener pacientes para el select
                    $pacientes_query = "SELECT * FROM pacientes";
                    $pacientes_result = mysqli_query($conexion, $pacientes_query);
                    while ($paciente = mysqli_fetch_assoc($pacientes_result)) {
                        $selected = (isset($cita['paciente_id']) && $cita['paciente_id'] == $paciente['id']) ? 'selected' : '';
                        echo "<option value='{$paciente['id']}' $selected>{$paciente['nombre']} {$paciente['apellidos']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="medico_id" class="form-label">Médico</label>
                <select class="form-select" id="medico_id" name="medico_id" required>
                    <option value="">Seleccione un médico</option>
                    <?php
                    // Obtener médicos para el select
                    $medicos_query = "SELECT * FROM personal WHERE estado = 'Activo'";
                    $medicos_result = mysqli_query($conexion, $medicos_query);
                    while ($medico = mysqli_fetch_assoc($medicos_result)) {
                        $selected = (isset($cita['medico_id']) && $cita['medico_id'] == $medico['id']) ? 'selected' : '';
                        echo "<option value='{$medico['id']}' $selected>{$medico['nombre']} {$medico['apellidos']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_hora" class="form-label">Fecha y Hora</label>
                <input type="datetime-local" class="form-control" id="fecha_hora" name="fecha_hora" value="<?php echo htmlspecialchars($cita['fecha_hora'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="motivo" class="form-label">Motivo</label>
                <textarea class="form-control" id="motivo" name="motivo" required><?php echo htmlspecialchars($cita['motivo'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="Pendiente" <?php echo (isset($cita['estado']) && $cita['estado'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="Confirmada" <?php echo (isset($cita['estado']) && $cita['estado'] == 'Confirmada') ? 'selected' : ''; ?>>Confirmada</option>
                    <option value="Completada" <?php echo (isset($cita['estado']) && $cita['estado'] == 'Completada') ? 'selected' : ''; ?>>Completada</option>
                    <option value="Cancelada" <?php echo (isset($cita['estado']) && $cita['estado'] == 'Cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $id > 0 ? 'Actualizar' : 'Guardar'; ?></button>
            <a href="citas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 