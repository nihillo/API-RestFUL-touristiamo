<?php

namespace touristiamo\controller\route;

use touristiamo\models\PictureModel as PictureModel;
use touristiamo\models\RouteModel as RouteModel;
use touristiamo\error\HttpError as HttpError;
use touristiamo\helper\TokenHelper as TokenHelper;
use touristiamo\helper\UserHelper as UserHelper;
use touristiamo\helper\ImageHelper as ImageHelper;
use touristiamo\View as Json;
use touristiamo\exception\BDException as BDException;


/**
 * @author I.E.S Francisco Ayala
 */
class RouteImagesCtrl
{
    /**
     * 
     * @param integer $routeId
     * @return Json
     */
    public static function getImages($routeId)
    {
        try 
        {
            $pictureModel = new PictureModel();
            $json = new Json();
            $json->images = $pictureModel->getAllByRouteId($routeId);
            if (!$json->images)
            {
                HttpError::send(400, "There isn't any image from this route");
            }
            return $json->render();
        } catch (BDException $e) 
        {
            HttpError::send(400, $e->getBdMessage());
        }       
    }
    
    /**
     * 
     * @param integer $routeId
     * @param \ArrayIterator $publicToken
     * @return Json
     */
    public static function newImage($routeId, $publicToken)
    {
        try
        {
            $userModel = TokenHelper::checkSign($publicToken);
            if ( (!UserHelper::isTouristiamo($userModel->id) && !UserHelper::isAdmin($userModel->id)) )
            {
                HttpError::send(401, "The user doesn't have permissions to upload images.");
            }
            if (empty($_FILES[APP_IMAGES_UPLOAD_NAME]))
            {
                HttpError::send(400, "The images array must be called ". APP_IMAGES_UPLOAD_NAME);
            }
            
            $routeModel = new RouteModel($routeId);
            $imagesUploaded = ImageHelper::upload($routeModel->id, APP_IMAGES_UPLOAD_NAME);
            $json = new Json();
            $json->numImages = count($imagesUploaded);
            $json->message = "Images were uploaded successfuly.";
            return $json->render();
        } catch (BDException $e)
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
    
    
    /**
     * Only the route own can delete images but anybody can upload images.
     * @param integer $imageId
     * @return Json
     */
    public static function deleteImage($routeId, $imageId, $publicToken)
    {
        try
        {
            $userModel = TokenHelper::checkSign($publicToken);
            $routeModel = new RouteModel($routeId);

            if ($routeModel->userId != $userModel->id && !UserHelper::isAdmin($userModel->id))
            {
                HttpError::send(401, "You cannot delete images of other user's routes.");
            }
            $fileDeleted = ImageHelper::delete($routeId, $imageId);
            $json = new Json();
            $json->message = 'The file '. $fileDeleted. ' was deleted successfuly.';
            return $json->render();
        } catch (BDException $e) 
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
}
