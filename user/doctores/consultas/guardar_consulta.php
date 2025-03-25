<?php
//session_start();
include '../../config/conexion.php';
/*
// Verificar si el usuario está logueado y es doctor
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'doctor') {
    header('Location: ../../login.php');
    exit();
}*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanitizar datos básicos
    $paciente_id = intval($_POST['paciente_id'] ?? 0);
    $doctor_id = intval($_POST['doctor_id'] ?? 0);

    if ($paciente_id === 0 || $doctor_id === 0) {
        die("Error: No se recibieron los datos del paciente o del doctor.");
    }
    
    // Sanitizar datos de la consulta
    $presion_arterial = mysqli_real_escape_string($conexion, $_POST['presion_arterial']);
    $temperatura = floatval($_POST['temperatura']);
    $frecuencia_cardiaca = intval($_POST['frecuencia_cardiaca']);
    $peso = floatval($_POST['peso']);
    $motivo = mysqli_real_escape_string($conexion, $_POST['motivo']);
    $sintomas = mysqli_real_escape_string($conexion, $_POST['sintomas']);
    $diagnostico = mysqli_real_escape_string($conexion, $_POST['diagnostico']);
    $tratamiento = mysqli_real_escape_string($conexion, $_POST['tratamiento']);
    $examenes = mysqli_real_escape_string($conexion, $_POST['examenes']);
    $notas_examenes = mysqli_real_escape_string($conexion, $_POST['notas_examenes']);

    // Iniciar transacción
    mysqli_begin_transaction($conexion);

    try {
        // Insertar consulta
        $query = "INSERT INTO consultas (
            paciente_id, doctor_id, fecha_hora, presion_arterial, temperatura,
            frecuencia_cardiaca, peso, motivo, sintomas, diagnostico, tratamiento
        ) VALUES (
            $paciente_id, $doctor_id, NOW(), '$presion_arterial', $temperatura,
            $frecuencia_cardiaca, $peso, '$motivo', '$sintomas', '$diagnostico', '$tratamiento'
        )";
        
        if (!mysqli_query($conexion, $query)) {
            throw new Exception("Error al guardar la consulta: " . mysqli_error($conexion));
        }
        
        $consulta_id = mysqli_insert_id($conexion);

        // Guardar en historial médico
        $query = "INSERT INTO historial_medico (
            paciente_id, consulta_id, fecha, diagnostico, tratamiento
        ) VALUES (
            $paciente_id, $consulta_id, NOW(), '$diagnostico', '$tratamiento'
        )";
        
        if (!mysqli_query($conexion, $query)) {
            throw new Exception("Error al guardar el historial: " . mysqli_error($conexion));
        }

        // Procesar receta médica
        if (isset($_POST['medicamentos']) && is_array($_POST['medicamentos'])) {
            foreach ($_POST['medicamentos'] as $index => $medicamento_id) {
                if (empty($medicamento_id)) continue;
                
                $medicamento_id = intval($medicamento_id);
                $dosis = mysqli_real_escape_string($conexion, $_POST['dosis'][$index]);
                $frecuencia = mysqli_real_escape_string($conexion, $_POST['frecuencia'][$index]);
                $duracion = mysqli_real_escape_string($conexion, $_POST['duracion'][$index]);

                $query = "INSERT INTO recetas (
                    consulta_id, medicamento_id, dosis, frecuencia, duracion
                ) VALUES (
                    $consulta_id, $medicamento_id, '$dosis', '$frecuencia', '$duracion'
                )";
                
                if (!mysqli_query($conexion, $query)) {
                    throw new Exception("Error al guardar la receta: " . mysqli_error($conexion));
                }
            }
        }

        // Guardar exámenes solicitados si existen
        if (!empty($examenes)) {
            $query = "INSERT INTO examenes_solicitados (
                consulta_id, descripcion, notas, fecha_solicitud
            ) VALUES (
                $consulta_id, '$examenes', '$notas_examenes', NOW()
            )";
            
            if (!mysqli_query($conexion, $query)) {
                throw new Exception("Error al guardar los exámenes: " . mysqli_error($conexion));
            }
        }

        // Confirmar transacción
        mysqli_commit($conexion);
        $_SESSION['mensaje'] = "Consulta guardada correctamente";
        header('Location: ver_consulta.php?id=' . $consulta_id);
        exit();

    } catch (Exception $e) {
        // Revertir transacción en caso de error
        mysqli_rollback($conexion);
        $_SESSION['error'] = $e->getMessage();
        header('Location: nueva_consulta.php?paciente_id=' . $paciente_id);
        exit();
    }
}

header('Location: consultas.php');
exit();