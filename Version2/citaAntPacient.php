
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antiguo paciente Cita</title>
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

    <!-- CUERPO -->
    <div class="cajaSolicitar container p-3">
        <p class="h2 text-center mb-5">Solicitar Cita</p>

        <form action="">
            <div class="SolicitarCita d-flex justify-content-center align-items-center gap-5">
                <!-- Datos de la cita -->
                <div class="w-50 px-3 py-3">
                    <input class="form-control" type="text" placeholder="Codigo Paciente">
                    <select class="form-control mt-4" name="" id="">
                        <option value="">Servicio que necesita</option>
                        <option value=""></option>
                        <option value=""></option>
                        <option value=""></option>
                    </select>
                    
                    <input class="form-control mt-4" type="text" placeholder="Fecha y hora preferidas">
                    <select class="form-control mt-4" name="" id="">
                        <option value="">Médico preferido</option>
                        <option value=""></option>
                        <option value=""></option>
                    </select>
                    <textarea name="mensa" class="form-control mt-3" placeholder="Escriba aqui el motivo de su cita" style="height: 100px ; resize:none" required></textarea>
                    <input class="btn btn-secondary mt-5 form-control" type="button" value="Solicitar Cita">
                   
                </div>
                

            </div>
            

        </form>
        <div class=" col-4 px-5 hora">
                        <div class="container">
                            <p class="h5 text-center ">Horario de apertura</p> 
                        </div> <hr class="text-light">
                        <div class="container">
                            <div>
                                <p>Lunes - Viernes: &nbsp <span>7:00 - 23:00 h</span>  </p>
                                <p>Sábado - Domingo: &nbsp <span>9:00 - 18:00h</span> </p>
                                <p class="h5 text-center festi"> Dia Festivo (Cierre de la clinica) <hr> <span class="text-light "> 8 Diciembre</span> </p>
                            </div>
                        </div>
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