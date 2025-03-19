<?php
include "conexion.php";

// Consulta para obtener recetas
$query = "SELECT r.*, p.nombre as paciente_nombre, m.nombre as medico_nombre 
          FROM recetas r 
          LEFT JOIN pacientes p ON r.paciente_id = p.id 
          LEFT JOIN personal m ON r.medico_id = m.id";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Recetas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Reporte de Recetas</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Medicamento</th>
                    <th>Dosis</th>
                    <th>Frecuencia</th>
                    <th>Duración</th>
                    <th>Fecha de Emisión</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($receta = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($receta['id']); ?></td>
                            <td><?php echo htmlspecialchars($receta['paciente_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($receta['medico_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($receta['medicamento_id']); ?></td> <!-- Cambia esto para mostrar el nombre del medicamento -->
                            <td><?php echo htmlspecialchars($receta['dosis']); ?></td>
                            <td><?php echo htmlspecialchars($receta['frecuencia']); ?></td>
                            <td><?php echo htmlspecialchars($receta['duracion']); ?></td>
                            <td><?php echo htmlspecialchars($receta['fecha_emision']); ?></td>
                            <td><?php echo htmlspecialchars($receta['estado']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">No hay recetas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="/pesadilla/user/Admin/modules/reportes/indexreportes.php" class="btn btn-secondary">Volver</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 