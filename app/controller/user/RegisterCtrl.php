<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace touristiamo\controller\user;

use touristiamo\models\UsersModel as UserModel;
use touristiamo\service\TokenService as TokenService;
use touristiamo\error\HttpError as HttpError;
use touristiamo\service\EmailServcie as EmailService;

/**
 * Description of RegisterCtrl
 *
 * @author cristobal
 */
class RegisterCtrl extends \touristiamo\Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     * @param \ArrayIterator $args
     */
    public function register($args)
    {        
        // Create model
        $userModel = new UserModel();
        $userModel->email = $args['email'];
        $userModel->pass = sha1($args['pass']);
        $userModel->name = $args['userName'];
        $userModel->token = TokenService::generate($args['email'], $args['pass']);
        
        if ($userModel->save())
        {
            $subject = 'Activate acount';
            $message = '<h1>Welcome to '. APP_NAME. '</h1>';
            $message .= '<p>To finish the register process, click on the link below</p>';
            $message .= '<a href="'. APP_URL. '/users/register/active/'. $userModel->token. '">';
            $message .= APP_URL. '/users/register/active/'. $userModel->token. '</a>';
            EmailService::sendEmail($userModel->email, 
                    $subject, $message, $userModel->name);
        }        
    }
    
    /**
     * 
     * @param String $token
     */
    public function active($token)
    {
        $userModel = new UserModel();
        $userModel->fillByToken($token);
        $userModel->activated = true;
        if ( $userModel->update() )
        {
            echo json_encode([
                'message'   =>  'The user was activated sucessful'
            ]);
        }
    }

}
