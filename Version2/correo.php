
<?php

if(isset($_POST['btnEnviar'])){

    $nombre= $_POST['nom'];
    $apellidos= $_POST['apel'];
    $destinatario= "daw548071@gmail.com";
    $miCorreo= $_POST['correo'];
    $asunto= $_POST['asunt'];
    $mensaje= $_POST['mensa'];

    $cuerpo='
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Correo</title>
        </head>
        <body>
       

        <!-- CUERPO -->
            <div style="width: 50%; margin-left: 300px; padding: 10px; height: auto; font-family: cambria;">
                
            <!-- Logo -->
                <div style="background-color: green; width: 160px; height: 150px; margin-left: 250px">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQq_WEc_J9z_1ZlmU9fCYqTQDzK6xxZRdjzxw&s"
                        width="100%" height="100%" alt="">
                </div>
                
                
                <!-- DESCRIPCION DEL NOMBRE DEL INSTITUTO -->
                    <h5 style="color: #0A2A66; text-align: center"> INSTITUTO SUPERIOR DE TELECOMUNICACIONES TECNOLOGIAS <br> DE INFORMACION Y COMUNICACION</h5>
                <br>
                <!-- TEXTO DE BIENVENIDA -->
                <p style="text-align: center; font-weight: 600; font-size: 20px; font-style: Italic">Bienvenido al correo del INSTTIC Sr/Sra ' . $nombre .' '.$apellidos .'</p>
                    
                
                <!-- MENSAJE -->
                <div style="width: 100%;">
                    <p style="padding: 20px 17px; text-align: center; font-size: 18px">
                        '.$mensaje.'
                    </p>
                </div>
               
            </div>
             <!-- FOOTER -->
             <div style="width: 70%; height: 110px; padding: 2px; height: auto;  margin-left: 170px; font-family: cambria; background-color:rgba(19, 19, 20, 0.35)">
                <p style=" text-align: center; font-size: 20px"> <span style="color: #0A2A66; font-weight: 600; font-size: 20px">INSTTIC</span> <br> <br> Copyright ©2025 Todos los derechos reservados</p>
            </div>
        </body>
        </html> ';


    $header = "MIME-Version: 1.0\r\n";

    $header .="Content-type: text/html; charset=UTF-8\r\n";


    $email = mail($destinatario, $asunto, $cuerpo, $header);
    if($email){
        $mensaje_alerta = '¡Correo enviado exitosamente!';
    $icono_alerta = 'success';
    } else {
    $mensaje_alerta = 'No se pudo enviar el correo, intentelo de nuevo';
    $icono_alerta = 'error';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="">
</head>
<body>
<script src="../recursos/sweetalert2.all.min.js"></script>
    <script>
        Swal.fire({
            title: '<?php echo $mensaje_alerta; ?>',
            icon: '<?php echo $icono_alerta; ?>',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = './index.php';
        });
    </script>
   
</body>
</html>