<?php

/**
 * Description of HttpError
 *
 * @author I.E.S AYALA
 */

namespace touristiamo\error;


class HttpError
{
    /**
     * Método encargado de enviar errores al cliente a través de JSON y 
     * el código HTTP.
     * 
     * @param Integer $errorCode
     * @param String $message
     * @return boolean
     */
    public static function send($errorCode, $message)
    {
        http_response_code($errorCode);
        exit(
            json_encode(
                array(
                    'message'   =>  $message
                )
            )    
        );
        return true;
    }
}
