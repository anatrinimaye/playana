

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Clínica</title>
    <link rel="stylesheet" href="../../Admin/assets/css/styles.css">
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
                        <a class="nav-link" href="../dashboard.php">
                        <i class="fas fa-home"></i> <span class="nav-text">Inicio</span>
                        </a>
                    </li>


                <li class="nav-item">
                        <a class="nav-link" href="../pacientes/indexpacientes.php">
                            <i class="fas fa-users"></i> Pacientes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../historiales/historiales.php">
                            <i class="fas fa-file-medical"></i> Historiales
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="../horarios/indexhorarios.php">
                            <i class="fas fa-clock"></i> Horarios
                        </a>
                    </li>
                   
                    
                    <li class="nav-item">
                        <a class="nav-link" href="../recetas/indexrecetas.php">
                            <i class="fas fa-file-prescription"></i> Recetas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../servicios/indexservicios.php">
                            <i class="fas fa-file-prescription"></i> Servicios
                        </a>
                    </li>
                    </li>
                </ul>

                <li class="nav-item">
                        <a class="nav-link" href="../../../login.php">
                        <i class="fas fa-sign-in-alt"></i> Cerrar Secion
                        </a>
                    </li>

            </nav>
        </aside>

        <main class="main-content">
            <div class="welcome-section">
            <?php
                include "citas.php";
            ?>
            </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 