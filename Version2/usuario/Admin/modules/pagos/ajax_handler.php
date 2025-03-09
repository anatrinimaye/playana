<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/pagos.php';

header('Content-Type: application/json');

try {
    $pagos = new Pagos($conn);
    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    switch($action) {
        case 'getAll':
            $result = $pagos->getAll();
            echo json_encode(['status' => 'success', 'data' => $result]);
            break;

        case 'getPacientes':
            $result = $pagos->getPacientes();
            echo json_encode(['status' => 'success', 'data' => $result]);
            break;

        case 'create':
            $data = [
                'paciente_id' => $_POST['paciente_id'],
                'concepto' => $_POST['concepto'],
                'monto' => $_POST['monto'],
                'metodo_pago' => $_POST['metodo_pago'],
                'fecha' => $_POST['fecha'],
                'estado' => $_POST['estado'],
                'observaciones' => $_POST['observaciones']
            ];
            $result = $pagos->create($data);
            echo json_encode(['status' => 'success', 'message' => 'Pago registrado exitosamente']);
            break;

        case 'update':
            $data = [
                'id' => $_POST['id'],
                'paciente_id' => $_POST['paciente_id'],
                'concepto' => $_POST['concepto'],
                'monto' => $_POST['monto'],
                'metodo_pago' => $_POST['metodo_pago'],
                'fecha' => $_POST['fecha'],
                'estado' => $_POST['estado'],
                'observaciones' => $_POST['observaciones']
            ];
            $result = $pagos->update($data);
            echo json_encode(['status' => 'success', 'message' => 'Pago actualizado exitosamente']);
            break;

        case 'delete':
            $result = $pagos->delete($_POST['id']);
            echo json_encode(['status' => 'success', 'message' => 'Pago eliminado exitosamente']);
            break;

        case 'getById':
            $result = $pagos->getById($_GET['id']);
            echo json_encode(['status' => 'success', 'data' => $result]);
            break;

        case 'filtrar':
            $fecha = $_GET['fecha'] ?? null;
            $estado = $_GET['estado'] ?? null;
            $result = $pagos->filtrarPagos($fecha, $estado);
            echo json_encode(['status' => 'success', 'data' => $result]);
            break;

        default:
            throw new Exception('Acción no válida');
    }
} catch(Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} 