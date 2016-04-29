<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace touristiamo\controller\route;

use touristiamo\error\HttpError as HttpError;
use touristiamo\models\RouteModel as RouteModel;
use touristiamo\View as Json;
use touristiamo\exception\BDException as BDException;
use touristiamo\models\CommentModel as CommentModel;
use touristiamo\helper\UserHelper as UserHelper;
use touristiamo\helper\TokenHelper as TokenHelper;

/**
 * This class manages all route comments. Any user registered can add, change,
 * update and delete comments.
 *
 * @author I.E.S Francisco Ayala
 */
class RouteCommentCtrl
{
    /**
     * TERMINAR ESTO CON LOS WEB TOKENS
     */
    
    
    
    /**
     * 
     * @param integer $routeId
     * @param \ArrayIterator $publicToken
     * @param \ArrayIterator $args
     * @return Json
     */
    public static function newComment($routeId, $publicToken, $args)
    {
        try 
        {
            $userModel = TokenHelper::checkSign($publicToken);
            
            if (empty($args['comment']) || empty($args['score']) )
            {
                HttpError::send(400, "You must fill comment and score.");
            }
            $routeModel = new RouteModel($routeId);
            $commentModel = new CommentModel();
            $commentModel->userId = $userModel->id;
            $commentModel->comment = htmlentities(trim($args['comment']));
            $commentModel->score = intval($args['score'] % PROHIBIT_LIMIT_SCORE); // The max score is 5
            $commentModel->date = date("Y-m-d");
            $commentModel->routeId = $routeModel->id;
            
            if ($commentModel->save())
            {
                $json = new Json();
                $json->message = 'The comment was saved successfuly.';
                return $json->render();
            }
        } catch (BDException $e) 
        {
            HttpError::send(400, $e->getBdMessage());
        } 
    }
    
    /**
     * 
     * @param integer $routeId 
     * @param integer $commentId
     * @param \ArrayIterator $publicToken
     * @param \ArrayIterator $args
     * @return Json
     */
    public static function editComment($routeId, $commentId, $publicToken, $args)
    {
        try 
        {
            $userModel = TokenHelper::checkSign($publicToken);
            
            $routeModel = new RouteModel($routeId);
            $commentModel = new CommentModel($commentId);
            if ($routeModel->id != $commentModel->routeId)
            {
                HttpError::send(400, 'You cannot change comments of other routes');
            }
            if ($commentModel->userId != $userModel->id)
            {
                HttpError::send(401, 'You cannot change comments of other users');
            }
            $commentModel->comment = (!$args['comment']) ? $commentModel->comment : htmlentities(trim($args['comment']));
            $commentModel->date = date("Y-m-d");
            $commentModel->score = (!$args['score'])? $commentModel->score : htmlentities(trim($args['score']));
            if ($commentModel->update())
            {
                $json = new Json();
                $json->message = 'The comment was modified successfuly';
                return $json->render();
            }
        } catch (BDException $e) 
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
    
    
    /**
     * Can delete the comment own user and any admin user.
     * @param integer $routeId
     * @param integer $commentId
     * @param \ArrayIterator $publicToken
     * @param \ArrayIterator $args
     * @return Json
     */
    public static function delete($routeId, $commentId, $publicToken, $args)
    {
        try
        {
            $userModel = TokenHelper::checkSign($publicToken);
            
            $routeModel = new RouteModel($routeId);
            $commentModel = new CommentModel($commentId);
            if ($routeModel->id != $commentModel->routeId)
            {
                HttpError::send(400, 'You cannot delete comments of other routes');
            }
            if ($commentModel->userId != $userModel->id && !UserHelper::isAdmin($userModel->id))
            {
                HttpError::send(401, 'You cannot delete comments of other users');
            }
            if ($commentModel->delete())
            {
                $json = new Json();
                $json->message = 'The comment was deleted successfuly';
                return $json->render();
            }
        } catch (BDException $e) 
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
}
