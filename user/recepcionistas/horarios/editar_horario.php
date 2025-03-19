<?php
include '../../config/conexion.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Obtener los datos del horario
    $query = "SELECT * FROM horarios WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);
    $horario = mysqli_fetch_assoc($resultado);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dia_semana = $_POST['dia_semana'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];

        // Actualizar el horario
        $query_update = "UPDATE horarios SET dia_semana = '$dia_semana', hora_inicio = '$hora_inicio', hora_fin = '$hora_fin' WHERE id = $id";
        if (mysqli_query($conexion, $query_update)) {
            header('Location: horarios.php?mensaje=Horario actualizado correctamente');
        } else {
            echo "Error al actualizar el horario: " . mysqli_error($conexion);
        }
    }
} else {
    header('Location: horarios.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Horario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Editar Horario</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="dia_semana" class="form-label">Día de la Semana</label>
                <select class="form-select" id="dia_semana" name="dia_semana" required>
                    <option value="Lunes" <?php echo $horario['dia_semana'] === 'Lunes' ? 'selected' : ''; ?>>Lunes</option>
                    <option value="Martes" <?php echo $horario['dia_semana'] === 'Martes' ? 'selected' : ''; ?>>Martes</option>
                    <option value="Miércoles" <?php echo $horario['dia_semana'] === 'Miércoles' ? 'selected' : ''; ?>>Miércoles</option>
                    <option value="Jueves" <?php echo $horario['dia_semana'] === 'Jueves' ? 'selected' : ''; ?>>Jueves</option>
                    <option value="Viernes" <?php echo $horario['dia_semana'] === 'Viernes' ? 'selected' : ''; ?>>Viernes</option>
                    <option value="Sábado" <?php echo $horario['dia_semana'] === 'Sábado' ? 'selected' : ''; ?>>Sábado</option>
                    <option value="Domingo" <?php echo $horario['dia_semana'] === 'Domingo' ? 'selected' : ''; ?>>Domingo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="hora_inicio" class="form-label">Hora Inicio</label>
                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" value="<?php echo $horario['hora_inicio']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="hora_fin" class="form-label">Hora Fin</label>
                <input type="time" class="form-control" id="hora_fin" name="hora_fin" value="<?php echo $horario['hora_fin']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="horarios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>