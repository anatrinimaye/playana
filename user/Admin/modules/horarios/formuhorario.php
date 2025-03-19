<?php
include "../../config/conexion.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$horario = null;

if ($id > 0) {
    $query = "SELECT * FROM horarios WHERE id = $id";
    $result = mysqli_query($conexion, $query);
    $horario = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id > 0 ? 'Editar Horario' : 'Nuevo Horario'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2><?php echo $id > 0 ? 'Editar Horario' : 'Nuevo Horario'; ?></h2>
        <form action="guardar_horario.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $horario['id'] ?? ''; ?>">
            <div class="mb-3">
                <label for="medico_id" class="form-label">Médico</label>
                <select class="form-select" id="medico_id" name="medico_id" required>
                    <option value="">Seleccione un médico</option>
                    <?php
                    // Obtener médicos para el select
                    $medicos_query = "SELECT * FROM personal WHERE estado = 'Activo'";
                    $medicos_result = mysqli_query($conexion, $medicos_query);
                    while ($medico = mysqli_fetch_assoc($medicos_result)) {
                        $selected = (isset($horario['medico_id']) && $horario['medico_id'] == $medico['id']) ? 'selected' : '';
                        echo "<option value='{$medico['id']}' $selected>{$medico['nombre']} {$medico['apellidos']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="dia_semana" class="form-label">Día de la Semana</label>
                <select class="form-select" id="dia_semana" name="dia_semana" required>
                    <option value="1" <?php echo (isset($horario['dia_semana']) && $horario['dia_semana'] == '1') ? 'selected' : ''; ?>>Domingo</option>
                    <option value="2" <?php echo (isset($horario['dia_semana']) && $horario['dia_semana'] == '2') ? 'selected' : ''; ?>>Lunes</option>
                    <option value="3" <?php echo (isset($horario['dia_semana']) && $horario['dia_semana'] == '3') ? 'selected' : ''; ?>>Martes</option>
                    <option value="4" <?php echo (isset($horario['dia_semana']) && $horario['dia_semana'] == '4') ? 'selected' : ''; ?>>Miércoles</option>
                    <option value="5" <?php echo (isset($horario['dia_semana']) && $horario['dia_semana'] == '5') ? 'selected' : ''; ?>>Jueves</option>
                    <option value="6" <?php echo (isset($horario['dia_semana']) && $horario['dia_semana'] == '6') ? 'selected' : ''; ?>>Viernes</option>
                    <option value="7" <?php echo (isset($horario['dia_semana']) && $horario['dia_semana'] == '7') ? 'selected' : ''; ?>>Sábado</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="hora_inicio" class="form-label">Hora Inicio</label>
                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" value="<?php echo htmlspecialchars($horario['hora_inicio'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="hora_fin" class="form-label">Hora Fin</label>
                <input type="time" class="form-control" id="hora_fin" name="hora_fin" value="<?php echo htmlspecialchars($horario['hora_fin'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $id > 0 ? 'Actualizar' : 'Guardar'; ?></button>
            <a href="horarios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 