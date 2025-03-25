<?php
include "../../config/conexion.php";

// Consulta para obtener las citas
$query = "SELECT * FROM citas ORDER BY fecha DESC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Citas</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Citas Solicitadas</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Médico</th>
                    <th>Correo</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($cita = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo $cita['id']; ?></td>
                            <td><?php echo htmlspecialchars($cita['servicio']); ?></td>
                            <td><?php echo htmlspecialchars($cita['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($cita['medico']); ?></td>
                            <td><?php echo htmlspecialchars($cita['correo']); ?></td>
                            <td><?php echo htmlspecialchars($cita['motivo']); ?></td>
                            <td><?php echo htmlspecialchars($cita['estado']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay citas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>