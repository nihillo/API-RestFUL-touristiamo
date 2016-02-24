<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace touristiamo\service;

/**
 * Description of GenerateToken
 *
 * @author cristobal
 */
class TokenService
{
    
    public static function generate($email, $pass)
    {
        return sha1(time(). $email. sha1($pass));
    }
}
