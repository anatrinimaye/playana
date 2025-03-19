<?php
include "conexion.php";

// Consulta para obtener citas
$query = "SELECT c.*, p.nombre as paciente_nombre, m.nombre as medico_nombre 
          FROM citas c 
          LEFT JOIN pacientes p ON c.paciente_id = p.id 
          LEFT JOIN personal m ON c.medico_id = m.id";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Reporte de Citas</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Fecha y Hora</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($cita = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cita['id']); ?></td>
                            <td><?php echo htmlspecialchars($cita['paciente_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($cita['medico_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($cita['fecha_hora']); ?></td>
                            <td><?php echo htmlspecialchars($cita['motivo']); ?></td>
                            <td><?php echo htmlspecialchars($cita['estado']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay citas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="/pesadilla/user/Admin/modules/reportes/indexreportes.php" class="btn btn-secondary">Volver</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 