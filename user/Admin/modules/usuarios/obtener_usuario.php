<?php
include "../../config/conexion.php";

$id = intval($_GET['id']);
$query = "SELECT id, nombre, email, fecha_creacion, rol FROM usuarios WHERE id = $id";
$resultado = mysqli_query($conexion, $query);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $usuario = mysqli_fetch_assoc($resultado);
    echo json_encode($usuario);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}
?>