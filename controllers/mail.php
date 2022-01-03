<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;                                   //Enable SMTP authentication
    $mail->Username = 'merakiphp63@gmail.com';                     //SMTP username
    $mail->Password = 'pruebaMeraki';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('merakiphp63@gmail.com', 'Meraki');
    $mail->addAddress($_SESSION['email'], $_SESSION['user']);

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Bienvenido a Meraki';
    $mail->Body = '<h3>¡Bienvenid@ ' . $_SESSION['user'] . '!</h3><br>' .
        '<p>Gracias por empezar a ser parte de esta comunidad. Esperamos que tengas tantas ganas de aportar información como nosotros de proporcionarla, siempre respetando las normas de Meraki</p>' .
        '<br><p>Saludos,</p><p>el equipo de Meraki</p>';

    $mail->send();
    echo 'Correo enviado con éxito';
} catch (Exception $e) {
    echo "Ha ocurrido un error al enviar el correo: {$mail->ErrorInfo}";
}