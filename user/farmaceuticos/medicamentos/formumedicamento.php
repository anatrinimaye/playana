<?php
include '../../config/conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$medicamento = null;

if ($id > 0) {
    $query = "SELECT * FROM medicamentos WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);
    $medicamento = mysqli_fetch_assoc($resultado);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id > 0 ? 'Editar Medicamento' : 'Nuevo Medicamento'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2><?php echo $id > 0 ? 'Editar Medicamento' : 'Nuevo Medicamento'; ?></h2>
        <form method="POST" action="formumedicamento.php">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($medicamento['nombre'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($medicamento['descripcion'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" step="0.01" value="<?php echo htmlspecialchars($medicamento['precio'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($medicamento['stock'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="medicamentos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>