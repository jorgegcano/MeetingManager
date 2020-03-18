<?php


function enviaMail($para,  $asuntoMensaje, $mensaje)
{

    $mail = new PHPMailer();
    $mail->IsSMTP();   // enviar vÌa SMTP
    $mail->Host = 'mentesinquietas-net.correoseguro.dinaserver.com';
    $mail->SMTPAuth = true; // activar la identificacÌn SMTP
    $mail->Username = 'ciberkaos@mentesinquietas.net'; // usuario SMTP
    $mail->Password = 'Jorge2019'; // clave SMTP
    $mail->From = "imfgestionreuninones@gmail.com"; //remitente
    $mail->FromName = "Sistema de administración de reuniones";//nombre de remitente
    //$mail->AddReplyTo(AppEmailSoporte); //e-mail para respuestas
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    //$mail->SMTPSecure = 'ssl';
    $mail->CharSet = 'UTF-8';
    //Destinatarios
    $mail->AddAddress($para); //destinatario

    // Establecemos los par·metros del mensaje: ancho y formato.
    $mail->WordWrap = 50; // ancho del mensaje
    $mail->IsHTML(true); // enviar como HTML

    $mail->Subject = $asuntoMensaje; //Asunto

    $mail->Body = $mensaje; //Cuerpo
    //$mail->AltBody  =  $Mensaje; //Cabecera

    // AÒadimos los adjuntos al mensaje
    //        $mail->AddAttachment("./Manifiestos/".$nombreFichero .".txt"); // podemos aÒadir un adjunto directamente

    if ($mail->Send()){
        //bien
    }else{
        //mal
    }

}
