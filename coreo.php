<?php

if (isset($_POST['btnEnviar'])) {

    $nombre = $_POST['nom'];
    $asunto = $_POST['asunt'];
    $correo = $_POST['correo'];
    $mensaje = $_POST['mensa'];


    $destinatario = "playanamail@gmail.com";


    $cuerpo = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>
        <body>
         <div style="width: 50%; border: 1px solid gray; margin-left: 300px; padding: 10px; height: auto; font-family: cambria;">
                
            <!-- Logo -->
                <div style="  width: 80px; height: 80px; margin-left: 250px">
                    <img style="border-radius: 50%;" src="https://i.pinimg.com/474x/14/9a/e2/149ae205811b3c624c15e3d3456b5a05.jpg"
                        width="100%" height="100%" alt="">
                </div>
                
                <!-- TEXTO DE BIENVENIDA -->
                <p style="text-align: center; font-size: 20px; color: balck"> Bienvenido a PLAyANA Sr/Sra <strong>Ana trini</strong>  
                </p>
                    
                
                <!-- MENSAJE -->
                <div style="width: 100%;">
                    <p style="padding: 20px 17px; text-align: center; font-size: 18px">
                        ' . $mensaje . '
                    </p>
                </div>

                <div style="width: 100%; height: 110px; padding: 2px; height: auto; font-family: cambria; background-color:rgba(6, 125, 139, 0.67)">
                <p style=" text-align: center; font-size: 15px; ">  Copyright ©2025 Todos los derechos reservados por PLAyANA</p>
            </div>
               
            </div>
        </body>
        </html> ';


    $header = "MIME-Version: 1.0\r\n";

    $header .= "Content-type: text/html; charset=UTF-8\r\n";
    // $header .= "From" ."<".$miCorreo.">";

    $email = mail($destinatario, $asunto, $cuerpo, $header);
    if ($email) {
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
    <script src="./js/sweetalert2.all.min.js"></script>
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