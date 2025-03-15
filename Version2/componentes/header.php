


    <div class="offcanvas offcanvas-end bg-light" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title ps-3" id="staticBackdropLabel"> 
                <p> <i class="fa-solid fa-hospital fs-4 pt-2"></i> <span>PLAyANA</p> </p>
            <p class="h4 nomEm"> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <a class="nav-link active" href="./index.php"> <span>Inicio</span> </a>
            <a class="nav-link" href="./sobrenosotros.php"> <span> Sobre Nosotros</span></a>
            <a class="nav-link" href="./servicios.php"> <span> Servicios</span></a>
            <!-- Solicitar Cita -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Solicitar Cita
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="./citaNuevoPacient.php">Nuevo Paciente</a></li>
                    <li><a class="dropdown-item" href="./citaAntPacient.php">Antiguo Paciente</a></li>
                </ul>
            </div>


            <a class="nav-link" href="./contacnos.php"><span>Contactanos</span></a>
        </div>
    </div>


    <!-- Contactos arriba del header -->
    <div class="container-fluid cajaGeneral py-1 d-flex ">
    
        <!-- Caja contactos -->
        <div class="col-7 contactos d-flex">
            <!-- localizacion -->
            <div class="col-4 ">
                    <i class="fa-solid fa-location-dot"></i> &nbsp
                    <span class=""> Bata Avnda Plaza de Reloj, G.E</span>
            </div>
            <!-- Telefono -->
            <div class="col-3 d-flex">
                    <i class="fa-solid fa-phone mt-1"></i> &nbsp
                    <a href="" class="nav-link"> +240 222 546 678</a>
            </div>
            <!-- Correo -->
            <div class="col-3 d-flex">
                    <i class="fa-solid fa-envelope mt-2"></i> &nbsp
                    <a href="" class="nav-link">playana@gmail.com</a>
            </div>
        </div>
    
        <!-- Login -->
        <div class="col-1 text-end">
            <a href=""><i class="fa-solid fa-user text-secondary"></i></a>
            
        </div>
    </div>



 <!-- Header -->
 <div class="header d-flex">
        <!-- Logo -->
        <div class=" col-lg-3 col-6 d-flex logo">
            <p> <i class="fa-solid fa-hospital fs-4 pt-2"></i> </p>
            <p class="h4 nomEm"> <span>PLAyANA</p>
        </div>
        <!-- enlaces -->
        <div class="col-lg-9 d-flex enlaces pe-5">
            <a class="nav-link active" href="./index.php"> <span>Inicio</span> </a>
            <a class="nav-link" href="./sobrenosotros.php"> <span> Sobre Nosotros</span></a>
            <a class="nav-link" href="./servicios.php"> <span> Servicios</span></a>
           <!-- Solicitar Cita -->
           <div class="dropdown">
                <button class="btn btn-ligth dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Solicitar Cita
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="./citaNuevoPacient.php">Nuevo Paciente</a></li>
                    <li><a class="dropdown-item" href="./citaAntPacient.php">Antiguo Paciente</a></li>
                </ul>
            </div>
            <a class="nav-link" href="./contacnos.php"><span>Contactanos</span></a>
        </div>
        <!-- Menu Burger -->
         <div>
            <button class="btn btnMenu d-flex d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                <i class="fa-solid fa-bars"></i>
            </button>
         </div>
       

        <!-- Buscador y Boton de cambio de tema -->
        <div class="col-lg-3 d-flex buscYbtncambio">
            <!-- Buscador -->
            <!-- <form class="d-flex bg-light " role="search">
                <div class="d-flex buscar">
                    <i class="fa-solid fa-magnifying-glass py-2 ps-2"></i>
                    <input class="form-control bg-light inpt" type="search" placeholder="Buscar..." aria-label="Search">
                </div>
            </form> -->

        </div>
            

 
        
</div>


    