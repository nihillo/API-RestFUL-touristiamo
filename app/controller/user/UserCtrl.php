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
     * @param \ArrayIterator $args
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
            HttpError::send(401, 'The user '. $userModel->email. ' is not activated or was banned');
        }
        if ($userModel->email === $args['email'] && $userModel->getPass() === sha1($args['password']))
        {
            $json = new Json();
            $json->token = TokenHelper::generatePublic($userModel, $userModel->getToken());
            return $json->render();
        } else
        {
            HttpError::send(401, 'Email or password incorrect');
        }
    }
    
    /**
     * This method generates a new token for the user.
     * @param \ArrayIterator $args
     * @param \ArrayIterator $publicToken
     * @return Json
     */
    public static function generateNewToken($args, $publicToken)
    {
        try
        {
            $userModel = TokenHelper::checkSign($publicToken);
            
            if (!UserHelper::isActive($userModel->id) || UserHelper::isBanned($userModel->id))
            {
                HttpError::send(401, 'The user '. $userModel->email. ' is not activated or was banned');
            }
            $userModel->setToken(TokenHelper::generate($userModel->email, $userModel->getPass()));
            if ($userModel->update())
            {
                $json = new Json();
                $json->token = TokenHelper::generatePublic($userModel, $userModel->getToken());
                return $json->render();
            } 
        } catch (BDException $e) 
        {
            HttpError::send(500, $e->getBdMessage());
        }
        
    }

    /**
     * This method updates the information of the user.
     * @param integer $id
     * @param \ArrayIterator $publicToken
     * @param \ArrayIterator $args
     * @return Json
     */
    public static function  updateInformation($id, $publicToken, $args)
    {
        try
        {
            $userModel = TokenHelper::checkSign($publicToken);
            
            if (!UserHelper::isActive($userModel->id) || UserHelper::isBanned($userModel->id))
            {
                HttpError::send(401, 'The user '. $userModel->email. ' is not activated or was banned');
            }
            if ($id != $userModel->id && !UserHelper::isAdmin($id))
            {
                HttpError::send(401, "The user doesn't have permissions to change this information");
            }
            $userModel->name = ( !isset($args['name'])) ? $userModel->name : htmlentities(trim($args['name']) , ENT_QUOTES );
            $userModel->setPassword( (!isset($args['password'])) ? $userModel->getPass() : sha1($args['password']) );
        
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
     * 
     * @param integer $id
     * @param \ArrayIterator $publicToken
     * @param ArrayIterator $args
     * @return Json
     */
    public static function disable($id, $publicToken, $args)
    {
        try
        {
            $userModel = TokenHelper::checkSign($publicToken);
            
            if ($id != $userModel->id && !UserHelper::isAdmin($id))
            {
                HttpError::send(401, "You don't have permissions to delete this user");
            }
            
            $userModel->activated = false;
            if ($userModel->update())
            {
                $json = new Json();
                $json->message = 'The user was disabled successfuly';
                
                $subject = 'Delete acount';
                $message = '<h1>'. APP_NAME. '</h1>';
                $message .= '<p>The account that you have in '. APP_NAME. ' has been deleted successfuly</p>';
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
     * 
     * @param integer $id
     * @param \ArrayIterator $publicToken
     * @param ArrayIterator $args
     * @return Json
     */
    public static function getAllComments($id, $publicToken)
    {
        try
        {
            $userModel = TokenHelper::checkSign($publicToken);
            
            if ($id != $userModel->id && !UserHelper::isAdmin($id))
            {
                HttpError::send(401, "You don't have permissions to see comments of other users.");
            }

            $json = new Json();

            $commentModel = new CommentModel();
            if ( !($json->comments = $commentModel->getAllByUserId($userModel->id)) )
            {
                HttpError::send(400, "There is not any comment");
            }

            return $json->render();
        } catch (BDException $e)
        {
            HttpError::send(400, $e->getBdMessage());
        }

        
    }
}
