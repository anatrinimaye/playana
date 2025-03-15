<?php
require_once __DIR__ . '/../../config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Horarios Bloqueados</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="crud-header">
            <div class="header-left">
                <h2>Gestión de Horarios Bloqueados</h2>
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Buscar bloqueos...">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>
            <div class="header-right">
                <button class="btn-add" onclick="openModal()">
                    <i class="fas fa-plus"></i>
                    <span>Agregar Bloqueo</span>
                </button>
            </div>
        </div>

        <div id="message-container"></div>

        <div class="table-container">
            <table id="bloqueoTable">
                <thead>
                    <tr>
                        <th>Médico</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Motivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos se cargarán dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Bloqueo -->
    <div id="bloqueoModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle">Agregar Bloqueo de Horario</h3>
            <form id="bloqueoForm">
                <input type="hidden" id="id" name="id">
                
                <div class="form-group">
                    <label for="medico_id">Médico:</label>
                    <select id="medico_id" name="medico_id" required>
                        <!-- Se cargará dinámicamente -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_inicio">Fecha y Hora de Inicio:</label>
                    <input type="datetime-local" id="fecha_inicio" name="fecha_inicio" required>
                </div>

                <div class="form-group">
                    <label for="fecha_fin">Fecha y Hora de Fin:</label>
                    <input type="datetime-local" id="fecha_fin" name="fecha_fin" required>
                </div>

                <div class="form-group">
                    <label for="motivo">Motivo:</label>
                    <textarea id="motivo" name="motivo" required rows="3"></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save" data-action="save-new">
                        <i class="fas fa-save"></i>
                        Guardar y Nuevo
                    </button>
                    <button type="submit" class="btn-save" data-action="save-return">
                        <i class="fas fa-check"></i>
                        Guardar y Volver
                    </button>
                    <button type="button" class="btn-cancel" onclick="closeModal()">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= BASE_URL ?>assets/js/horarios_bloqueados.js"></script>
</body>
</html> 