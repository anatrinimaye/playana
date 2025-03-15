
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
            <div class="aviso mt-2 py-2" >
                <p class="text-center mt-3 not"> NOTA: <br> <strong class="solo1"> Solo los pacientes de nuestra clinica pueden realizar citas</strong> </p>
                <p> </p>
                <p class="text-center"> Si no es paciente, le regamos que realice su primera consuta en la clinica, GRACIAS!! </p>
            </div>
        </div>


            <!-- Formulario -->
        <div class="col-lg-5 formAnt px-5 py-5">
            <form action="">
                    <div class=" ">
                        <input class="form-control" type="text" placeholder="Codigo Paciente">
                        <select class="form-control mt-4" name="" id="">
                            <option >Servicio que necesita</option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                        </select>
                        
                        <input class="form-control mt-4" type="text" placeholder="Fecha y hora preferidas" >
                        <select class="form-control mt-4" name="" id="">
                            <option value="">Médico preferido</option>
                            <option value=""></option>
                            <option value=""></option>
                        </select>
                        <textarea name="mensa" class="form-control mt-4" placeholder="Escriba aqui el motivo de su cita" style="height: 100px; resize:none" required></textarea>
                        <input class="btn mt-4 form-control fw-bold" type="submit" value="Solicitar Cita" style="background-color: #013f6baf; font-size: 18px">
                    
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