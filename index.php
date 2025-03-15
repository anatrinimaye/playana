
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
    <div>
        <?php
            require("./componentes/header.php");
        ?>
    </div>
    
       
        <!--BANNER-->
        <div class="container-fluit eslo banner">
            <h1>BIENVENIDO A LA CLINICA <br> PLAyANA</h1>
                <!--Eslogan-->
            <div class="eslogan">
                <h2>La salud no lo es todo pero sin ella, todo lo demas es nada</h2>
            </div>
            <!-- Tarjetas -->
            <div class="mt-5 d-flex container tarjetas" >
                <div class="foto">
                    <img src="./img/ofta1.jpg" class="card-img-top" alt="...">
                </div>
                <div class="foto">
                    <img src="./img/ofta4.jpg" class="card-img-top" alt="...">
                </div>
                <div class="foto">
                    <img src="./img/ofta5.jpeg" class="card-img-top" alt="...">
                </div>
            </div>
        </div>

        
        <p class="mt-5 enunciados"> Nuestro Horario </p>
        <!-- HORARIO -->
        <div class="">
            <!-- Horario de Emergencia -->
            <div class="horario d-flex container-fluid mt-5">
                <!-- Caja del Horario y de Emergencia  -->
                <div class=" d-flex horari col-lg-8 col-12">
                    <!-- Horario -->
                    <div class="col-lg-6 col-12 hora">
                        <div class="container">
                            <p class="h3 text-center ">Horario de apertura</p> 
                        </div> <hr class="text-light">
                        <div class="container">
                            <div>
                                <p>Lunes - Viernes: &nbsp <span>7:00 - 23:00 h</span>  </p>
                                <p>Sábado - Domingo: &nbsp <span>9:00 - 18:00h</span> </p>
                                <p class="h5 mt-4 text-center festi">  Dia Festivo (Cierre de la clinica)</p> 
                                <hr class="text-light">
                                <p class="text-center"> 8 Diciembre</p>
                            </div>
                        </div>
                    </div>
                    <!-- Emergencia -->
                    <div class="col-lg-5 col-12 ">
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

         <!-- Texto de bienvenida para los servicios-->
         <p class="mt-5 enunciados"> Nuestros Servicios</p>
            <p class="text-center mb-5"> Ofrecemos amplios procedimientos médicos a pacientes entrantes y salientes.</p>

            <!-- SERVICIOS -->
             <div class="container-fluid cajaServicios ">
                <div class="container d-flex servicios">
                    
                    <!-- Cada Caja -->
                    <div class=" servicio">
                        <div class="foto">
                            <img src="./img/ofta5.jpeg" class="card-img-top" alt="...">
                        </div>
                        <div class="">
                            <h6 class="text-center">Tratamientos de enfermedades oculares</h6>
                            <p class="titul"> 
                                Lorem dolor sit amet sequi ducimus aliquam sequi ducimus?
                            </p>
                            <div class="cajaBtn d-flex">
                                <a class="" href="">Leer Mas</a>
                            </div>
                        </div>
                    </div>

                    <!-- Cada Caja -->
                    <div class=" servicio">
                        <div class="foto">
                            <img src="./img/ofta1.jpg" class="card-img-top" alt="...">
                        </div>
                        <div class="">
                            <h6 class="text-center mb-3">Examenes y Diagnosticos de la vista</h6>
                            <p class="titul">
                                Lorem dolor sit amet sequi ducimus aliquam sequi ducimus?
                            </p>
                            <div class="cajaBtn d-flex ">
                                <a class="" href="">Leer Mas</a>
                            </div>
                        </div>
                    </div>

                    <!-- Cada Caja -->
                    <div class=" servicio" >
                        <div class="foto">
                            <img src="./img/ofta4.jpg" class="card-img-top" alt="...">
                        </div>
                        <div class="">
                            <h6 class="text-center mb-3">Correccion de Errores Refractorios</h6>
                            <p class="titul">
                                Lorem dolor sit amet sequi ducimus aliquam sequi ducimus sequi
                            </p>
                            <div class="cajaBtn d-flex">
                                <a class="" href="">Leer Mas</a>
                            </div>
                        </div>
                    </div>


      

                </div>
             </div>


        <!-- TESTIMONIOS -->
        
        
        <p class="mt-5 enunciados"> Lo Que Dicen Nuestros Pacientes</p>
        <p class="text-center mb-5"> Ofrecemos procedimientos médicos integrales para pacientes que ingresan y salen del país.</p>
        
        

        <div class="cajaTestimonios py-5 d-flex justify-content-center bg-success">
        <div class="cajaGeneralSlider">
            <div class="slider">
                <div class="slide">
                <div class="foto">
                        <img class="mt-3" src="./img/cli1.jpg" alt="Imagen 1">
                    </div>
                    <div class="texto px-5 pt-3">
                        <p class="px-5">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Qui obcaecati laborum vero consequuntur 
                            atque aut unde modi commodi illum blanditiis 
                            dolor velit exercitationem officiis odio delectus hic eveniet, ipsum minus.
                        </p>
                            
                    </div>
                    <p class="text-center py-3 nombre">Anastasia Trinidad</p>
                </div>
                <div class="slide">
                    <div class="foto">
                        <img class="mt-3" src="./img/cli2.jpg" alt="Imagen 1">
                    </div>
                    <div class="texto px-5 pt-3">
                    <p class="px-5">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Qui obcaecati laborum vero consequuntur 
                            atque aut unde modi commodi illum blanditiis 
                            dolor velit exercitationem officiis odio delectus hic eveniet, ipsum minus.
                        </p>
                    </div>
                    <p class="text-center py-3 nombre">Ana Trini Maye</p>
                </div>
                <div class="slide">
                    <div class="foto">
                        <img class="mt-3" src="./img/cli3.jpg" alt="Imagen 1">
                    </div>
                    <div class="texto px-5 pt-3">
                    <p class="px-5">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Qui obcaecati laborum vero consequuntur 
                            atque aut unde modi commodi illum blanditiis 
                            dolor velit exercitationem officiis odio delectus hic eveniet, ipsum minus.
                        </p>
                    </div>
                    <p class="text-center py-3 nombre">Placido Pablo</p>
                </div>
            </div>
            <button class="btnAnterior"><i class="fa-solid fa-angle-left"></i></button>
            <button class="btnSiguiente"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        </div>


   
         <div>
            <?php
                require("./componentes/footer.php")
            ?>
         </div>
    
         <!-- MI SCRIPT PARA EL CARRUSEL -->
        <script>
            const slider = document.querySelector('.slider');
            const slides = document.querySelectorAll('.slide');
            const btnAnterior = document.querySelector('.btnAnterior');
            const btnSiguiente = document.querySelector('.btnSiguiente');

            let counter = 0;
            const slideWidth = slides[0].clientWidth;

            btnSiguiente.addEventListener('click', () => {
                if (counter >= slides.length - 1) return;
                counter++;
                slider.style.transform = `translateX(${-slideWidth * counter}px)`;
            });

            btnAnterior.addEventListener('click', () => {
                if (counter <= 0) return;
                counter--;
                slider.style.transform = `translateX(${-slideWidth * counter}px)`;
            });
        </script>


<script src="./js/bootstrap.min.js"></script>
<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>