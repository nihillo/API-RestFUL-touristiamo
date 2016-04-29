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
use touristiamo\models\CityModel as CityModel;
use touristiamo\models\PictureModel as PictureModel;
use touristiamo\models\CommentModel as CommentModel;
use touristiamo\helper\UserHelper as UserHelper;
use touristiamo\helper\TokenHelper as TokenHelper;

/**
 * This class is used to manage routes
 *
 * @author I.E.S Francisco Ayala
 */
class RouteAdminCtrl
{
    /**
     * 
     * @param \ArrayIterator $publicToken
     * @param \ArrayIterator $args
     * @return Json
     */
    public static function create($publicToken, $args)
    {
        try 
        {
            $userModel = TokenHelper::checkSign($publicToken);
            
            if (!(UserHelper::isAdmin($userModel->id)) && !(UserHelper::isTouristiamo($userModel->id)) )
            {
                HttpError::send(401, "The user doesn't have permissions to create routes");
            }
            if (empty($args['name']) || empty($args['slogan']) || empty($args['description']) 
                    || empty($args['handicapped']) || empty($args['cityId']) )
            {
                HttpError::send(400, "You must fill name, slogan, description, handicapped and city id");
            }
            $cityModel = new CityModel($args['cityId']);
            if (($cityModel->id != ($args['cityId'])) )
            {
                HttpError::send(400, "The city id is incorrect");
            }
            $routeModel = new RouteModel();
            $routeModel->name = htmlentities(trim($args['name']));
            $routeModel->slogan = htmlentities(trim($args['slogan']));
            $routeModel->description = htmlentities(trim($args['description']));
            $routeModel->handicapped = ($args['handicapped']) ? true : false;
            $routeModel->cityId = htmlentities(trim($args['cityId']));
            $routeModel->userId = $userModel->id;

            if ($routeModel->save())
            {
                $json = new Json();
                $json->message = 'The route was saved successfuly.';
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
     * @param \ArrayIterator $publicToken
     * @param \ArrayIterator $args
     * @return Json
     */
    public static function edit($routeId, $publicToken, $args)
    {
        try
        {
            $userModel = TokenHelper::checkSign($publicToken);
            
            
            $routeModel = new RouteModel($routeId);
            if ($userModel->id != $routeModel->userId)
            {
                HttpError::send(401, 'You cannot change routes of other users.');
            }
            $routeModel->name = (!isset($args['name'])) ? $routeModel->name : htmlentities(trim($args['name']));
            $routeModel->slogan = (!isset($args['slogan'])) ? $routeModel->slogan : htmlentities(trim($args['slogan']));
            $routeModel->description = (!isset($args['description'])) ? $routeModel->description : htmlentities(trim($args['description']));
            $routeModel->handicapped = (!isset($args['handicapped'])) ? false : true;
            $routeModel->cityId = (!isset($args['cityId'])) ? $routeModel->cityId : htmlentities(trim($args['cityId']));
            if ($routeModel->update())
            {
                $json = new Json();
                $json->message = 'The route was updated successfuly.';
                return $json->render();
            }
        } catch (BDException $e) 
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
    
    
    /**
     * Only could delete the owner and any admin user.
     * @param integer $routeId
     * @param \ArrayIterator $publicToken
     * @param \ArrayIterator $args
     * @return Json
     */
    public static function delete($routeId, $publicToken)
    {
        try
        {
            $userModel = TokenHelper::checkSign($publicToken);
            $routeModel = new RouteModel($routeId);
            if ($routeModel->userId != $userModel->id && !UserHelper::isAdmin($userModel->id))
            {
                HttpError::send(401, 'You cannot delete routes of other users');
            }
            $pictureModel = new PictureModel();
            $commentModel = new CommentModel();
            if (!$pictureModel->deleteAllByRouteId($routeId) || !$commentModel->deleteAllByRouteId($routeId)) 
            {
                HttpError::send(500, 'Fail to delete images or comments from this route');
            }
            if ($routeModel->delete())
            {
                $json = new Json();
                $json->message = 'The route was deleted successfuly';
                return $json->render();
            }
        } catch (BDException $e) 
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
}
