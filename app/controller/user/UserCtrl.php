<?php

namespace touristiamo\controller\user;

use touristiamo\error\HttpError as HttpError;
use touristiamo\models\UsersModel as UserModel;
use touristiamo\models\CommentModel as CommentModel;
use touristiamo\helper\TokenHelper as TokenHelper;
use touristiamo\exception\BDException as BDException;
use touristiamo\helper\UserHelper as UserHelper;
use touristiamo\service\EmailServcie as EmailService;
use touristiamo\View as Json;


/**
 * Controller for access and user actions
 *
 * @author I.E.S Francisco Ayala
 */
class UserCtrl
{    
    /**
     * This method is used to login users and returns their tokens.
     * @param String $email
     * @param String $pass
     * @return Json
     */
    public static function login($args)
    {
        if (empty($args['email']) || empty($args['password']))
        {
            HttpError::send(400, 'You must type email and password');
        }
        $userModel = new UserModel();
        if (!$userModel->fillByEmail($args['email']))
        {
            HttpError::send(400, 'The user '. $args['email']. ' not exist.');
        }
        if (!UserHelper::isActive($userModel->id) || UserHelper::isBanned($userModel->id))
        {
            HttpError::send(400, 'The user '. $userModel->email. ' is not activated or was banned');
        }
        if ($userModel->email === $args['email'] && $userModel->pass === sha1($args['password']))
        {
            $json = new Json();
            $json->name = $userModel->name;
            $json->token = $userModel->token;
            return $json->render();
        } else
        {
            HttpError::send(400, 'Email or password incorrect');
        }
    }
    
    /**
     * This method generates a new token for the user.
     * @param \ArrayIterator $args
     * @return Json
     */
    public static function generateNewToken($args)
    {
        if (empty($args['token']))
        {
            HttpError::send(400, 'You must type the token');
        }
        $userModel = new UserModel();
        if ($userModel->fillByToken($args['token']))
        {
            if (!UserHelper::isActive($userModel->id) || UserHelper::isBanned($userModel->id))
            {
                HttpError::send(400, 'The user '. $userModel->email. ' is not activated or was banned');
            }
            $userModel->token = TokenHelper::generate($userModel->email, $userModel->pass);
            if ($userModel->update())
            {
                $json = new Json();
                $json->token = $userModel->token;
                return $json->render();
            } 
        } else 
        {
            HttpError::send(400, 'The token is incorrect');
        }
    }

    /**
     * This method updates the information of the user.
     * @param \ArrayIterator $args
     * @return Json
     */
    public static function updateInformation($token, $args)
    {
        $userModel = new UserModel();
        if (!$userModel->fillByToken($token))
        {
            HttpError::send(400, 'The token is incorrect');
        }
        if (!UserHelper::isActive($userModel->id) || UserHelper::isBanned($userModel->id))
        {
            HttpError::send(400, 'The user '. $userModel->email. ' is not activated or was banned');
        }
        if (isset($args['name'])) {
            $userModel->name = htmlentities(trim($args['name']) , ENT_QUOTES);
        }
        
        if (isset($args['password']))
        {
            $userModel->pass = sha1($args['password']);
        }
        
        try
        {
            if ($userModel->update())
            {
                $json = new Json();
                $json->message = "Ther user's information was updated successfully";
                $json->render();
            }
        } catch (BDException $e)
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
    
    /**
     * Delete the user from the data base.
     * @param string $token
     * @return Json
     */
    public static function disable($token)
    {
        $userModel = new UserModel();
        if (!$userModel->fillByToken($token))
        {
            HttpError::send(400, 'The token is incorrect');
        }
        
        try
        {
            $userModel->activated = false;
            if ($userModel->update())
            {
                $json = new Json();
                $json->message = 'The user was disabled successfuly';
                
                $subject = 'Delete acount';
                $message = '<h1>'. APP_NAME. '</h1>';
                $message .= '<p>The account that you have in '. APP_NAME. ' have been deleted successfuly</p>';
                $message .= '<p>Thank you for your visit</p>';
                $json->mailSent = (EmailService::sendEmail($userModel->email, $subject, 
                        $message, $userModel->name)) ? true : false;
                
                return $json->render();
            }
        } catch (BDException $e)
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
    
    /**
     * Get all comments by user
     * @param string $token
     * @return Json
     */
    public static function getAllComments($token)
    {
        $userModel = new UserModel();
        
        if (!$userModel->fillByToken($token))
        {
            HttpError::send(400, 'The token is incorrect');
        }
        
        $json = new Json();
        
        $commentModel = new CommentModel();
        if ( !($json->comments = $commentModel->getAllByUserId($userModel->id)) )
        {
            HttpError::send(400, "There is not any comment");
        }
        
        return $json->render();
    }
}
