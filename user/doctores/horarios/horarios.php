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

// Iniciar la salida HTML
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Horarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Lista de Horarios</h2>
       <!-- <a href="formuhorario.php" class="btn btn-primary mb-3">Nuevo Horario</a>-->

        <div class="search-box mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar horario...">
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Médico</th>
                    <th>Día de la Semana</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <!-- <th>Acciones</th> -->
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
                                <!-- <a href="formuhorario.php?id=<?php// echo $horario['id']; ?>" class="btn btn-warning btn-sm">Editar</a> -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 