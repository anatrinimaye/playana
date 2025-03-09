<?php
// Verificar si el archivo se está incluyendo desde dashboard.php
if (!defined('BASE_URL')) {
    die('Acceso no autorizado');
}
?>

<table id="proximasCitasTable" class="table">
    <thead>
        <tr>
            <th>Fecha y Hora</th>
            <th>Paciente</th>
            <th>Doctor</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <!-- Los datos se cargarán dinámicamente via JavaScript -->
    </tbody>
</table> 