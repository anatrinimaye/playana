<?php
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $estado = $stock > 0 ? 'Disponible' : 'Agotado';

    if ($id > 0) {
        // Actualizar medicamento existente
        $query = "UPDATE medicamentos SET nombre = '$nombre', descripcion = '$descripcion', precio = $precio, stock = $stock, estado = '$estado' WHERE id = $id";
    } else {
        // Insertar nuevo medicamento
        $query = "INSERT INTO medicamentos (nombre, descripcion, precio, stock, estado) VALUES ('$nombre', '$descripcion', $precio, $stock, '$estado')";
    }

    if (mysqli_query($conexion, $query)) {
        header('Location: medicamentos.php?mensaje=Medicamento guardado correctamente');
        exit();
    } else {
        echo "Error al guardar el medicamento: " . mysqli_error($conexion);
    }
}
?>