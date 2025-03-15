
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
    <!-- BANNER -->
    <div class="container-fluid px-5 banner">
        <div class="container">
        <p class="h1 pt-4 text-light">Solicitar Cita</p>
        </div>
    </div>

    <p class="h2 text-center mt-3 mb-5 enunciados">Nuevo Paciente</p>
    <div class="cajaSolicitarNew d-flex container">

        <!-- Formulario -->
        <div class="col-7 formulario">
            <form action="">
                <div class="SolicitarCitaNew px-3">

                    <!-- Caja De Solicitud -->
                    <div class=" d-flex solictud">
                        <!-- Datos Personales del Paciente -->
                        <div class="col-lg-7 px-3 datPersonales">
                            <p class="h5 text-center fw-bold pt-3">Datos Personales</p>
                            <input class="form-control mt-4" required type="text" placeholder="Nombre Completo">
                            <label class="mt-3 ms-1">Fecha de Nacimiento</label>
                            <input class="form-control" required type="date">
                            <input class="form-control mt-4" required type="text" placeholder="DIP/PASAPORTE">
                            <input class="form-control mt-4" required type="text" placeholder="Direccion">
                            <input class="form-control mt-4" required type="text" placeholder="Telefono">
                            <input class="form-control mt-4" required type="email" placeholder="Correo">
                            <input class="form-control mt-4" required type="text" placeholder="Genero">
                        </div>
                        <!-- Datos de la cita -->
                        <div class="col-lg-4 datCita">
                            <p class="h5 text-center fw-bold">Datos de la cita</p>
                            <select class="form-control mt-4" name="" id="" required>
                                <option value="">Servicio que necesita</option>
                                <option value=""></option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                            
                            <input class="form-control mt-4" required type="text" placeholder="Fecha y hora preferidas">
                            <select class="form-control mt-4" required name="" id="">
                                <option value="">Médico preferido</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                            <textarea name="mensa" class="form-control mt-4" placeholder="Escriba aqui el motivo de su cita" style="height: 100px; resize:none" required></textarea>  
                        </div>
                    </div>
                    <input class="btn mt-4 form-control text-light" type="submit" value="Solicitar Cita" style="background-color: #013f6baf; font-size: 18px">
                </div>   
            </form>
        </div>
         <!-- Horario -->
         <div class="col-lg-4 px-5 hora py-3 ">
            <p class="h5 text-center">Horario de apertura</p> <hr class="text-light">
            <div>
                <p class="ps-3">Lunes - Viernes: &nbsp <span>7:00 - 23:00 h</span> </p>
                <p class="ps-3">Sábado - Domingo: &nbsp <span>9:00 - 18:00h</span> </p>
                <p class="h5 mt-5 text-center">  Dia Festivo (Cierre de la clinica)</p> 
                <hr class="text-light">
                <p class="text-center"> 8 Diciembre</p>
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