<?php
include "../../config/conexion.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$receta = null;

if ($id > 0) {
    $query = "SELECT * FROM recetas WHERE id = $id";
    $result = mysqli_query($conexion, $query);
    $receta = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id > 0 ? 'Editar Receta' : 'Nueva Receta'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2><?php echo $id > 0 ? 'Editar Receta' : 'Nueva Receta'; ?></h2>
        <form action="guardarreceta.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $receta['id'] ?? ''; ?>">
            <div class="mb-3">
                <label for="paciente_id" class="form-label">Paciente</label>
                <select class="form-select" id="paciente_id" name="paciente_id" required>
                    <option value="">Seleccione un paciente</option>
                    <?php
                    // Obtener pacientes para el select
                    $pacientes_query = "SELECT * FROM pacientes";
                    $pacientes_result = mysqli_query($conexion, $pacientes_query);
                    while ($paciente = mysqli_fetch_assoc($pacientes_result)) {
                        $selected = (isset($receta['paciente_id']) && $receta['paciente_id'] == $paciente['id']) ? 'selected' : '';
                        echo "<option value='{$paciente['id']}' $selected>{$paciente['nombre']}</option>";
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
                        $selected = (isset($receta['medico_id']) && $receta['medico_id'] == $medico['id']) ? 'selected' : '';
                        echo "<option value='{$medico['id']}' $selected>{$medico['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="historial_id" class="form-label">Historial Clínico</label>
                <select class="form-select" id="historial_id" name="historial_id" required>
                    <option value="">Seleccione un historial clínico</option>
                    <?php
                    // Obtener historiales clínicos para el select
                    $historiales_query = "SELECT * FROM historiales_clinicos";
                    $historiales_result = mysqli_query($conexion, $historiales_query);
                    while ($historial = mysqli_fetch_assoc($historiales_result)) {
                        $selected = (isset($receta['historial_id']) && $receta['historial_id'] == $historial['id']) ? 'selected' : '';
                        echo "<option value='{$historial['id']}' $selected>{$historial['diagnostico']}</option>"; // Cambia esto según lo que quieras mostrar
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="medicamento_id" class="form-label">Medicamento</label>
                <select class="form-select" id="medicamento_id" name="medicamento_id" required>
                    <option value="">Seleccione un medicamento</option>
                    <?php
                    // Obtener medicamentos para el select
                    $medicamentos_query = "SELECT * FROM medicamentos";
                    $medicamentos_result = mysqli_query($conexion, $medicamentos_query);
                    while ($medicamento = mysqli_fetch_assoc($medicamentos_result)) {
                        $selected = (isset($receta['medicamento_id']) && $receta['medicamento_id'] == $medicamento['id']) ? 'selected' : '';
                        echo "<option value='{$medicamento['id']}' $selected>{$medicamento['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="dosis" class="form-label">Dosis</label>
                <input type="text" class="form-control" id="dosis" name="dosis" value="<?php echo htmlspecialchars($receta['dosis'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="frecuencia" class="form-label">Frecuencia</label>
                <input type="text" class="form-control" id="frecuencia" name="frecuencia" value="<?php echo htmlspecialchars($receta['frecuencia'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="duracion" class="form-label">Duración</label>
                <input type="text" class="form-control" id="duracion" name="duracion" value="<?php echo htmlspecialchars($receta['duracion'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha_emision" class="form-label">Fecha de Emisión</label>
                <input type="date" class="form-control" id="fecha_emision" name="fecha_emision" value="<?php echo htmlspecialchars($receta['fecha_emision'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="activa" <?php echo (isset($receta['estado']) && $receta['estado'] == 'activa') ? 'selected' : ''; ?>>Activa</option>
                    <option value="completada" <?php echo (isset($receta['estado']) && $receta['estado'] == 'completada') ? 'selected' : ''; ?>>Completada</option>
                    <option value="cancelada" <?php echo (isset($receta['estado']) && $receta['estado'] == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $id > 0 ? 'Actualizar' : 'Guardar'; ?></button>
            <a href="recetas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 