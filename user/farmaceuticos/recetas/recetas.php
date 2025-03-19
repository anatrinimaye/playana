<?php
include "../../config/conexion.php";

// Búsqueda de recetas
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$query = "SELECT r.*, p.nombre as paciente_nombre, m.nombre as medico_nombre 
          FROM recetas r 
          LEFT JOIN pacientes p ON r.paciente_id = p.id 
          LEFT JOIN personal m ON r.medico_id = m.id 
          WHERE 
          p.nombre LIKE '%$busqueda%' OR 
          m.nombre LIKE '%$busqueda%' 
          ORDER BY r.fecha_emision DESC";

$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Recetas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2>Lista de Recetas</h2>

        <div class="search-box mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar receta...">
        </div>

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
                    <th>F.Emisión</th>
                    <th>Estado</th>
                    <th>H.Clínico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($receta = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($receta['id']); ?></td>
                            <td><?php echo htmlspecialchars($receta['paciente_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($receta['medico_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($receta['medicamento_id']); ?></td>
                            <td><?php echo htmlspecialchars($receta['dosis']); ?></td>
                            <td><?php echo htmlspecialchars($receta['frecuencia']); ?></td>
                            <td><?php echo htmlspecialchars($receta['duracion']); ?></td>
                            <td><?php echo htmlspecialchars($receta['fecha_emision']); ?></td>
                            <td><?php echo htmlspecialchars($receta['estado']); ?></td>
                            <td><?php echo htmlspecialchars($receta['historial_id']); ?></td>
                            <td>
                                <!-- Botón Ver -->
                                <a href="obtener_detalles_receta.php?id=<?php echo $receta['id']; ?>" class="btn btn-info btn-sm" title="Ver Detalles">
                                    <i class="fas fa-eye"></i> <!-- Icono de "Ver" -->
                                </a>

                                <!-- Botón Dispensar -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDispensar<?php echo $receta['id']; ?>" title="Dispensar Receta">
                                    <i class="fas fa-pills"></i> <!-- Icono de "Dispensar" -->
                                </button>
                            </td>
                        </tr>

                        <!-- Modal para Dispensar Receta -->
                        <div class="modal fade" id="modalDispensar<?php echo $receta['id']; ?>" tabindex="-1" aria-labelledby="modalDispensarLabel<?php echo $receta['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalDispensarLabel<?php echo $receta['id']; ?>">Dispensar Receta</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6>Información del Paciente</h6>
                                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($receta['paciente_nombre']); ?></p>
                                        <p><strong>Médico:</strong> <?php echo htmlspecialchars($receta['medico_nombre']); ?></p>
                                        <p><strong>Fecha de Emisión:</strong> <?php echo htmlspecialchars($receta['fecha_emision']); ?></p>

                                        <h6>Información del Medicamento</h6>
                                        <p><strong>Medicamento:</strong> <?php echo htmlspecialchars($receta['medicamento_id']); ?></p>
                                        <p><strong>Dosis:</strong> <?php echo htmlspecialchars($receta['dosis']); ?></p>
                                        <p><strong>Frecuencia:</strong> <?php echo htmlspecialchars($receta['frecuencia']); ?></p>
                                        <p><strong>Duración:</strong> <?php echo htmlspecialchars($receta['duracion']); ?></p>
                                        <p><strong>Estado:</strong> <?php echo htmlspecialchars($receta['estado']); ?></p>

                                        <?php if (trim(strtolower($receta['estado'])) === 'activa'): ?>
                                            <form method="POST" action="dispensar_receta.php">
                                                <input type="hidden" name="receta_id" value="<?php echo $receta['id']; ?>">
                                                <input type="hidden" name="medicamento_id" value="<?php echo $receta['medicamento_id']; ?>">
                                                <input type="hidden" name="cantidad" value="<?php echo $receta['dosis']; ?>">

                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle"></i>
                                                    ¿Está seguro de dispensar esta receta? Esta acción reducirá el stock del medicamento.
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-check"></i> Confirmar Dispensación
                                                    </button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times"></i> Cancelar
                                                    </button>
                                                </div>
                                            </form>
                                        <?php else: ?>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Esta receta no está activa y no puede ser dispensada.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center">No hay recetas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>