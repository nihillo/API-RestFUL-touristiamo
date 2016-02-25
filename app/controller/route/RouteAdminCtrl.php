<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace touristiamo\controller\route;

use touristiamo\models\UsersModel as UserModel;
use touristiamo\error\HttpError as HttpError;
use touristiamo\models\RouteModel as RouteModel;
use touristiamo\View as Json;
use touristiamo\exception\BDException as BDException;
use touristiamo\models\CityModel as CityModel;
use touristiamo\helper\UserHelper as UserHelper;

/**
 * This class is used to manage routes
 *
 * @author I.E.S Francisco Ayala
 */
class RouteAdminCtrl
{
    public static function createRoute($token, $args)
    {
        $userModel = new UserModel();
        if (!$userModel->fillByToken($token))
        {
            HttpError::send(400, "The token is incorrect");
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
        if (!(UserHelper::isAdmin($userModel->id)) && !(UserHelper::isTouristiamo($userModel->id)) )
        {
            HttpError::send(400, "The user doesn't have permissions to create routes");
        }
        $routeModel = new RouteModel();
        $routeModel->name = htmlentities(trim($args['name']));
        $routeModel->slogan = htmlentities(trim($args['slogan']));
        $routeModel->description = htmlentities(trim($args['description']));
        $routeModel->handicapped = ($args['handicapped']) ? true : false;
        $routeModel->cityId = htmlentities(trim($args['cityId']));
        $routeModel->userId = $userModel->id;
        try 
        {
            if ($routeModel->save())
            {
                $json = new Json();
                $json->message = 'The route was saved successfuly.';
                return $json->render();
            }
        } catch (BDException $e) 
        {
            return $e->getBdMessage();
        }
    }
}
