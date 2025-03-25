<?php
//session_start();
include '../config/conexion.php';
/*
// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_rol'])) {
    header('Location: ../../../Admin/login.php'); // Redirigir al login si no está logueado
    exit();
}

// Asignar el rol del usuario desde la sesión
$usuario_rol = $_SESSION['usuario_rol'];

// Verificar si el usuario tiene el rol de doctor
if ($usuario_rol !== 'doctor') {
    header('Location: acceso_denegado.php'); // Redirigir si no tiene el rol adecuado
    exit();
}
*/
// Obtener total de pacientes
$query_pacientes = "SELECT COUNT(*) as total FROM pacientes WHERE estado = 'Activo'";
$result_pacientes = mysqli_query($conexion, $query_pacientes);
$total_pacientes = mysqli_fetch_assoc($result_pacientes)['total'];

// Obtener citas del día
$hoy = date('Y-m-d');
$query_citas = "SELECT COUNT(*) as total FROM citas WHERE DATE(fecha_hora) = '$hoy'";
$result_citas = mysqli_query($conexion, $query_citas);
$citas_hoy = mysqli_fetch_assoc($result_citas)['total'];

// Obtener total de médicos activos
$query_medicos = "SELECT COUNT(*) as total FROM personal WHERE tipo = 'Doctor' AND estado = 'Activo'";
$result_medicos = mysqli_query($conexion, $query_medicos);
$total_medicos = mysqli_fetch_assoc($result_medicos)['total'];

// Obtener total de medicamentos
$query_medicamentos = "SELECT COUNT(*) as total FROM medicamentos WHERE estado = 'disponible'";
$result_medicamentos = mysqli_query($conexion, $query_medicamentos);
$total_medicamentos = mysqli_fetch_assoc($result_medicamentos)['total'];

// Datos para el gráfico de citas por mes
$query_grafico_citas = "SELECT 
    MONTH(fecha_hora) as mes,
    COUNT(*) as total
    FROM citas 
    WHERE YEAR(fecha_hora) = YEAR(CURRENT_DATE)
    GROUP BY MONTH(fecha_hora)
    ORDER BY mes";
$result_grafico_citas = mysqli_query($conexion, $query_grafico_citas);
$datos_citas = array_fill(0, 12, 0); // Inicializar array con 12 meses en 0

while ($row = mysqli_fetch_assoc($result_grafico_citas)) {
    $datos_citas[$row['mes']-1] = $row['total'];
}

// Datos para el gráfico de pacientes nuevos por mes
$query_grafico_pacientes = "SELECT 
    MONTH(fecha_registro) as mes,
    COUNT(*) as total
    FROM pacientes 
    WHERE YEAR(fecha_registro) = YEAR(CURRENT_DATE)
    GROUP BY MONTH(fecha_registro)
    ORDER BY mes";
$result_grafico_pacientes = mysqli_query($conexion, $query_grafico_pacientes);
$datos_pacientes = array_fill(0, 12, 0); // Inicializar array con 12 meses en 0

while ($row = mysqli_fetch_assoc($result_grafico_pacientes)) {
    $datos_pacientes[$row['mes']-1] = $row['total'];
}

$contenido_rol = '';
if ($usuario_rol ='') {
    $contenido_rol = '
    <div class="quick-actions">
        <h3>Acciones Rápidas</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="card action-card">
                    <div class="card-body">
                        <h5>Nueva Cita</h5>
                        <button onclick="nuevaCita()" class="btn btn-primary">
                            <i class="fas fa-calendar-plus"></i> Agendar
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card action-card">
                    <div class="card-body">
                        <h5>Registro Paciente</h5>
                        <button onclick="nuevoPaciente()" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Registrar
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card action-card">
                    <div class="card-body">
                        <h5>Pagos Pendientes</h5>
                        <button onclick="verPagos()" class="btn btn-warning">
                            <i class="fas fa-dollar-sign"></i> Ver Pagos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    ';
}

