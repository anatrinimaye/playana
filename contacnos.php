
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactnos</title>
    <link rel="stylesheet" href="./css/estilos.css">
    <link rel="stylesheet" href="./css/contactanos.css">
</head>
<body>

    <?php
        require("./componentes/header.php");
    ?>

    <!-- BANNER -->
   
        <div class="container-fluid px-5 banner">
            <div class="container">
            <p class="h1 py-5 text-light">Contáctanos</p>
            </div>
      </div>
        


        <!-- Formulario y contactos -->
         <div class="container d-flex mt-5 px-3 formYcont">
            <!--Caja Contactos -->
            <div class="contactos pt-3 px-4 ">
                <h5 class="mb-4 text-center">En que podemos ayudarte</h5>
                <!-- contactos  -->
                 <div class="contaco">
                    <div class="cadaCont d-flex mb-3 ps-3 pt-2">
                        <i class="fa-solid fa-location-dot fs-5 pt-1 pe-3"></i> Djibloho/Oyala-G.E.</p>
                    </div>
                    <a href="" class="nav-link">
                        <div class="cadaCont d-flex mb-3 ps-3 pt-2">
                            <i class="fa-regular fa-envelope fs-5 pt-1 pe-3"></i> insttic@gmail.com</p>
                        </div>
                    </a>
                   
                    <a href="" class="nav-link">
                        <div class="cadaCont d-flex mb-3 ps-3 pt-2">
                            <i class="fa-solid fa-phone fs-5 pt-1 pe-3"></i> +240 555 777 442</p>
                        </div>
                    </a>
                   
                 </div>
                <hr class="mt-5">
                <!--  redes sociales-->
                    <h6 class="ps-3 pt-1 pb-3">Contacta con nosotros:</h6>
                    <div class="d-flex redes">
                        <a href="#" class=" mr-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class=" mr-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class=" mr-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class=""><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class=""> <i class="fa-brands fa-whatsapp"></i> </a>
                    </div>
            </div>


            <!-- Formulario -->
            <div class="formul py-3 px-3">
                <h3 class="px-5 text-center">Envianos tus mensajes</h3>
                <p class="px-5">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                    Ipsam excepturi earum minus ratione id rem soluta in, ab, 
                    nulla eum corrupti ex cumque vero doloribus sint vitae 
                    natus beatae nihil.
                </p>
                <form action="./coreo.php" method="POST" class="px-5 mt-5">

                        <!-- Asunto y correo -->
                        <div class="d-flex w-100 justify-content-center align-items-center gap-5    ">
                            <!-- Nombre, correo y asunto -->
                                <div class="w-50 inpts">
                                    <input type="text" name="nom" class="form-control me-3" placeholder="Nombre Completo" required>
                                    <input type="email" name="correo" class="form-control mt-5" placeholder="Correo electronico" required>
                                    <input type="text" name="asunt" class="form-control me-3 mt-5" placeholder="Asunto" required>
                                </div>

                                <!-- Mensaje y boton-->
                                <div class="text-right w-50">
                                    <textarea name="mensa" class="form-control" placeholder="Escriba aqui su mensaje" required></textarea>
                                    <div class="d-flex justify-content-center align-items-center">
                                <button type="submit" class="w-100 mt-3 btn text-black fw-bold" name="btnEnviar" style=" background-color: #013f6baf;">ENVIAR CORREO</button>
                        </div>
                                </div>
                            
                        
                        </div>
                   
                </form>
            </div>
            
          
            
         </div>
   
  
       <!-- FOOTER -->
 <div>
  <?php
    require("./componentes/footer.php");
  ?>
 </div>


 <script src="./js/bootstrap.min.js"></script>
<script src="./js/bootstrap.bundle.min.js"></script>
 <script src="./js/sweetalert2.all.js"></script>
    
</body>
</html>