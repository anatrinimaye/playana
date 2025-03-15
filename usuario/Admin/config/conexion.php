<?php
$conexion = mysqli_connect('localhost', 'root', '', 'clinica');

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Establecer el conjunto de caracteres
mysqli_set_charset($conexion, "utf8mb4");
?> 