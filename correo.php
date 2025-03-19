
<?php

if(isset($_POST['btnEnviar'])){

    $codigo = $_POST['cod'];
    $servicio = $_POST['servicio'];
    $fecha = $_POST['fecha'];
    $medico =  $_POST['medico'];
    $motivo =  $_POST['motivo'];
    $miCorreo= $_POST['correo'];

    $destinatario= "playanamail@gmail.com";
    
    $asunto = "Solicitar Cita";

    $cuerpo='
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>
        <body>
                <div style=" border: 2px solid gray; padding: 0px 30px 30px 30px; width: 600px; margin-left: 350px; margin-top: 50px; font-family: cambria">
                <p style="text-align: center; font-weight: bold ; color: blue; margin-bottom: 50px; font-size: 40px">Solicitar Cita</p>

                <label style="font-weight: bold">Codigo Paciente:</label>
                <div style="width: 100%; margin-bottom: 20px; height: 20px; padding: 5px; border: 1px solid gray; ">'.$codigo .'</div>

                <label style="font-weight: bold">Servicio que necesita:</label>
                <div style="width: 100%; margin-bottom: 20px; height: 20px; padding: 5px; border: 1px solid gray; "> ' .$servcico.' </div>

                <label style="font-weight: bold">Fecha y Hora preferida:</label>
                <div style="width: 100%; margin-bottom: 20px; height: 20px; padding: 5px; border: 1px solid gray; "> ' .$fecha .'</div>

                <label style="font-weight: bold">Medico Preferido:</label>
                <div style="width: 100%; margin-bottom: 20px; height: 20px; padding: 5px; border: 1px solid gray; "> ' .$medico .'</div>

                <label style="font-weight: bold">Motivo de la cita</label>
                <div style="width: 100%; margin-bottom: 20px; padding: 5px; border:1px solid gray; height: 100px; resize:none"> '.$motivo .'</div>
            </div>
        </body>
        </html> ';


    $header = "MIME-Version: 1.0\r\n";

    $header .="Content-type: text/html; charset=UTF-8\r\n";
    // $header .= "From" ."<".$miCorreo.">";

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
<script src="./js/sweetalert2.all.min.js"></script>
    <script>
        Swal.fire({
            title: '<?php echo $mensaje_alerta; ?>',
            icon: '<?php echo $icono_alerta; ?>',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = '../index.php';
        });
    </script>
   
</body>
</html>