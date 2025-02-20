
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="./css/estilos.css">
    <link rel="stylesheet" href="./css/inicio.css">
</head>
<body>
    <?php
        require("./componentes/header.php");
    ?>
       
        <!--BANNER-->
        <div class="container-fluit eslo banner">
            <h1>BIENVENIDO A LA CLINICA <br> PLAyANA</h1>
                <!--Eslogan-->
            <div class="eslogan">
                <h2>La salud no lo es todo pero sin ella, todo lo demas es nada</h2>
            </div>
        </div>

        <!-- HORARIO -->
        <div class="">
            <!-- Texto de bienvenida -->
            <p class="text-center h3"> Bienvenido a Nuestra Clinica</p>
            <p class="text-center mb-5"> Ofrecemos amplios procedimientos médicos a pacientes entrantes y salientes.</p>
            <!-- Horario de Emergencia -->
            <div class="horario d-flex container-fluid">
                <!-- Caja del Horario y de Emergencia  -->
                <div class=" d-flex horari col-8 py-5 px-2">
                    <!-- Horario -->
                    <div class=" col-6 px-5 hora">
                        <div class="container">
                            <p class="h3 text-center ">Horario de apertura</p> 
                        </div> <hr class="text-light">
                        <div class="container">
                            <div>
                                <p>Lunes - Viernes: &nbsp <span>7:00 - 23:00 h</span>  </p>
                                <p>Sábado - Domingo: &nbsp <span>9:00 - 18:00h</span> </p>
                                <p class="h4 text-center festi"> Dia Festivo (Cierre de la clinica) <hr> <span class="text-light "> 8 Diciembre</span> </p>
                            </div>
                        </div>
                    </div>
                    <!-- Emergencia -->
                    <div class=" col-5 px-5">
                        <div class="container">
                            <p class="h3 text-center">Casos de emergencia</p> 
                        </div> <hr class="text-light">
                        <div class="container">
                            <div>
                                <p class="cont">
                                    <i class="fa-solid fa-phone" style="color: rgb(199, 104, 1);"></i> &nbsp
                                    <span> +240 222 546 678</span>
                                </p>
                                <span>Su plan de tratamiento está diseñado para un progreso constante, con cada fase implementada rápidamente.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SOBRE NOSOTROS -->
         <div class="nosotros my-5">
            <p class="text-center h3"> Sobre Nosotros</p> 
         </div>




    
    











<script src="./js/bootstrap.min.js"></script>
<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>