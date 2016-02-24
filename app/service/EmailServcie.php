<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace touristiamo\service;

/**
 * Description of EmailServcie
 *
 * @author cristobal
 */
class EmailServcie
{
    /**
     * 
     * This method send an email through SMTP
     * 
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param string $userName
     */
    public static function sendEmail($to, $subject, $message, $userName)
    {
        $mail = new \PHPMailer();
        //indico a la clase que use SMTP
        $mail->isSMTP();
        //permite modo debug para ver mensajes de las cosas que van ocurriendo
        $mail->SMTPDebug = APP_EMAIL_DEBUG_MODE;
        $mail->CharSet = 'UTF-8';
        //Debo de hacer autenticación SMTP
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = APP_EMAIL_ENCRYPT;
        //indico el servidor de Gmail para SMTP
        $mail->Host = APP_EMAIL_HOST;
        //indico el puerto que usa Gmail
        $mail->Port = APP_EMAIL_PORT;
        //indico un usuario / clave de un usuario de gmail
        $mail->Username = APP_EMAIL;
        $mail->Password = APP_CLAVE;
        $mail->SetFrom(APP_EMAIL, APP_NAME);
        $mail->AddReplyTo(APP_EMAIL, APP_NAME);
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        //indico destinatario
        $mail->AddAddress($to, $userName);
        if (!$mail->Send())
        {
            HttpError::send(500, $mail->ErrorInfo);
        } else
        {
            exit( json_encode([
                'message'   =>  'Mail sent succesful.'
            ]) );
        }
    }
}
