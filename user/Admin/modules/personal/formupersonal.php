<?php
//session_start();
include '../../config/conexion.php';
/*
// Verificar permisos
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}
*/
// Obtener datos del personal si se está editando
$personal = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM personal WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);
    $personal = mysqli_fetch_assoc($resultado);
    
    // Obtener especialidades asignadas
    if ($personal) {
        $query_esp = "SELECT especialidad_id FROM personal_especialidades WHERE personal_id = $id";
        $result_esp = mysqli_query($conexion, $query_esp);
        $especialidades_asignadas = array();
        while ($row = mysqli_fetch_assoc($result_esp)) {
            $especialidades_asignadas[] = $row['id'];
        }
    }
}

// Obtener todas las especialidades
$query_especialidades = "SELECT * FROM especialidades ORDER BY nombre";
$resultado_especialidades = mysqli_query($conexion, $query_especialidades);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $personal ? 'Editar Personal' : 'Nuevo Personal'; ?></title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5">
        <h2><?php echo $personal ? 'Editar Personal' : 'Nuevo Personal'; ?></h2>
        <form method="POST" action="procesar_personal.php" enctype="multipart/form-data">
            <input type="hidden" name="personal_id" value="<?php echo $personal['id'] ?? ''; ?>">
            
            <div class="row">
                <div class="col-md-8">
                    <!-- Información Personal -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>Información Personal</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $personal['nombre'] ?? ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="apellidos" class="form-label">Apellidos</label>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $personal['apellidos'] ?? ''; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="dni" class="form-label">DNI</label>
                                        <input type="text" class="form-control" id="dni" name="dni" value="<?php echo htmlspecialchars($personal['dni'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($personal['fecha_nacimiento'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="genero" class="form-label">Género</label>
                                        <select class="form-select" id="genero" name="genero" required>
                                            <option value="">Seleccionar...</option>
                                            <option value="M" <?php echo (isset($personal['genero']) && $personal['genero'] == 'M') ? 'selected' : ''; ?>>Masculino</option>
                                            <option value="F" <?php echo (isset($personal['genero']) && $personal['genero'] == 'F') ? 'selected' : ''; ?>>Femenino</option>
                                            <option value="O" <?php echo (isset($personal['genero']) && $personal['genero'] == 'O') ? 'selected' : ''; ?>>Otro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>Información de Contacto</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($personal['telefono'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($personal['email'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2"><?php echo htmlspecialchars($personal['direccion'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Información Profesional -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>Información Profesional</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipo" class="form-label">Tipo de Personal</label>
                                        <select class="form-control" id="tipo" name="tipo" required>
                                            <option value="Doctor" <?php echo (isset($personal) && $personal['tipo'] === 'Doctor') ? 'selected' : ''; ?>>Doctor</option>
                                            <option value="Enfermero" <?php echo (isset($personal) && $personal['tipo'] === 'Enfermero') ? 'selected' : ''; ?>>Enfermero</option>
                                            <option value="Farmaceutico" <?php echo (isset($personal) && $personal['tipo'] === 'Farmaceutico') ? 'selected' : ''; ?>>Farmacéutico</option>
                                            <option value="Otro" <?php echo (isset($personal) && $personal['tipo'] === 'Otro') ? 'selected' : ''; ?>>Otro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="num_colegiado" class="form-label">Número de Colegiado</label>
                                        <input type="text" class="form-control" id="num_colegiado" name="num_colegiado" value="<?php echo htmlspecialchars($personal['num_colegiado'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="especialidades" class="form-label">Especialidades</label>
                                <select class="form-control select2" id="especialidades" name="especialidades[]" multiple>
                                    <?php while ($esp = mysqli_fetch_assoc($resultado_especialidades)): ?>
                                        <option value="<?php echo $esp['id']; ?>" 
                                            <?php echo (isset($especialidades_asignadas) && in_array($esp['id'], $especialidades_asignadas)) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($esp['nombre']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="cv" class="form-label">CV/Experiencia</label>
                                <textarea class="form-control" id="cv" name="cv" rows="3"><?php echo htmlspecialchars($personal['cv'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Foto y Estado -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>Foto y Estado</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <?php if (isset($personal) && $personal['foto']): ?>
                                    <img src="../../uploads/<?php echo $personal['foto']; ?>" alt="Foto" class="img-fluid mt-2">
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="Activo" <?php echo (isset($personal['estado']) && $personal['estado'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                                    <option value="Inactivo" <?php echo (isset($personal['estado']) && $personal['estado'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                                    <option value="Vacaciones" <?php echo (isset($personal['estado']) && $personal['estado'] == 'Vacaciones') ? 'selected' : ''; ?>>Vacaciones</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="personal.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
</body>
</html> 