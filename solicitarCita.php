<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Cita</title>
    <link rel="stylesheet" href="./css/estilos.css">
    <link rel="stylesheet" href="./css/solicitarCita.css">
</head>
<body>
    <!-- HEADER -->
    <div>
        <?php
            require("./componentes/header.php");
        ?>
    </div>

    <?php
    include "./user/config/conexion.php"; // Asegúrate de que la conexión esté configurada correctamente

    // Consulta para obtener los servicios activos
    $queryServicios = "SELECT * FROM servicios WHERE estado = 'Activo'";
    $resultadoServicios = mysqli_query($conexion, $queryServicios);

    // Consulta para obtener los médicos (tipo = 'doctor')
    $queryMedicos = "SELECT id, nombre FROM personal WHERE tipo = 'doctor'";
    $resultadoMedicos = mysqli_query($conexion, $queryMedicos);

    // Verificar si hay resultados
    $servicios = [];
    if ($resultadoServicios && mysqli_num_rows($resultadoServicios) > 0) {
        $servicios = mysqli_fetch_all($resultadoServicios, MYSQLI_ASSOC); // Obtener todas las filas como un array asociativo
    }

    $medicos = [];
    if ($resultadoMedicos && mysqli_num_rows($resultadoMedicos) > 0) {
        $medicos = mysqli_fetch_all($resultadoMedicos, MYSQLI_ASSOC); // Obtener todas las filas como un array asociativo
    }
    ?>

    <!-- BANNER -->
    <div class="container-fluid px-5 banner">
        <div class="container">
        <p class="h1 pt-4 text-light">Solicitar Cita</p>
        </div>
    </div>

    <div class="cajaSolicitarAnt mt-5 d-flex container p-3">
        <!-- Horario y Aviso-->
        <div class="col-lg-4 horarioYaviso">
            <div class="hora px-5 py-1">
                <p class="h5 text-center">Horario de apertura</p> <hr class="text-light">
                <div>
                    <p class="ps-3">Lunes - Viernes: &nbsp <span>7:00 - 23:00 h</span> </p>
                    <p class="ps-3">Sábado - Domingo: &nbsp <span>9:00 - 18:00h</span> </p>
                    <p class="h5 mt-4 text-center">  Dia Festivo (Cierre de la clinica)</p> 
                    <hr class="text-light">
                    <p class="text-center"> 8 Diciembre</p>
                </div>
            </div>
            <!-- Nota -->
            <div class="aviso mt-2 py-2">
                <p class="text-center mt-3 not"> NOTA: <br> <strong class="solo1"> Solo los pacientes de nuestra clinica pueden realizar citas</strong> </p>
                <p> </p>
                <p class="text-center"> Si no es paciente, le regamos que realice su primera consuta en la clinica, GRACIAS!! </p>
            </div>
        </div>

        <!-- Formulario -->
        <div class="col-lg-5 formAnt px-5 py-5">
            <form action="./guardarCita.php" method="POST">
                <div class="">
                    <!-- Servicios -->
                    <select class="form-control mt-4" name="servicio" required>
                        <option value="" disabled selected>Servicio que necesita</option>
                        <?php foreach ($servicios as $servicio): ?>
                            <option value="<?php echo htmlspecialchars($servicio['nombre']); ?>">
                                <?php echo htmlspecialchars($servicio['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Fecha y hora -->
                    <label class="mt-4">Fecha y hora preferidas:</label>
                    <input class="form-control" type="datetime-local" name="fecha" required>

                    <!-- Médicos -->
                    <select class="form-control mt-4" name="medico_id" required>
                        <option value="" disabled selected>Médico preferido</option>
                        <?php foreach ($medicos as $medico): ?>
                            <option value="<?php echo htmlspecialchars($medico['id']); ?>">
                                <?php echo htmlspecialchars($medico['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Correo -->
                    <input type="email" class="form-control mt-4" placeholder="Su Correo Electrónico" name="correo" required>

                    <!-- Motivo -->
                    <textarea name="motivo" class="form-control mt-4" placeholder="Escriba aquí el motivo de su cita" style="height: 100px; resize:none" required></textarea>

                    <!-- Botón de enviar -->
                    <input name="btnEnviar" class="btn mt-4 form-control fw-bold" type="submit" value="Solicitar Cita" style="background-color: #013f6baf; font-size: 18px">
                </div>
            </form>
        </div>
    </div>

    <!-- FOOTER -->
    <div>
        <?php
            require("./componentes/footer.php")
        ?>
    </div>

    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/sweetalert2.all.js"></script>
</body>
</html>