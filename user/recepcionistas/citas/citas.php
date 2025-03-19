<?php
include "../../config/conexion.php";

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $query = "DELETE FROM citas WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        echo "<div class='alert alert-success'>Cita eliminada correctamente</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar la cita: " . mysqli_error($conexion) . "</div>";
    }
}

$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : '';

$query = "SELECT c.id, p.nombre AS paciente_nombre, m.nombre AS medico_nombre, 
                 DATE(c.fecha_hora) AS fecha, TIME(c.fecha_hora) AS hora, c.estado 
          FROM citas c
          LEFT JOIN pacientes p ON c.paciente_id = p.id
          LEFT JOIN personal m ON c.medico_id = m.id
          WHERE (p.nombre LIKE '%$busqueda%' OR m.nombre LIKE '%$busqueda%')";

if ($estado_filtro) {
    $query .= " AND c.estado = '$estado_filtro'";
}

$query .= " ORDER BY c.fecha_hora DESC";

$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Citas</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Listado de Citas</h2>

        <div class="btn-group mb-3" role="group">
            <a href="?estado=pendiente" class="btn btn-primary">Pendientes</a>
            <a href="?estado=confirmada" class="btn btn-success">Confirmadas</a>
            <a href="?estado=cancelada" class="btn btn-danger">Canceladas</a>
            <a href="?estado=completada" class="btn btn-secondary">Completadas</a>
        </div>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['mensaje']); ?>
            </div>
        <?php endif; ?>

        <a href="formucita.php" class="btn btn-primary mb-3">Nueva Cita</a>

        <div class="search-box mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar cita...">
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cita = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo $cita['id']; ?></td>
                        <td><?php echo htmlspecialchars($cita['paciente_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($cita['medico_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($cita['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($cita['hora']); ?></td>
                        <td><?php echo htmlspecialchars($cita['estado']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function cambiarEstadoCita(citaId, nuevoEstado) {
        console.log("Cambiando estado de la cita:", citaId, "a:", nuevoEstado);
        
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "actualizar_estado_cita.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log("Respuesta del servidor:", xhr.responseText);
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert('El estado de la cita ha sido cambiado a: ' + nuevoEstado);
                        location.reload();
                    } else {
                        alert('Error al cambiar el estado: ' + response.error);
                    }
                } else {
                    alert('Error en la solicitud: ' + xhr.status);
                }
            }
        };
        xhr.send("id=" + citaId + "&estado=" + nuevoEstado);
    }
    </script>
</body>
</html>