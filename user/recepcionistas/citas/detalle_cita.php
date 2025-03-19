<?php
include '../../config/conexion.php';

// Validar ID de la cita
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit('ID de cita inválido');
}

$cita_id = mysqli_real_escape_string($conexion, $_GET['id']);

// Consultar detalles de la cita
$query = "SELECT 
            c.fecha_hora,
            c.estado,
            p.dni,
            p.nombre as paciente_nombres,
            p.apellidos as paciente_apellidos,
            p.telefono,
            c.motivo
          FROM citas c
          JOIN pacientes p ON c.paciente_id = p.id
          WHERE c.id = '$cita_id'";

$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Cita</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<?php
if ($cita = mysqli_fetch_assoc($resultado)) {
    // Aquí va el código para mostrar los detalles básicos de la cita
    ?>
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-12">
                <h6>Información del Paciente</h6>
                <p class="mb-1"><strong>Nombre:</strong> <?php echo htmlspecialchars($cita['paciente_nombres'] . ' ' . $cita['paciente_apellidos']); ?></p>
                <p class="mb-1"><strong>DNI:</strong> <?php echo htmlspecialchars($cita['dni']); ?></p>
                <p class="mb-1"><strong>Teléfono:</strong> <?php echo htmlspecialchars($cita['telefono']); ?></p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <h6>Información de la Cita</h6>
                <p class="mb-1"><strong>Fecha y Hora:</strong> <?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora'])); ?></p>
                <p class="mb-1"><strong>Estado:</strong> 
                    <span class="badge <?php echo $cita['estado'] === 'confirmada' ? 'bg-success' : ($cita['estado'] === 'cancelada' ? 'bg-danger' : 'bg-warning'); ?>">
                        <?php echo ucfirst($cita['estado']); ?>
                    </span>
                </p>
                <p class="mb-1"><strong>Motivo:</strong> <?php echo nl2br(htmlspecialchars($cita['motivo'])); ?></p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12 text-center">
                <h6>Acciones</h6>
                <div class="btn-group" role="group" aria-label="Acciones">
                    <?php if ($cita['estado'] !== 'cancelada' && $cita['estado'] !== 'confirmada'): ?>
                        <button type="button" class="btn btn-success" onclick="cambiarEstadoCita(<?php echo $cita_id; ?>, 'confirmada')">Confirmar Cita</button>
                        <button type="button" class="btn btn-danger" onclick="cambiarEstadoCita(<?php echo $cita_id; ?>, 'cancelada')">Cancelar Cita</button>
                        <button type="button" class="btn btn-warning" onclick="cambiarEstadoCita(<?php echo $cita_id; ?>, 'pendiente')">Marcar en Espera</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    echo 'No se encontró la cita solicitada';
}
?>

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