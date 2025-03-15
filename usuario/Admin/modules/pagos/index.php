<?php
require_once __DIR__ . '/../../config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Pagos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>

<style>
        /* Estilos para la tabla de personal */
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        #pagosTable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        #pagosTable th,
        #pagosTable td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        #pagosTable th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #2d3748;
        }

        #pagosTable tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            cursor: pointer;
        }

        /* Estilos para los formularios */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #4a5568;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        /* Estilos para los botones */
        .btn-add {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-save {
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-cancel {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Estilos para el header */
        .crud-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-container {
            position: relative;
        }

        .search-container input {
            padding: 8px 32px 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            width: 250px;
        }

        .search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        /* Estilos para mensajes */
        #message-container {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>

<body>
    <div class="container">
        <div class="crud-header">
            <div class="header-left">
                <h2>Gestión de Pagos</h2>
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Buscar pagos...">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>
            <div class="header-right">
                <button class="btn-add" onclick="openModal()">
                    <i class="fas fa-plus"></i>
                    <span>Registrar Pago</span>
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-container">
            <div class="filter-group">
                <label for="filterFecha">Fecha:</label>
                <input type="date" id="filterFecha">
            </div>
            <div class="filter-group">
                <label for="filterEstado">Estado:</label>
                <select id="filterEstado">
                    <option value="">Todos</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Pagado">Pagado</option>
                    <option value="Anulado">Anulado</option>
                </select>
            </div>
        </div>

        <div id="message-container"></div>

        <div class="table-container">
            <table id="pagosTable">
                <thead>
                    <tr>
                        <th>Nº Recibo</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Método Pago</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos se cargarán dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Pago -->
    <div id="pagoModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle">Registrar Pago</h3>
            <form id="pagoForm">
                <input type="hidden" id="id" name="id">
                
                <div class="form-group">
                    <label for="paciente_id">Paciente:</label>
                    <select id="paciente_id" name="paciente_id" required>
                        <!-- Se cargará dinámicamente -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="concepto">Concepto:</label>
                    <select id="concepto" name="concepto" required>
                        <option value="Consulta">Consulta</option>
                        <option value="Tratamiento">Tratamiento</option>
                        <option value="Medicamentos">Medicamentos</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="monto">Monto:</label>
                    <input type="number" id="monto" name="monto" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="metodo_pago">Método de Pago:</label>
                    <select id="metodo_pago" name="metodo_pago" required>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Tarjeta">Tarjeta</option>
                        <option value="Transferencia">Transferencia</option>
                        <option value="Yape">Yape</option>
                        <option value="Plin">Plin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="datetime-local" id="fecha" name="fecha" required>
                </div>

                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado" required>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Pagado">Pagado</option>
                        <option value="Anulado">Anulado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="observaciones">Observaciones:</label>
                    <textarea id="observaciones" name="observaciones" rows="3"></textarea>
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

    <script src="<?= BASE_URL ?>assets/js/pagos.js"></script>
    <script src="../../js/bootstrap.bunle.min.js"></script>
</body>
</html> 