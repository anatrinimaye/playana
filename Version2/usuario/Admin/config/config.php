<?php

$conexion = mysqli_connect('localhost', 'root', '', 'clinica');

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?> 