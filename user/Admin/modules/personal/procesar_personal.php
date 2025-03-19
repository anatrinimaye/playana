<?php
session_start();
include '../../config/conexion.php';

// Verificar permisos
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanitizar datos básicos
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conexion, $_POST['apellidos']);
    $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
    $dni = mysqli_real_escape_string($conexion, $_POST['dni']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
    $fecha_nacimiento = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']);
    $genero = mysqli_real_escape_string($conexion, $_POST['genero']);
    $num_colegiado = mysqli_real_escape_string($conexion, $_POST['num_colegiado']);
    $cv = mysqli_real_escape_string($conexion, $_POST['cv']);
    
    // Verificar si el DNI ya existe
    $query = "SELECT * FROM personal WHERE dni = '$dni'";
    $resultado = mysqli_query($conexion, $query);

    if (mysqli_num_rows($resultado) > 0) {
        $_SESSION['error'] = "El DNI ya está registrado.";
        header('Location: personal.php');
        exit();
    }

    // Procesar foto si se ha subido una nueva
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_nombre = uniqid() . '_' . $_FILES['foto']['name'];
        $foto_destino = '../../uploads/' . $foto_nombre;
        
        if (move_uploaded_file($foto_tmp, $foto_destino)) {
            $foto = $foto_nombre;
        }
    }

    // Preparar la consulta SQL
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $query = "UPDATE personal SET 
            nombre = '$nombre',
            apellidos = '$apellidos',
            tipo = '$tipo',
            especialidad_id = $especialidad_id,
            dni = '$dni',
            telefono = '$telefono',
            email = '$email',
            direccion = '$direccion',
            estado = '$estado',
            num_colegiado = '$num_colegiado',
            fecha_nacimiento = '$fecha_nacimiento'
            WHERE id = $id";
        
        if ($foto) {
            $query .= ", foto = '$foto'";
        }
        
        $query .= " WHERE id = $id";
    } else {
        // Inserción
        $query = "INSERT INTO personal (
            nombre, apellidos, tipo, dni, telefono, email, direccion, estado,
            fecha_nacimiento, genero, num_colegiado, cv, foto, fecha_registro
        ) VALUES (
            '$nombre', '$apellidos', '$tipo', '$dni', '$telefono', '$email', 
            '$direccion', '$estado', '$fecha_nacimiento', '$genero', 
            '$num_colegiado', '$cv', '$foto', NOW()
        )";
    }

    // Ejecutar la consulta
    if (mysqli_query($conexion, $query)) {
        $id = isset($_POST['id']) ? intval($_POST['id']) : mysqli_insert_id($conexion);
        
        // Manejar especialidades
        if (isset($_POST['especialidades'])) {
            // Primero eliminar todas las especialidades existentes
            mysqli_query($conexion, "DELETE FROM personal_especialidades WHERE personal_id = $id");
            
            // Luego insertar las nuevas especialidades
            foreach ($_POST['especialidades'] as $especialidad_id) {
                $especialidad_id = intval($especialidad_id);
                mysqli_query($conexion, "INSERT INTO personal_especialidades (personal_id, especialidad_id) 
                                         VALUES ($id, $especialidad_id)");
            }
        }
        
        $_SESSION['mensaje'] = "Personal actualizado correctamente";
        header('Location: personal.php');
        exit();
    } else {
        $_SESSION['error'] = "Error al actualizar el personal: " . mysqli_error($conexion);
        // Agregar un var_dump para depurar
        var_dump($query);
    }
}

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $query = "DELETE FROM personal WHERE id = $id"; // Asegúrate de que 'id' sea la columna correcta
    
    if (mysqli_query($conexion, $query)) {
        $_SESSION['mensaje'] = "Personal eliminado correctamente";
        header('Location: personal.php');
        exit();
    } else {
        $_SESSION['error'] = "Error al eliminar el personal: " . mysqli_error($conexion);
    }
}

header('Location: personal.php');
exit();
?> 