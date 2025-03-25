<?php
session_start();
include '../../config/conexion.php';

// Verificar si se proporcionó el ID del paciente
if (isset($_GET['paciente_id'])) {
    $paciente_id = intval($_GET['paciente_id']);
    $query = "SELECT * FROM pacientes WHERE id = $paciente_id";
    $resultado = mysqli_query($conexion, $query);

    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    $paciente = mysqli_fetch_assoc($resultado);

    if (!$paciente) {
        die("No se encontró información del paciente con ID: $paciente_id");
    }
} else {
    die("No se proporcionó un ID de paciente.");
}

// Obtener el ID del doctor desde la sesión
$doctor_id = $_SESSION['usuario_id'] ?? null;

if (!$doctor_id) {
    die("No se encontró información del doctor. Asegúrate de haber iniciado sesión.");
}

// Obtener historial médico
$query_historial = "SELECT * FROM historial_medico WHERE paciente_id = $paciente_id ORDER BY fecha DESC LIMIT 5";
$resultado_historial = mysqli_query($conexion, $query_historial);

// Obtener lista de medicamentos
$query_medicamentos = "SELECT * FROM medicamentos WHERE estado = 'disponible' ORDER BY nombre";
$resultado_medicamentos = mysqli_query($conexion, $query_medicamentos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Consulta Médica</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4>Nueva Consulta Médica</h4>
                    </div>
                    <div class="card-body">
                        <form id="consultaForm" method="POST" action="guardar_consulta.php">
                            <input type="hidden" name="paciente_id" value="<?php echo $paciente['id'] ?? ''; ?>">
                            <input type="hidden" name="doctor_id" value="<?php echo $doctor_id ?? ''; ?>">

                            <?php
                            if (!$paciente || !$doctor_id) {
                                die("No se puede realizar la consulta. Faltan datos del paciente o del doctor.");
                            }
                            ?>

                            <!-- Información del Paciente -->
                            <div class="mb-3">
                                <h5>Información del Paciente</h5>
                                <?php if ($paciente): ?>
                                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']); ?></p>
                                    <p><strong>DNI:</strong> <?php echo htmlspecialchars($paciente['dni']); ?></p>
                                    <p><strong>Edad:</strong> <?php echo htmlspecialchars($paciente['edad']); ?> años</p>
                                <?php endif; ?>
                            </div>

                            <!-- Signos Vitales -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>Signos Vitales</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="presion_arterial">Presión Arterial</label>
                                                <input type="text" class="form-control" name="presion_arterial" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="temperatura">Temperatura (°C)</label>
                                                <input type="number" step="0.1" class="form-control" name="temperatura" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="frecuencia_cardiaca">Frec. Cardíaca</label>
                                                <input type="number" class="form-control" name="frecuencia_cardiaca" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="peso">Peso (kg)</label>
                                                <input type="number" step="0.1" class="form-control" name="peso" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Consulta -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>Detalles de la Consulta</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="motivo">Motivo de la Consulta</label>
                                        <textarea class="form-control" name="motivo" rows="2" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sintomas">Síntomas</label>
                                        <textarea class="form-control" name="sintomas" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="diagnostico">Diagnóstico</label>
                                        <textarea class="form-control" name="diagnostico" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tratamiento">Tratamiento</label>
                                        <textarea class="form-control" name="tratamiento" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Receta -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>Receta Médica</h5>
                                </div>
                                <div class="card-body">
                                    <div id="medicamentos-container">
                                        <div class="medicamento-item mb-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>Medicamento</label>
                                                    <select class="form-control select2" name="medicamentos[]" required>
                                                        <option value="">Seleccionar...</option>
                                                        <?php while ($med = mysqli_fetch_assoc($resultado_medicamentos)): ?>
                                                            <option value="<?php echo $med['id']; ?>">
                                                                <?php echo htmlspecialchars($med['nombre']); ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Dosis</label>
                                                    <input type="text" class="form-control" name="dosis[]" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Frecuencia</label>
                                                    <input type="text" class="form-control" name="frecuencia[]" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Duración</label>
                                                    <input type="text" class="form-control" name="duracion[]" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary" onclick="agregarMedicamento()">
                                        <i class="fas fa-plus"></i> Agregar Medicamento
                                    </button>
                                </div>
                            </div>

                            <!-- Exámenes -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>Exámenes Solicitados</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="examenes">Exámenes</label>
                                        <textarea class="form-control" name="examenes" rows="2"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="notas_examenes">Notas Adicionales</label>
                                        <textarea class="form-control" name="notas_examenes" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Guardar Consulta</button>
                                <a href="consultas.php" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Historial Médico -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Historial Médico Reciente</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($resultado_historial) && mysqli_num_rows($resultado_historial) > 0): ?>
                            <?php while ($historial = mysqli_fetch_assoc($resultado_historial)): ?>
                                <div class="historial-item mb-3">
                                    <h6><?php echo date('d/m/Y', strtotime($historial['fecha'])); ?></h6>
                                    <p><strong>Diagnóstico:</strong> <?php echo htmlspecialchars($historial['diagnostico']); ?></p>
                                    <p><strong>Tratamiento:</strong> <?php echo htmlspecialchars($historial['tratamiento']); ?></p>
                                    <hr>
                                </div>
                            <?php endwhile; ?>
                            <a href="ver_historial.php?paciente_id=<?php echo $paciente_id; ?>" class="btn btn-info btn-sm">
                                Ver Historial Completo
                            </a>
                        <?php else: ?>
                            <p>No hay registros previos</p>
                        <?php endif; ?>
                        <a href="nueva_consulta.php?paciente_id=<?php echo $paciente['id']; ?>" class="btn btn-primary">Nueva Consulta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        function agregarMedicamento() {
            const container = document.getElementById('medicamentos-container');
            const template = document.querySelector('.medicamento-item').cloneNode(true);
            container.appendChild(template);
            $(template).find('.select2').select2();
        }
    </script>
</body>
</html>