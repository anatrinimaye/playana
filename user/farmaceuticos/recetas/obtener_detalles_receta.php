<?php
include '../../config/conexion.php';

if (!isset($_GET['id'])) {
    echo "ID de receta no proporcionado";
    exit();
}

$id = intval($_GET['id']);
echo "ID recibido: $id<br>";

$query = "SELECT r.*, 
                 m.nombre AS medicamento, 
                 m.stock, 
                 p.nombre AS paciente, 
                 p.dni, 
                 d.nombre AS doctor, 
                 r.fecha_emision AS fecha_consulta
          FROM recetas r
          LEFT JOIN medicamentos m ON r.medicamento_id = m.id
          LEFT JOIN pacientes p ON r.paciente_id = p.id
          LEFT JOIN usuario d ON r.medico_id = d.id
          WHERE r.id = $id";

$resultado = mysqli_query($conexion, $query);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

$receta = mysqli_fetch_assoc($resultado);

if (!$receta) {
    echo "Receta no encontrada para el ID: $id<br>";
    exit();
}

// Nueva consulta para búsqueda
$busqueda = $_GET['busqueda'] ?? '';
$query_busqueda = "SELECT r.*, p.nombre as paciente_nombre, m.nombre as medico_nombre 
                   FROM recetas r 
                   LEFT JOIN pacientes p ON r.paciente_id = p.id 
                   LEFT JOIN personal m ON r.medico_id = m.id 
                   WHERE 
                   p.nombre LIKE '%$busqueda%' OR 
                   m.nombre LIKE '%$busqueda%' 
                   ORDER BY r.fecha_emision DESC";

$resultado_busqueda = mysqli_query($conexion, $query_busqueda);

if (!$resultado_busqueda) {
    die("Error en la consulta de búsqueda: " . mysqli_error($conexion));
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<div class="container-fluid p-4 bg-light rounded shadow">
    <!-- Información del Paciente -->
    <div class="mb-4">
        <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-user"></i> Información del Paciente</h5>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($receta['paciente']); ?></p>
                <p><strong>Documento:</strong> <?php echo htmlspecialchars($receta['dni']); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Doctor:</strong> <?php echo htmlspecialchars($receta['doctor'] ?? 'No asignado'); ?></p>
            </div>
        </div>
    </div>

    <!-- Información de la Consulta -->
    <div class="mb-4">
        <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-calendar-alt"></i> Información de la Consulta</h5>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($receta['fecha_consulta'])); ?></p>
                <p><strong>Diagnóstico:</strong> <?php echo htmlspecialchars($receta['diagnostico'] ?? 'No especificado'); ?></p>
            </div>
        </div>
    </div>

    <!-- Información del Medicamento -->
    <div class="mb-4">
        <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-pills"></i> Información del Medicamento</h5>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($receta['medicamento']); ?></p>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($receta['descripcion'] ?? 'No especificada'); ?></p>
                <p><strong>Stock:</strong> 
                    <span class="badge <?php echo $receta['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                        <?php echo htmlspecialchars($receta['stock']); ?>
                    </span>
                </p>
                <p><strong>Precio:</strong> <?php echo htmlspecialchars($receta['precio'] ?? 'No especificado'); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Estado:</strong> 
                    <span class="badge 
                        <?php 
                        echo $receta['estado'] === 'activa' ? 'bg-warning' : 
                            ($receta['estado'] === 'dispensada' ? 'bg-success' : 'bg-secondary'); 
                        ?>">
                        <?php echo ucfirst($receta['estado']); ?>
                    </span>
                </p>
                <p><strong>Cantidad:</strong> <?php echo htmlspecialchars($receta['cantidad'] ?? 'No especificada'); ?></p>
            </div>
        </div>
    </div>

    <!-- Información de la Prescripción -->
    <div class="mb-4">
        <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-file-prescription"></i> Detalles de la Prescripción</h5>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Dosis:</strong> <?php echo htmlspecialchars($receta['dosis']); ?></p>
                <p><strong>Frecuencia:</strong> <?php echo htmlspecialchars($receta['frecuencia']); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Duración:</strong> <?php echo htmlspecialchars($receta['duracion']); ?></p>
            </div>
        </div>
    </div>

    <!-- Estado de la Receta -->
    <div class="mb-4">
        <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-info-circle"></i> Estado de la Receta</h5>
        <div class="row">
            <div class="col-md-6">
                <p>
                    <strong>Estado:</strong>
                    <span class="badge 
                        <?php 
                        echo $receta['estado'] === 'activa' ? 'bg-warning' : 
                            ($receta['estado'] === 'dispensada' ? 'bg-success' : 'bg-secondary'); 
                        ?>">
                        <?php echo ucfirst($receta['estado']); ?>
                    </span>
                </p>
                <p><strong>Fecha de Receta:</strong> <?php echo isset($receta['fecha_receta']) ? date('d/m/Y H:i', strtotime($receta['fecha_receta'])) : 'No especificada'; ?></p>
            </div>
            <div class="col-md-6">
                <?php if ($receta['estado'] !== 'activa'): ?>
                    <p><strong>Fecha de <?php echo $receta['estado'] === 'dispensada' ? 'Dispensación' : 'Cancelación'; ?>:</strong>
                        <?php echo isset($receta['fecha_dispensacion']) ? date('d/m/Y H:i', strtotime($receta['fecha_dispensacion'])) : 'No especificada'; ?></p>
                    <p><strong><?php echo $receta['estado'] === 'dispensada' ? 'Dispensado por' : 'Cancelado por'; ?>:</strong>
                        <?php echo htmlspecialchars($receta['dispensador'] ?? 'No especificado'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>