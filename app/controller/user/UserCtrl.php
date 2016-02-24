<?php

namespace touristiamo\controller\user;

use touristiamo\error\HttpError as HttpError;
use touristiamo\models\UsersModel as UserModel;
use touristiamo\service\TokenService as TokenService;

/**
 * Description of UserCtrl
 *
 * @author cristobal
 */
class UserCtrl extends \touristiamo\Controller
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * 
     * @param String $email
     * @param String $pass
     */
    public function login($email, $pass)
    {
        $userModel = new UserModel();
        $userModel->fillByEmail($email);
        
        if ($userModel->email === $email && $userModel->pass === sha1($pass))
        {
            echo json_encode([
                'token' => $userModel->token
            ]);
        } else
        {
            HttpError::send(400, 'Email or password incorrect');
        }
    }
    
    public function generateNewToken($oldToken)
    {
        $userModel = new UserModel();
        if ($userModel->fillByToken($oldToken))
        {
            $userModel->token = TokenService::generate($userModel->email, $userModel->pass);
            if ($userModel->save())
            {
                echo json_encode([
                    'token' =>  $userModel->token
                ]);
            } else 
            {
                HttpError::send(400, 'Error with the old token');
            }
        }
    }

    /**
     * 
     * @param Array $args
     */
    public function profile($args)
    {
        HttpError::send(400, 'Prueba de mensaje de error');
    }
}
