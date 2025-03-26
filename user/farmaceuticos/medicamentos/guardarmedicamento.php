<?php
include '../../config/conexion.php';

if (!$conexion) {
    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
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
        echo "Error en la consulta SQL: " . mysqli_error($conexion);
        echo "<br>Consulta ejecutada: " . $query;
    }
}
?>