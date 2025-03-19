<?php
require_once __DIR__ . '/../../config/config.php';

class Pagos {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        try {
            $query = "SELECT p.*, CONCAT(pac.nombres, ' ', pac.apellidos) as nombre_paciente 
                     FROM pagos p 
                     LEFT JOIN pacientes pac ON p.paciente_id = pac.id 
                     ORDER BY p.fecha DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener pagos: " . $e->getMessage());
        }
    }

    public function create($data) {
        try {
            // Generar número de recibo
            $data['numero_recibo'] = $this->generarNumeroRecibo();
            
            $query = "INSERT INTO pagos (numero_recibo, paciente_id, concepto, monto, 
                                       metodo_pago, fecha, estado, observaciones) 
                     VALUES (:numero_recibo, :paciente_id, :concepto, :monto, 
                             :metodo_pago, :fecha, :estado, :observaciones)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        } catch(PDOException $e) {
            throw new Exception("Error al crear pago: " . $e->getMessage());
        }
    }

    public function update($data) {
        try {
            $query = "UPDATE pagos 
                     SET paciente_id = :paciente_id,
                         concepto = :concepto,
                         monto = :monto,
                         metodo_pago = :metodo_pago,
                         fecha = :fecha,
                         estado = :estado,
                         observaciones = :observaciones 
                     WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        } catch(PDOException $e) {
            throw new Exception("Error al actualizar pago: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            // Verificar si el pago puede ser eliminado
            $pago = $this->getById($id);
            if ($pago['estado'] === 'Pagado') {
                throw new Exception("No se puede eliminar un pago ya procesado");
            }

            $query = "DELETE FROM pagos WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute(['id' => $id]);
        } catch(PDOException $e) {
            throw new Exception("Error al eliminar pago: " . $e->getMessage());
        }
    }

    public function getById($id) {
        try {
            $query = "SELECT p.*, CONCAT(pac.nombres, ' ', pac.apellidos) as nombre_paciente 
                     FROM pagos p 
                     LEFT JOIN pacientes pac ON p.paciente_id = pac.id 
                     WHERE p.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener pago: " . $e->getMessage());
        }
    }

    public function getPacientes() {
        try {
            $query = "SELECT id, CONCAT(nombres, ' ', apellidos) as nombre_completo 
                     FROM pacientes 
                     WHERE estado = 'Activo' 
                     ORDER BY apellidos, nombres";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener pacientes: " . $e->getMessage());
        }
    }

    private function generarNumeroRecibo() {
        try {
            // Obtener el último número de recibo
            $query = "SELECT MAX(CAST(SUBSTRING(numero_recibo, 5) AS UNSIGNED)) as ultimo 
                     FROM pagos 
                     WHERE numero_recibo LIKE CONCAT(YEAR(CURRENT_DATE), '%')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $ultimo = $result['ultimo'] ?? 0;
            $siguiente = $ultimo + 1;
            
            // Formato: AAAA-NNNNN (ejemplo: 2024-00001)
            return date('Y') . '-' . str_pad($siguiente, 5, '0', STR_PAD_LEFT);
        } catch(PDOException $e) {
            throw new Exception("Error al generar número de recibo: " . $e->getMessage());
        }
    }

    public function filtrarPagos($fecha = null, $estado = null) {
        try {
            $conditions = [];
            $params = [];
            
            if ($fecha) {
                $conditions[] = "DATE(p.fecha) = :fecha";
                $params['fecha'] = $fecha;
            }
            
            if ($estado) {
                $conditions[] = "p.estado = :estado";
                $params['estado'] = $estado;
            }
            
            $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
            
            $query = "SELECT p.*, CONCAT(pac.nombres, ' ', pac.apellidos) as nombre_paciente 
                     FROM pagos p 
                     LEFT JOIN pacientes pac ON p.paciente_id = pac.id 
                     $whereClause 
                     ORDER BY p.fecha DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al filtrar pagos: " . $e->getMessage());
        }
    }
} 