<?php
//session_start();
include '../../config/conexion.php';

// Verificar permisos
/*if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header('Location: ../../login.php');
    exit();
}*/

// Verificar la conexión a la base de datos
if (!$conexion) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

// Mostrar la tabla de usuarios
$query = "SELECT id, email, nombre, rol FROM usuarios";
$result = mysqli_query($conexion, $query);

if (!$result) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}
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
            <?php
            $sidebar_path = '../../includes/sidebar.php';
            if (file_exists($sidebar_path)) {
                include $sidebar_path;
            } else {
                echo "<div class='alert alert-warning'>El archivo sidebar.php no se encontró.</div>";
            }
            ?>

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
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($usuario = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $usuario['id']; ?></td>
                                <td><?php echo $usuario['email']; ?></td>
                                <td><?php echo $usuario['nombre']; ?></td>
                                <td><?php echo ucfirst($usuario['rol']); ?></td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm" onclick="editarUsuario(<?php echo $usuario['id']; ?>)" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
                    <?php include 'registro.php'; ?> <!-- Incluir el formulario de registro -->
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
            // Restablecer el formulario
            document.getElementById('usuarioForm').reset();
            document.getElementById('usuario_id').value = '';
            document.getElementById('fecha_creacion').value = ''; // Limpiar la fecha de creación
            document.getElementById('modalTitle').textContent = 'Nuevo Usuario';
            usuarioModal.show();
        }

        function editarUsuario(id) {
            // Obtener los datos del usuario y rellenar el formulario
            fetch(`obtener_usuario.php?id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener los datos del usuario');
                    }
                    return response.json();
                })
                .then(data => {
                    // Rellenar los campos del formulario con los datos del usuario
                    document.getElementById('usuario_id').value = data.id;
                    document.getElementById('nombre').value = data.nombre;
                    document.getElementById('email').value = data.email;
                    document.getElementById('password').value = ''; // No mostrar la contraseña
                    document.getElementById('fecha_creacion').value = data.fecha_creacion;
                    document.getElementById('rol').value = data.rol;

                    // Cambiar el título del modal
                    document.getElementById('modalTitle').textContent = 'Editar Usuario';

                    // Mostrar el modal
                    usuarioModal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('No se pudo cargar la información del usuario.');
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