<?php
include '../../config/conexion.php';

// Búsqueda de horarios
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$query = "SELECT h.*, m.nombre as medico_nombre 
          FROM horarios h 
          LEFT JOIN personal m ON h.medico_id = m.id 
          WHERE 
          m.nombre LIKE '%$busqueda%' 
          ORDER BY h.dia_semana, h.hora_inicio ASC";

$resultado = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Horarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Gestión de Horarios de Médicos</h2>

        <!-- Barra de búsqueda -->
        <div class="search-box mb-3">
            <form method="GET" action="horarios.php">
                <input type="text" class="form-control" name="busqueda" placeholder="Buscar por médico o día..." value="<?php echo htmlspecialchars($busqueda); ?>">
            </form>
        </div>

        <!-- Tabla de horarios -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Médico</th>
                    <th>Día de la Semana</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($horario = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($horario['id']); ?></td>
                            <td><?php echo htmlspecialchars($horario['medico_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($horario['dia_semana']); ?></td>
                            <td><?php echo htmlspecialchars($horario['hora_inicio']); ?></td>
                            <td><?php echo htmlspecialchars($horario['hora_fin']); ?></td>
                            <td>
                                <!-- Botón para editar horario -->
                                <a href="editar_horario.php?id=<?php echo $horario['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <!-- Botón para asignar cita -->
                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#horariosModal" onclick="cargarFormulario(<?php echo $horario['id']; ?>)">
                                    Asignar Cita
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay horarios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="horariosModal" tabindex="-1" aria-labelledby="horariosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="horariosModalLabel">Asignar Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="formularioCita">
                        <!-- Aquí se cargará dinámicamente el formulario -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cargarFormulario(horarioId) {
            const formularioCita = document.getElementById('formularioCita');
            formularioCita.innerHTML = '<p>Cargando formulario...</p>';

            // Realizar una solicitud para cargar el formulario
            fetch(`formuhorario.php?horario_id=${horarioId}`)
                .then(response => response.text())
                .then(data => {
                    formularioCita.innerHTML = data;
                })
                .catch(error => {
                    formularioCita.innerHTML = '<p>Error al cargar el formulario.</p>';
                    console.error('Error:', error);
                });
        }
    </script>
</body>
</html>

