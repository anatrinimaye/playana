<?php
include "../../config/conexion.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$historial = null;

if ($id > 0) {
    $query = "SELECT * FROM historiales_clinicos WHERE id = $id";
    $result = mysqli_query($conexion, $query);
    $historial = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id > 0 ? 'Editar Historial Clínico' : 'Nuevo Historial Clínico'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2><?php echo $id > 0 ? 'Editar Historial Clínico' : 'Nuevo Historial Clínico'; ?></h2>
        <button class="btn btn-primary" onclick="window.location.href='formulario_historial.php'">
            Registrar Nuevo Historial
        </button>
        <form action="guardar_historial.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $historial['id'] ?? ''; ?>">
            <div class="mb-3">
                <label for="paciente_id" class="form-label">Paciente</label>
                <select class="form-select" id="paciente_id" name="paciente_id" required>
                    <option value="">Seleccione un paciente</option>
                    <?php
                    // Obtener pacientes para el select
                    $pacientes_query = "SELECT * FROM pacientes";
                    $pacientes_result = mysqli_query($conexion, $pacientes_query);
                    while ($paciente = mysqli_fetch_assoc($pacientes_result)) {
                        $selected = (isset($historial['paciente_id']) && $historial['paciente_id'] == $paciente['id']) ? 'selected' : '';
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
                        $selected = (isset($historial['medico_id']) && $historial['medico_id'] == $medico['id']) ? 'selected' : '';
                        echo "<option value='{$medico['id']}' $selected>{$medico['nombre']} {$medico['apellidos']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_consulta" class="form-label">Fecha de Consulta</label>
                <input type="datetime-local" class="form-control" id="fecha_consulta" name="fecha_consulta" value="<?php echo htmlspecialchars($historial['fecha_consulta'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="diagnostico" class="form-label">Diagnóstico</label>
                <textarea class="form-control" id="diagnostico" name="diagnostico" required><?php echo htmlspecialchars($historial['diagnostico'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="tratamiento" class="form-label">Tratamiento</label>
                <textarea class="form-control" id="tratamiento" name="tratamiento" required><?php echo htmlspecialchars($historial['tratamiento'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones"><?php echo htmlspecialchars($historial['observaciones'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $id > 0 ? 'Actualizar' : 'Guardar'; ?></button>
            <a href="historiales.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <!-- Modal para Registrar Nuevo Historial -->
    <div class="modal fade" id="registrarHistorialModal" tabindex="-1" aria-labelledby="registrarHistorialModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registrarHistorialModalLabel">Registrar Nuevo Historial Médico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="formulario_historial.php" method="POST">
                        <div class="mb-3">
                            <label for="paciente_id" class="form-label">Paciente</label>
                            <select class="form-select" id="paciente_id" name="paciente_id" required>
                                <option value="">Seleccione un paciente</option>
                                <?php
                                // Obtener pacientes para el select
                                $pacientes_query = "SELECT * FROM pacientes";
                                $pacientes_result = mysqli_query($conexion, $pacientes_query);
                                while ($paciente = mysqli_fetch_assoc($pacientes_result)) {
                                    echo "<option value='{$paciente['id']}'>{$paciente['nombre']} {$paciente['apellidos']}</option>";
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
                                    echo "<option value='{$medico['id']}'>{$medico['nombre']} {$medico['apellidos']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_consulta" class="form-label">Fecha de Consulta</label>
                            <input type="datetime-local" class="form-control" id="fecha_consulta" name="fecha_consulta" required>
                        </div>
                        <div class="mb-3">
                            <label for="diagnostico" class="form-label">Diagnóstico</label>
                            <textarea class="form-control" id="diagnostico" name="diagnostico" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="tratamiento" class="form-label">Tratamiento</label>
                            <textarea class="form-control" id="tratamiento" name="tratamiento" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 