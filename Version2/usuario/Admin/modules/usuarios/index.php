<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y tiene permisos
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}

// Obtener lista de usuarios
$query = "SELECT * FROM usuarios ORDER BY id DESC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Clínica</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include '../../includes/sidebar.php'; ?>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1><i class="fas fa-users"></i> Gestión de Usuarios</h1>
                    <button class="btn btn-primary" onclick="abrirModal()">
                        <i class="fas fa-plus"></i> Nuevo Usuario
                    </button>
                </div>

                <!-- Tabla de usuarios -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($usuario = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td><?php echo $usuario['id']; ?></td>
                                <td><?php echo $usuario['username']; ?></td>
                                <td><?php echo $usuario['nombre']; ?></td>
                                <td><?php echo ucfirst($usuario['rol']); ?></td>
                                <td>
                                    <span class="badge <?php echo $usuario['estado'] === 'Activo' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $usuario['estado']; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editarUsuario(<?php echo $usuario['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(<?php echo $usuario['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal para Crear/Editar Usuario -->
    <div class="modal fade" id="usuarioModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="usuarioForm" method="POST" action="procesar_usuario.php">
                        <input type="hidden" id="usuario_id" name="usuario_id">
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted">Dejar en blanco para mantener la contraseña actual</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-control" id="rol" name="rol" required>
                                <option value="admin">Administrador</option>
                                <option value="doctor">Doctor</option>
                                <option value="recepcionista">Recepcionista</option>
                                <option value="enfermera">Enfermera</option>
                                <option value="farmaceutico">Farmacéutico</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let usuarioModal;

        document.addEventListener('DOMContentLoaded', function() {
            usuarioModal = new bootstrap.Modal(document.getElementById('usuarioModal'));
        });

        function abrirModal() {
            document.getElementById('usuarioForm').reset();
            document.getElementById('usuario_id').value = '';
            document.getElementById('modalTitle').textContent = 'Nuevo Usuario';
            usuarioModal.show();
        }

        function editarUsuario(id) {
            fetch(`obtener_usuario.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('usuario_id').value = data.id;
                    document.getElementById('username').value = data.username;
                    document.getElementById('nombre').value = data.nombre;
                    document.getElementById('rol').value = data.rol;
                    document.getElementById('estado').value = data.estado;
                    
                    document.getElementById('modalTitle').textContent = 'Editar Usuario';
                    usuarioModal.show();
                });
        }

        function eliminarUsuario(id) {
            if (confirm('¿Está seguro de eliminar este usuario?')) {
                window.location.href = `eliminar_usuario.php?id=${id}`;
            }
        }
    </script>
</body>
</html> 