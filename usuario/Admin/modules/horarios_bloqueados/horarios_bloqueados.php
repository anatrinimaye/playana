<?php
require_once __DIR__ . '/../../config/config.php';

class HorariosBloqueados {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        try {
            $query = "SELECT hb.*, CONCAT(p.nombres, ' ', p.apellidos) as nombre_medico 
                     FROM horarios_bloqueados hb 
                     LEFT JOIN personal p ON hb.medico_id = p.id_personal 
                     ORDER BY hb.fecha_inicio DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener horarios bloqueados: " . $e->getMessage());
        }
    }

    public function create($data) {
        try {
            $query = "INSERT INTO horarios_bloqueados (medico_id, fecha_inicio, fecha_fin, motivo) 
                     VALUES (:medico_id, :fecha_inicio, :fecha_fin, :motivo)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        } catch(PDOException $e) {
            throw new Exception("Error al crear bloqueo: " . $e->getMessage());
        }
    }

    public function update($data) {
        try {
            $query = "UPDATE horarios_bloqueados 
                     SET medico_id = :medico_id, 
                         fecha_inicio = :fecha_inicio, 
                         fecha_fin = :fecha_fin, 
                         motivo = :motivo 
                     WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        } catch(PDOException $e) {
            throw new Exception("Error al actualizar bloqueo: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $query = "DELETE FROM horarios_bloqueados WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute(['id' => $id]);
        } catch(PDOException $e) {
            throw new Exception("Error al eliminar bloqueo: " . $e->getMessage());
        }
    }

    public function getById($id) {
        try {
            $query = "SELECT * FROM horarios_bloqueados WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener bloqueo: " . $e->getMessage());
        }
    }

    public function getMedicos() {
        try {
            $query = "SELECT id_personal, CONCAT(nombres, ' ', apellidos) as nombre_completo 
                     FROM personal 
                     WHERE estado = 1 
                     ORDER BY nombres";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener médicos: " . $e->getMessage());
        }
    }

    public function verificarSolapamiento($data) {
        try {
            $query = "SELECT COUNT(*) as total 
                     FROM horarios_bloqueados 
                     WHERE medico_id = :medico_id 
                     AND ((fecha_inicio BETWEEN :fecha_inicio AND :fecha_fin) 
                     OR (fecha_fin BETWEEN :fecha_inicio AND :fecha_fin))
                     AND id != :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'medico_id' => $data['medico_id'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],
                'id' => $data['id'] ?? 0
            ]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0;
        } catch(PDOException $e) {
            throw new Exception("Error al verificar solapamiento: " . $e->getMessage());
        }
    }
} 