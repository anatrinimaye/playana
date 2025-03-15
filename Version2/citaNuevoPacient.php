
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Paciente Cita</title>
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
    <p class="h2 text-center py-5">Solicitar Cita</p>
    <div class="cajaSolicitar d-flex bg-info container">

        <!-- Formulario -->
        <div class="col-7 formulario bg-primary">
            <form action="" class="col-8 bg-secondary">
                <div class="SolicitarCita bg-success">
                    <!-- Caja De Solicitud -->

                    <div class="bg-warning d-flex solictud">
                        <!-- Datos Personales del Paciente -->
                        <div class="col-7 bg-danger px-3 datPersonales">
                            <p class="h5 text-center">Datos Personales</p>
                            <input class="form-control mt-4" required type="text" placeholder="Nombre Completo">
                            <input class="form-control mt-4" required type="text" placeholder="Fecha de Nacimiento">
                            <input class="form-control mt-4" required type="text" placeholder="DIP">
                            <input class="form-control mt-4" required type="text" placeholder="Direccion">
                            <input class="form-control mt-4" required type="text" placeholder="Telefono">
                            <input class="form-control mt-4" required type="email" placeholder="Correo">
                            <input class="form-control mt-4" required type="text" placeholder="Genero">
                        </div>
                        <!-- Datos de la cita -->
                        <div class="datCita">
                            <select class="form-control mt-4" required name="" id="">
                                <option value="">Servicio que necesita</option>
                                <option value=""></option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                            <input class="form-control mt-4" required type="text" placeholder="Motivo">
                            <input class="form-control mt-4" required type="text" placeholder="Fecha y hora preferidas">
                            <select class="form-control mt-4" required name="" id="">
                                <option value="">Médico preferido</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                            
                        </div>
                        
                    </div>
                    <input class="btn btn-secondary mt-5 form-control" type="button" value="Solicitar Cita">
                
                    
                
                    

            </form>

        </div>
        </div>
 
           <!-- Horario -->
        <div class="col-5 px-5 hora bg-danger">
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