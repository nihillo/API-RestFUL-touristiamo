<?php

namespace touristiamo\helper;

use Firebase\JWT\JWT;
use touristiamo\models\UsersModel as UserModel;


/**
 * Description of GenerateToken
 *
 * @author I.E.S Francisco Ayala
 */
class TokenHelper
{
    /**
     * Generate the private token. This token is used to generate the public 
     * token.
     * @param string $email
     * @param string $pass
     * @return string
     */
    public static function generate($email, $pass)
    {
        return sha1(time(). $email. sha1($pass));
    }
    
    /**
     * Generate the public token.
     * @param Object $data
     * @param string $privateToken
     */
    public static function generatePublic($data, $privateToken)
    {
        return JWT::encode($data, $privateToken, APP_HASH);
    }
    
    
    
    /**
     * Get data from the public token.
     * @param string $token
     * @return Json
     */
    public static function getData($token)
    {
        if (!$token)
        {
            return false;
        }
        return json_decode( JWT::urlsafeB64Decode(explode('.', $token)[1]) );
    }
    
    /**
     * Check if the token was modified by a hacker.
     * @param \ArrayIterator $publicToken
     * @return UserModel Return UserModel or throw HttpError and exit.
     */
    public static function checkSign($publicToken)
    {
        if ( !isset($publicToken[0]) )
        {
            \touristiamo\error\HttpError::send(401, 'You must send the Authorization header');
        }
        if ( !($token = $publicToken[0]) || !($user = self::getData($token)) )
        {
            \touristiamo\error\HttpError::send(400, 'The token is incorrect');
        }
        $userModel = new UserModel($user->id);
        try
        {
            JWT::decode($token, $userModel->getToken(), array(APP_HASH));
            return $userModel;
        } catch (\Exception $e)
        {
            \touristiamo\error\HttpError::send(401, 'Token failed: '.$e->getMessage());
        }
    }
}
