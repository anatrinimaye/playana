<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/usuarios.php';

header('Content-Type: application/json');

try {
    $usuarios = new Usuarios($conn);
    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    switch($action) {
        case 'getAll':
            $result = $usuarios->getAll();
            echo json_encode(['status' => 'success', 'data' => $result]);
            break;

        case 'create':
            // Validar que la contraseña no esté vacía para usuarios nuevos
            if (empty($_POST['password'])) {
                throw new Exception("La contraseña es obligatoria para nuevos usuarios");
            }

            $data = [
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'nombre' => $_POST['nombre'],
                'email' => $_POST['email'],
                'rol' => $_POST['rol'],
                'estado' => $_POST['estado']
            ];
            $result = $usuarios->create($data);
            echo json_encode(['status' => 'success', 'message' => 'Usuario creado exitosamente']);
            break;

        case 'update':
            $data = [
                'id' => $_POST['id'],
                'username' => $_POST['username'],
                'nombre' => $_POST['nombre'],
                'email' => $_POST['email'],
                'rol' => $_POST['rol'],
                'estado' => $_POST['estado']
            ];

            // Solo incluir la contraseña si se proporcionó una nueva
            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }

            $result = $usuarios->update($data);
            echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado exitosamente']);
            break;

        case 'delete':
            // Verificar que no se esté eliminando al usuario actual
            if ($_POST['id'] == $_SESSION['user_id']) {
                throw new Exception("No puede eliminar su propio usuario");
            }

            $result = $usuarios->delete($_POST['id']);
            echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado exitosamente']);
            break;

        case 'getById':
            $result = $usuarios->getById($_GET['id']);
            echo json_encode(['status' => 'success', 'data' => $result]);
            break;

        case 'filtrar':
            $rol = $_GET['rol'] ?? null;
            $estado = $_GET['estado'] ?? null;
            $result = $usuarios->filtrarUsuarios($rol, $estado);
            echo json_encode(['status' => 'success', 'data' => $result]);
            break;

        case 'listar':
            $query = "SELECT * FROM usuarios ORDER BY id DESC";
            $result = mysqli_query($conn, $query);
            $usuarios = [];
            
            while ($row = mysqli_fetch_assoc($result)) {
                unset($row['password']); // No enviar contraseñas al cliente
                $usuarios[] = $row;
            }
            
            echo json_encode($usuarios);
            break;
        
        case 'obtener':
            $id = $_GET['id'] ?? 0;
            $query = "SELECT id, username, nombre, rol, estado FROM usuarios WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            echo json_encode(mysqli_fetch_assoc($result));
            break;
        
        case 'guardar':
            $id = $_POST['usuario_id'] ?? '';
            $username = $_POST['username'];
            $password = $_POST['password'];
            $nombre = $_POST['nombre'];
            $rol = $_POST['rol'];
            $estado = $_POST['estado'];
            
            if ($id) {
                // Actualizar usuario existente
                if ($password) {
                    $query = "UPDATE usuarios SET username=?, password=?, nombre=?, rol=?, estado=? WHERE id=?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "sssssi", $username, $password, $nombre, $rol, $estado, $id);
                } else {
                    $query = "UPDATE usuarios SET username=?, nombre=?, rol=?, estado=? WHERE id=?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "ssssi", $username, $nombre, $rol, $estado, $id);
                }
            } else {
                // Crear nuevo usuario
                $query = "INSERT INTO usuarios (username, password, nombre, rol, estado) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "sssss", $username, $password, $nombre, $rol, $estado);
            }
            
            $result = mysqli_stmt_execute($stmt);
            echo json_encode(['success' => $result]);
            break;
        
        case 'eliminar':
            $id = $_GET['id'] ?? 0;
            $query = "DELETE FROM usuarios WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            $result = mysqli_stmt_execute($stmt);
            
            echo json_encode(['success' => $result]);
            break;

        default:
            throw new Exception('Acción no válida');
    }
} catch(Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} 