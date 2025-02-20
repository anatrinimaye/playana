


<!-- Contactos arriba del header -->
 <div class="container-fluid cajaGeneral py-1 d-flex ">
  
    <!-- Caja contactos -->
    <div class="col-7 contactos d-flex">
        <!-- localizacion -->
        <div class="col-4 ">
                <i class="fa-solid fa-location-dot"></i> &nbsp
                <span> Bata Avnda Plaza de Reloj, G.E</span>
        </div>
        <!-- Telefono -->
        <div class="col-3  ">
                <i class="fa-solid fa-phone"></i> &nbsp
                <span> +240 222 546 678</span>
        </div>
        <!-- Correo -->
        <div class="col-3 ">
                <i class="fa-solid fa-envelope"></i> &nbsp
                <span>playana@gmail.com</span>
        </div>
    </div>
   
    <!-- Login -->
    <div class="col-1 text-end">
        <!-- Button trigger modal -->
        <button type="button" class="btn btnLogin " data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                <i class="fa-solid fa-right-to-bracket"></i>
                <span>Login</span>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <input class="form-control" placeholder="" name="correo" type="email">
                    <input class="form-control" placeholder="" name="pass" type="password">
                    <input class="form-control btn btn-success" value="Acceder" name="" type="submit">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
            </div>
        </div>
        </div>  
        
    </div>
 </div>



 <!-- Header -->
 <div class="row header ">
        <!-- Logo -->
        <div class=" col-lg-3 d-flex logo">
            <p> <i class="fa-solid fa-hospital fs-4 pt-2"></i> </p>
            <p class="h4 nomEm"> <span>PLAyANA</p>
        </div>
        <!-- enlaces -->
        <div class="col-lg-6 d-flex enlaces pe-5">
            <a class="nav-link active" href=""> <span>Inicio</span> </a>
            <a class="nav-link" href=""> <span> Sobre Nosotros</span></a>
            <!-- Servicios -->
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class=" serv"> Servicios</span>   
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">
                        <span>Tratamientos de enfermedades oculares</span> </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                        <span>Correccion de Errores Refractorios</span></a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                        <span> Examenes y Diagnosticos de la vista</span> </a>
                    </li>
                </ul>
            </div>
            <!-- <a class="nav-link" href=""><span>Doctores</span> </a> -->
            <a class="nav-link" href=""><span>Solicitar cita</span> </a>
            <a class="nav-link" href=""><span>Contactanos</span></a>
        </div>
        <!-- Buscador y Boton de cambio de tema -->
        <div class="col-lg-3 d-flex buscYbtncambio">
            <!-- Buscador -->
            <form class="d-flex bg-light " role="search">
                <div class="d-flex buscar">
                    <i class="fa-solid fa-magnifying-glass py-2 ps-2"></i>
                    <input class="form-control bg-light inpt" type="search" placeholder="Buscar..." aria-label="Search">
                </div>
            </form>
            <!-- Boton de cambio de tema -->
            <button class="btncamTema" type="button" onclick="cambiarTema()">
                <i class="fa-solid fa-moon"></i>
            </button>

        </div>
            

 
        
    </div>


    <script>
        function cambiarTema() { 
        document.body.classList.toggle("oscuro"); }
    </script>

    