<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/horarios_bloqueados.php';

header('Content-Type: application/json');

try {
    $horarios = new HorariosBloqueados($conn);
    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    switch($action) {
        case 'getAll':
            $result = $horarios->getAll();
            echo json_encode(['status' => 'success', 'data' => $result]);
            break;

        case 'getMedicos':
            $result = $horarios->getMedicos();
            echo json_encode(['status' => 'success', 'data' => $result]);
            break;

        case 'create':
            $data = [
                'medico_id' => $_POST['medico_id'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => $_POST['fecha_fin'],
                'motivo' => $_POST['motivo']
            ];

            // Verificar solapamiento
            if ($horarios->verificarSolapamiento($data)) {
                throw new Exception("Ya existe un bloqueo para este médico en el rango de fechas seleccionado");
            }

            $result = $horarios->create($data);
            echo json_encode(['status' => 'success', 'message' => 'Bloqueo creado exitosamente']);
            break;

        case 'update':
            $data = [
                'id' => $_POST['id'],
                'medico_id' => $_POST['medico_id'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => $_POST['fecha_fin'],
                'motivo' => $_POST['motivo']
            ];

            // Verificar solapamiento
            if ($horarios->verificarSolapamiento($data)) {
                throw new Exception("Ya existe un bloqueo para este médico en el rango de fechas seleccionado");
            }

            $result = $horarios->update($data);
            echo json_encode(['status' => 'success', 'message' => 'Bloqueo actualizado exitosamente']);
            break;

        case 'delete':
            $result = $horarios->delete($_POST['id']);
            echo json_encode(['status' => 'success', 'message' => 'Bloqueo eliminado exitosamente']);
            break;

        case 'getById':
            $result = $horarios->getById($_GET['id']);
            echo json_encode(['status' => 'success', 'data' => $result]);
            break;

        default:
            throw new Exception('Acción no válida');
    }
} catch(Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} 