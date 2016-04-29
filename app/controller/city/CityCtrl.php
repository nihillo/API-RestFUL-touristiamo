<?php

namespace touristiamo\controller\city;

use touristiamo\models\CityModel as CityModel;
use touristiamo\models\CountryModel as CountryModel;
use touristiamo\models\RouteModel as RouteModel;
use touristiamo\models\PictureModel as PictureModel;
use touristiamo\models\CommentModel as CommentModel;
use touristiamo\View as Json;
use touristiamo\helper\TokenHelper as TokenHelper;
use touristiamo\error\HttpError as HttpError;
use touristiamo\helper\UserHelper as UserHelper;
use touristiamo\exception\BDException as BDException;


/**
 * City Controller
 *
 * @author I.E.S Francisco Ayala
 */
class CityCtrl
{
    /**
     * Get all cities
     * @return Json
     */
    public static function getAll()
    {
        try
        {
            $cityModel = new CityModel();
            $json = new Json();
            $json->cities = $cityModel->getAll();
            return $json->render();
        } catch (BDException $e) 
        {
            HttpError::send(400, $e->getBdMessage());
        }
        
    }
    
    /**
     * 
     * @param \ArrayIterator $args
     * @param \ArrayIterator $publicToken
     * @return Json
     */
    public static function newCity($args, $publicToken)
    {
        try
        {
            $userModel = TokenHelper::checkSign($publicToken);
            if (!isset($args['name']) || !isset($args['countryId']))
            {
                HttpError::send(400, 'You must fill the name and the countryId');
            }
            if (!UserHelper::isTouristiamo($userModel->id) && !UserHelper::isAdmin($userModel->id))
            {
                HttpError::send(401, "The user doesn't have permissions to create cities");
            }
            $countryModel = new CountryModel($args['countryId']);
            $cityModel = new CityModel();
            $cityModel->countryId = $countryModel->id;
            $cityModel->name = htmlentities(trim($args['name']));
            if ($cityModel->save())
            {
                $json = new Json();
                $json->message = "The city was saved successfuly.";
                return $json->render();
            }
        } catch (BDException $e)
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
    
    
    /**
     * 
     * @param integer $cityId
     * @param \ArrayIterator $publicToken
     * @param \ArrayIterator $args
     * @return Json
     */
    public static function edit($cityId, $publicToken, $args)
    {
        try
        {
            $userModel = TokenHelper::checkSign($publicToken);
            $cityModel = new CityModel($cityId);
            if (!UserHelper::isTouristiamo($userModel->id) && !UserHelper::isAdmin($userModel->id))
            {
                HttpError::send(401, "The user doesn't have permissions to update this city");
            }
            $cityModel->name = (!isset($args['name'])) ? $cityModel->name : htmlentities(trim($args['name']));
            $cityModel->countryId = (!isset($args['countryId'])) ? $cityModel->countryId : htmlentities(trim($args['countryId']));
            if ($cityModel->update())
            {
                $json = new Json();
                $json->message = "The city was modified successfuly.";
                return $json->render();
            }
        } catch (BDException $e)
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
    
    
    /**
     * Delete a city. For deleting a city is necesary delete all routes join to this
     * city.
     * @param integer $id
     * @param \ArrayIterator $publicToken
     * @return Json
     */
    public static function delete($id, $publicToken)
    {
        try
        {
            $userModel = TokenHelper::checkSign($publicToken);
            if (!UserHelper::isTouristiamo($userModel->id) && !UserHelper::isAdmin($userModel->id))
            {
                HttpError::send(401, "The user doesn't have permissions to delete cities");
            }
            $cityModel = new CityModel($id);
            $routeModel = new RouteModel();
            $pictureModel = new PictureModel();
            $commentModel = new CommentModel();
            // Delete all comments, pictures and routes from this city
            foreach ($routeModel->getAllByCity($cityModel->id) as $route)
            {
                $routeMod = new RouteModel($route->id);
                if (!$pictureModel->deleteAllByRouteId($routeMod->id) || !$commentModel->deleteAllByRouteId($routeMod->id))
                {
                    HttpError::send(500, 'Fail to delete images or comments from this route');
                }
                $routeMod->delete();
            }
            if ($cityModel->delete())
            {
                $json = new Json();
                $json->message = "The city was deleted successfuly.";
                return $json->render();
            }
        } catch (BDException $e)
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
}
