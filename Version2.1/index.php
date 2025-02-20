<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/inicio.css">
</head>
<body>
    <!-- HEADER -->
     <div class="container-fliut">
     <nav class="navbar navbar-expand-lg px-3 py-3 header">
            <!--Logo PLAyANA-->
        <div class="navbar pe-2 logo">
            <img src="./IMG/logoOro.jpg"  class="" alt="">
        </div>
            <!-- Buscador -->
        <div class="buscador ms-5">
                <i class=" fa-solid fa-magnifying-glass"></i>
            <div class="busca">   
                <input class="pt-2" type="search" placeholder="Buscar servicios...">
            </div>
        </div>
        <!-- Boton de la amburguesa -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
            <!-- Caja de los enlaces para la navegacion -->
        <div class="collapse ps-5 navbar-collapse enlaces" id="navbarSupportedContent">
            
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!--Menu Desplegable de Sobre Nosotros-->
                <div class="ps-5 dropdown">
                        <button class="btn dropdown-toggle servis " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user-group"></i> <label for="">Sobre Nosotros</label>
                        </button>
                        <ul class="dropdown-menu ulu">
                            <li><button class="dropdown-item" type="button"><a href="./formAdmin.html"><i class="fa-solid fa-lock"></i> Quienes Somos</a></button></li>
                            <li><button class="dropdown-item" type="button"><a href="./formservis.html"><i class="fa-solid fa-capsules"></i> Nuestra Historia</a></button></li>
                            <li><button class="dropdown-item" type="button"><a href="./formservis.html"><i class="fa-solid fa-user-nurse"></i> Nuestra Vision</a></button></li>
                           
                        </ul>
                    </div>
                <li class="nav-item ps-5"> 
                    <a href="./index.php #docto"><i class="fa-solid fa-user-doctor"></i> Doctores</a>
                </li>
                <li class="nav-item ps-5"> 
                    <a href="./index.php #docto"><i class="fa-solid fa-user-doctor"></i> Solicitar cita</a>
                </li>

                    <!--Menu Desplegable de los Servicios de la empresa-->
                    <div class="ps-5 dropdown">
                        <button class="btn dropdown-toggle servis " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-gear"></i> <label>Servicios</label>
                        </button>
                        <ol class="dropdown-menu ulu">
                            <li><button class="dropdown-item" type="button"><a href="./formAdmin.html"><i class="fa-solid fa-lock"></i> Tratamientos de enfermedades oculares</a></button></li>
                            <li><button class="dropdown-item" type="button"><a href="./formservis.html"><i class="fa-solid fa-capsules"></i> Examenes y Diagnosticos de la vista</a></button></li>
                            <li><button class="dropdown-item" type="button"><a href="./formservis.html"><i class="fa-solid fa-user-nurse"></i> Correccion de Errores Refractorios</a></button></li>
                           
                        </ol>
                    </div>
                
                    <!--Menu Desplegable de los Empleados de la empresa-->
                    <div class="ps-5 dropdown">
                        <button class="btn dropdown-toggle servis " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-gear"></i>
                        </button>
                        <ul class="dropdown-menu ulu">
                            <li><button class="dropdown-item" type="button"><a href="./formAdmin.html"><i class="fa-solid fa-lock"></i> Recepcionista</a></button></li>
                            <li><button class="dropdown-item" type="button"><a href="./formservis.html"><i class="fa-solid fa-capsules"></i> Farmaceutico</a></button></li>
                            <li><button class="dropdown-item" type="button"><a href="./formservis.html"><i class="fa-solid fa-user-nurse"></i> Enfermero</a></button></li>
                            <li><button class="dropdown-item" type="button"><a href="./formDoct.html"><i class="fa-solid fa-user-doctor"></i> Doctor</a></button></li>
                            <li><button class="dropdown-item" type="button"><a href="./Admin//php/plantAdmin.php" class="enContactos"><i class="fa-solid fa-user-gear"></i> Admin</a></button></li>
                        </ul>
                    </div>
                    
            </ul>
            </div>
             
        </div>
</nav>
     </div>
    
    <!-- CUERPO -->
    <div class="container-fluit">
            <!--BANNER-->
        <div class="container-fluit eslo  p-5 banner">
            <h1>BIENVENIDO A LA CLINICA <br> PLAyANA</h1>
                <!--Eslogan-->
            <div class="eslogan">
                <h2>La salud no lo es todo pero sin ella, todo lo demas es nada</h2>
            </div>
        </div>
    </div>
    
<!--INFO-->
    
<div class="todo">

<!--Sobre Nosotros-->
<h1 id="nos" class="mb-5">SOBRE NOSOTROS</h1>
<div id="nosotros" class="container w-75 py-2 px-3 nos">
    <div class="container fot">
        <img src="./IMG/doc3.jpg" alt="">
    </div>
    <div class="py-5 text">
        <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. 
            Inventore illum totam quod ea sed incidunt, iusto quaerat nam sequi dignissimos,
            delectus temporibus 
            commodi asperiores soluta provident reiciendis pariatur reprehenderit aperiam?
            Lorem ipsum dolor sit amet consectetur adipisicing elit. 
            Inventore illum totam quod ea sed incidunt, iusto quaerat nam sequi dignissimos,
            delectus temporibus 
            commodi asperiores soluta provident reiciendis pariatur reprehenderit aperiam?
        </p>
    </div> 
</div>

<!--Servicios-->
<h1 class="mb-5 mt-4" id="servis">SERVICIOS</h1>
<div id="servicios" class="container servicios">
    <div class="container">
        
    </div>
    <div class="container">
        
    </div>
    <div class="container">
        
    </div>
</div>

<!--Doctores-->
<h1 class="mb-5 mt-4" id="docto">DOCTORES</h1>
<div class="">
    <div class="container w-50 doctores" id="doc">
        
    </div>
</div>

<!--Horario y la Nota-->
<h1 class="mb-5 mt-4" id="horario">HORARIO:</h1>
<div class="container w-50 dere">
    <div class="horario">
       <p>LUNES - VIERNES: 7:00 - 20:00h <br> SABADOS Y DOMINGOS: 9:00 - 19:00h</p>
    </div>  
        <p id="not">NOTA: En caso de alguna emergencia contacte con su médico cabecera</p>
</div>

<!--FOOTER-->
<div class="footer">
<!--Iconos de contactos-->
<h1 class="mb-5 mt-4" id="contac">CONTACTOS</h1>
<div class="container-fluit py-3 px-5 cont">
    <div class="txt">
        <p class="text-center">
            <span>LOCALIZACION: </span> 
            BATA-LITORAL ( Avda PLAZA-RELOJ )
        </p>
    </div>
    <div class="w-75 icon">
        <a class="ml-2" href=""><i class="fa-solid fa-phone"></i> +240333219976</a>
        <a class="ml-2" href=""><i class="fa-brands fa-linkedin-in"></i> playana@gamil.com</a>
        <a class="ml-2" href=""><i class="fa-brands fa-tiktok"></i> playana@gamil.com</a>
        <a class="ml-2" href=""><i class="fa-brands fa-square-instagram"></i> playana</a>
    </div>
    
    
        
</div>
</div>

</div>
<script src="./js/bootstrap.bundle.min.js"></script>

</body>
</html>