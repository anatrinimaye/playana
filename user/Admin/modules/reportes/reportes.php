<?php
include "../../../Admin/config/conexion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes - Clínica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Generar Reportes</h2>
        <div class="list-group">
            <a href="./report/indexreportes_pacientes.php" class="list-group-item list-group-item-action">Reportes de Pacientes</a>
            <a href="./report/indexreportes_citas.php" class="list-group-item list-group-item-action">Reportes de Citas</a>
            <a href="./report/indexreportes_recetas.php" class="list-group-item list-group-item-action">Reportes de Recetas</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 