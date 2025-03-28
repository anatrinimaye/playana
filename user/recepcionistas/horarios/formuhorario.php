<?php
include '../../config/conexion.php';

$horario_id = isset($_GET['horario_id']) ? (int)$_GET['horario_id'] : 0;

// Obtener los datos del horario
$query = "SELECT h.*, m.nombre as medico_nombre 
          FROM horarios h 
          LEFT JOIN personal m ON h.medico_id = m.id 
          WHERE h.id = $horario_id";
$resultado = mysqli_query($conexion, $query);
$horario = mysqli_fetch_assoc($resultado);
?>

<div class="container">
    <h2>Asignar Cita</h2>
    <form method="POST" action="asigna_citar.php">
        <input type="hidden" name="horario_id" value="<?php echo $horario_id; ?>">
        <div class="mb-3">
            <label for="medico" class="form-label">Médico</label>
            <input type="text" class="form-control" id="medico" value="<?php echo htmlspecialchars($horario['medico_nombre']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="dia_semana" class="form-label">Día</label>
            <input type="text" class="form-control" id="dia_semana" value="<?php echo htmlspecialchars($horario['dia_semana']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="hora_inicio" class="form-label">Hora Inicio</label>
            <input type="text" class="form-control" id="hora_inicio" value="<?php echo htmlspecialchars($horario['hora_inicio']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="hora_fin" class="form-label">Hora Fin</label>
            <input type="text" class="form-control" id="hora_fin" value="<?php echo htmlspecialchars($horario['hora_fin']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="paciente" class="form-label">Paciente</label>
            <select class="form-select" id="paciente" name="paciente_id" required>
                <option value="">Seleccione un paciente</option>
                <?php
                $query_pacientes = "SELECT id, nombre FROM pacientes WHERE estado = 'Activo'";
                $result_pacientes = mysqli_query($conexion, $query_pacientes);
                while ($paciente = mysqli_fetch_assoc($result_pacientes)) {
                    echo "<option value='{$paciente['id']}'>{$paciente['nombre']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Asignar Cita</button>
    </form>
</div>

<div class="modal" id="asignarCitaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignar Cita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="asigna_citar.php">
                <div class="modal-body">
                    <input type="hidden" name="cita_id" value="<?php echo isset($cita['id']) ? $cita['id'] : ''; ?>"> <!-- ID de la cita pendiente -->
                    <select name="horario_id" required>
                        <?php foreach ($horarios as $horario): ?>
                            <option value="<?php echo $horario['id']; ?>">
                                <?php echo $horario['medico_nombre'] . ' - ' . $horario['dia_semana'] . ' - ' . $horario['hora_inicio']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Asignar Cita</button>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Asignar Cita</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('fecha').addEventListener('change', function() {
        const medicoId = document.getElementById('medico').value;
        const fecha = this.value;

        if (medicoId && fecha) {
            fetch(`horarios/obtener_horarios.php?medico_id=${medicoId}&fecha=${fecha}`)
                .then(response => response.json())
                .then(data => {
                    const horarioSelect = document.getElementById('horario');
                    horarioSelect.innerHTML = '<option value="">Seleccione un horario</option>';
                    data.forEach(horario => {
                        horarioSelect.innerHTML += `<option value="${horario.hora_inicio}">${horario.hora_inicio} - ${horario.hora_fin}</option>`;
                    });
                });
        }
    });
</script>
<script src="horarios.js"></script>