<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace touristiamo\helper;

/**
 * Description of GenerateToken
 *
 * @author cristobal
 */
class TokenHelper
{
    
    public static function generate($email, $pass)
    {
        return sha1(time(). $email. sha1($pass));
    }
}
