<?php
class NotificationSystem {
    public function enviarNotificacion($tipo, $destinatario, $mensaje) {
        switch($tipo) {
            case 'cita_confirmada':
                // Enviar SMS y email al paciente
                $this->enviarSMS($destinatario, $mensaje);
                $this->enviarEmail($destinatario, 'Confirmación de Cita', $mensaje);
                break;
            
            case 'recordatorio_cita':
                // Enviar recordatorio 24h antes
                $this->enviarSMS($destinatario, $mensaje);
                break;
                
            case 'resultados_listos':
                // Notificar al médico y paciente
                $this->enviarNotificacionInterna($destinatario, $mensaje);
                break;
        }
    }
} 