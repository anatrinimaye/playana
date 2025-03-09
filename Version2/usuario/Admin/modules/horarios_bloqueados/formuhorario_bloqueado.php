<?php
include "../../config/conexion.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$horario_bloqueado = null;

if ($id > 0) {
    $query = "SELECT * FROM horarios_bloqueados WHERE id = $id";
    $result = mysqli_query($conexion, $query);
    $horario_bloqueado = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id > 0 ? 'Editar Horario Bloqueado' : 'Nuevo Horario Bloqueado'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2><?php echo $id > 0 ? 'Editar Horario Bloqueado' : 'Nuevo Horario Bloqueado'; ?></h2>
        <form action="guardar_horario_bloqueado.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $horario_bloqueado['id'] ?? ''; ?>">
            <div class="mb-3">
                <label for="fecha_bloqueo" class="form-label">Fecha de Bloqueo</label>
                <input type="date" class="form-control" id="fecha_bloqueo" name="fecha_bloqueo" value="<?php echo htmlspecialchars($horario_bloqueado['fecha_bloqueo'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="paciente_id" class="form-label">Paciente</label>
                <select class="form-select" id="paciente_id" name="paciente_id" required>
                    <option value="">Seleccione un paciente</option>
                    <?php
                    // Obtener pacientes para el select
                    $pacientes_query = "SELECT * FROM pacientes";
                    $pacientes_result = mysqli_query($conexion, $pacientes_query);
                    while ($paciente = mysqli_fetch_assoc($pacientes_result)) {
                        $selected = (isset($horario_bloqueado['paciente_id']) && $horario_bloqueado['paciente_id'] == $paciente['id']) ? 'selected' : '';
                        echo "<option value='{$paciente['id']}' $selected>{$paciente['nombre']} {$paciente['apellidos']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="hora_inicio" class="form-label">Hora Inicio</label>
                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" value="<?php echo htmlspecialchars($horario_bloqueado['hora_inicio'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="hora_fin" class="form-label">Hora Fin</label>
                <input type="time" class="form-control" id="hora_fin" name="hora_fin" value="<?php echo htmlspecialchars($horario_bloqueado['hora_fin'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $id > 0 ? 'Actualizar' : 'Guardar'; ?></button>
            <a href="horarios_bloqueados.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 