if ($usuario_rol = '') {
    $contenido_rol .= '
    <div class="doctor-dashboard">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5>Próximas Consultas</h5>
                        <div class="consultas-list">
                            <!-- Lista de consultas del día -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Acciones Rápidas</h5>
                        <button onclick="verHistorial()" class="btn btn-info mb-2 w-100">
                            <i class="fas fa-history"></i> Historial Médico
                        </button>
                        <button onclick="nuevaReceta()" class="btn btn-primary mb-2 w-100">
                            <i class="fas fa-prescription"></i> Nueva Receta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    ';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Clínica</title>
    <link rel="stylesheet" href="../Admin/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .main-content {
            flex: 1;
            padding: 2rem;
            margin-left: 280px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            background: white;
            margin-bottom: 1.5rem;
        }
        
        .stats-card {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
        }
        
        .stats-card i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .stats-number {
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .welcome-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card:hover {
            transform: scale(1.05);
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card-body {
            padding: 20px;
        }
        .modal {
            display: none; /* Cambiar a 'block' cuando se abra el modal */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            margin: 15% auto; /* 15% desde la parte superior y centrado */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Ancho del modal */
            max-width: 600px; /* Ancho máximo */
            border-radius: 8px; /* Bordes redondeados */
        }

        .form-group {
            margin-bottom: 15px; /* Espaciado entre campos */
        }

        .form-group label {
            display: block; /* Asegura que las etiquetas estén en su propia línea */
            margin-bottom: 5px; /* Espaciado entre la etiqueta y el campo */
        }

        .form-group input,
        .form-group select {
            width: 100%; /* Ancho completo */
            padding: 10px; /* Espaciado interno */
            border: 1px solid #ccc; /* Borde */
            border-radius: 4px; /* Bordes redondeados */
        }

        .btn-save,
        .btn-cancel {
            padding: 10px 15px; /* Espaciado interno */
            border: none; /* Sin borde */
            border-radius: 4px; /* Bordes redondeados */
            cursor: pointer; /* Cambia el cursor al pasar el mouse */
        }

        .btn-save {
            background-color: #28a745; /* Color verde */
            color: white; /* Texto blanco */
        }

        .btn-cancel {
            background-color: #dc3545; /* Color rojo */
            color: white; /* Texto blanco */
        }

        /* Estilos para el menú lateral */
        .sidebar {
            background-color: #343a40;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .container-fluid {
            margin-left: 20px;
        }
        .chart-container {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h2>Clínica</h2>
            </div>
            <nav class="menu">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="../doctores/pacientes/indexpacientes.php">
                            <i class="fas fa-users"></i> Pacientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./citas/indexcitas.php">
                            <i class="fas fa-calendar-alt"></i> Citas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../doctores/historiales/indexhistoriales.php">
                            <i class="fas fa-file-medical"></i> Historiales
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="../doctores/horarios/indexhorarios.php">
                            <i class="fas fa-clock"></i> Horarios
                        </a>
                    </li>
                   
                    
                    <li class="nav-item">
                        <a class="nav-link" href="../doctores/recetas/indexrecetas.php">
                            <i class="fas fa-file-prescription"></i> Recetas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../doctores/servicios/indexservicios.php">
                            <i class="fas fa-file-prescription"></i> Servicios
                        </a>
                    </li>


                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="welcome-section">
                <h1>Bienvenido al Panel de Doctores</h1>
                <p class="text-muted">Gestiona toda la información de tu clínica desde aquí</p>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card stats-card">
                        <div class="card-body text-center">
                            <i class="fas fa-users"></i>
                            <div class="stats-number"><?php echo $total_pacientes; ?></div>
                            <div class="stats-label">Pacientes Totales</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check"></i>
                            <div class="stats-number"><?php echo $citas_hoy; ?></div>
                            <div class="stats-label">Citas Hoy</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card">
                        <div class="card-body text-center">
                            <i class="fas fa-user-md"></i>
                            <div class="stats-number"><?php echo $total_medicos; ?></div>
                            <div class="stats-label">Médicos Activos</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card">
                        <div class="card-body text-center">
                            <i class="fas fa-pills"></i>
                            <div class="stats-number"><?php echo $total_medicamentos; ?></div>
                            <div class="stats-label">Medicamentos</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pacientes</h5>
                            <p class="card-text">Gestiona la información de los pacientes.</p>
                            <a href="modules/pacientes/pacientes.php" class="btn btn-primary">Ver Pacientes</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Medicamentos</h5>
                            <p class="card-text">Controla el stock de medicamentos.</p>
                            <a href="modules/medicamentos/medicamentos.php" class="btn btn-primary">Ver Medicamentos</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Servicios</h5>
                            <p class="card-text">Administra los servicios ofrecidos.</p>
                            <a href="modules/servicios/servicios.php" class="btn btn-primary">Ver Servicios</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Citas por Mes</h5>
                            <canvas id="citasChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pacientes Nuevos</h5>
                            <canvas id="pacientesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <?php echo $contenido_rol; ?>
        </main>
    </div>

    <!-- Agrega el script al final del body -->
    <script src="./assets/js/dashboard.js"></script>

    <!-- Modal -->
    <div id="personalModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Agregar Personal</h2>
            <form id="personalForm">
                <input type="hidden" id="id" name="id">
                <div class="form-group">
                    <label for="dni">DNI:</label>
                    <input type="text" id="dni" name="dni" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" required>
                </div>
                <div class="form-group">
                    <label for="especialidad_id">Especialidad:</label>
                    <select id="especialidad_id" name="especialidad_id">
                        <option value="">Seleccione una especialidad</option>
                        <!-- Las especialidades se cargarán dinámicamente -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono">
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion">
                </div>
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado" required>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Guardar</button>
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openModal(isEdit = false) {
        const modal = document.getElementById('personalModal');
        const form = document.getElementId('personalForm');
        
        if (!isEdit) {
            document.getElementById('modalTitle').textContent = 'Nuevo Personal';
            form.reset();
            document.getElementById('id').value = '';
        } else {
            document.getElementById('modalTitle').textContent = 'Editar Personal';
            // Cargar datos del personal a editar
        }
        
        modal.style.display = 'block'; // Asegúrate de que el modal se muestre
    }

    function closeModal() {
        document.getElementById('personalModal').style.display = 'none';
        document.getElementById('personalForm').reset();
    }
    </script>

    <script>
        // Gráfico de citas
        const ctxCitas = document.getElementById('citasChart').getContext('2d');
        const citasChart = new Chart(ctxCitas, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                datasets: [{
                    label: 'Citas por Mes',
                    data: <?php echo json_encode($datos_citas); ?>,
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de pacientes
        const ctxPacientes = document.getElementById('pacientesChart').getContext('2d');
        const pacientesChart = new Chart(ctxPacientes, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                datasets: [{
                    label: 'Pacientes Nuevos',
                    data: <?php echo json_encode($datos_pacientes); ?>,
                    fill: false,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>