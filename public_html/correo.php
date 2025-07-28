<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require './librerias/PHPMailer/Exception.php';
    require './librerias/PHPMailer/PHPMailer.php';
    require './librerias/PHPMailer/SMTP.php';

	$mensaje = $_POST['mensaje'];
	$correo = $_POST['correo'];
	$nombre = $_POST['nombre'];

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mail.webfiltros.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'atencion.cliente@webfiltros.com';                     //SMTP username
        $mail->Password   = 'Facil2023';                               //SMTP password
        $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
        $mail->Port       = 465;     


        //Recipients
        $mail->setFrom('atencion.cliente@webfiltros.com', 'WebFiltros Contacto');
        $mail->addAddress('atencion.cliente@webfiltros.com', 'WebFiltros');     //Add a recipient
       
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject =   'WebFiltros Contacto' ;
        $mail->CharSet = 'UTF-8';
        $mail->Body = 'Nombre: ' .$nombre . '<br><br>' .$mensaje .'<br><br>  Correo: ' .$correo ;
       
    
        $mail->send();
        echo json_encode('Exito');
    } catch (Exception $e) {
        echo json_encode("Error: {$mail->ErrorInfo}");
    }

    ?>