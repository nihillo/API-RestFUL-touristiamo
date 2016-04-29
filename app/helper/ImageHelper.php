<?php

namespace touristiamo\helper;

use touristiamo\models\PictureModel as PictureModel;

/**
 * This class help us to manage images.
 *
 * @author I.E.S Francisco Ayala
 */
class ImageHelper
{
    /**
     * 
     * @param integer $routeId
     * @param string $imgArrayName
     * @return array
     */
    public static function upload($routeId, $imgArrayName)
    {
        $routeDirectory = APP_IMAGES_DIRECTORY. DS. 'routes'. DS. $routeId;
        if (!is_dir($routeDirectory) && !mkdir($routeDirectory, 0755) && 
                !is_readable($routeDirectory))
        {
            HttpError::send(500, "The routes image directory cannot be accessed. You must look permissions please.");
        }
        
        $images = [];
        for ($i = 0; $i < count($_FILES[$imgArrayName]['name']); $i++)
        {
            $fileName = time(). '_'. $_FILES[$imgArrayName]['name'][$i];
            $fileType = $_FILES[$imgArrayName]['type'][$i];
            array_push($images, self::uploadImage($routeId, $fileName, $fileType, 
                    $routeDirectory, $_FILES[$imgArrayName]['tmp_name'][$i]) );
        }      
        return $images;
    }
    
    /**
     * 
     * @param integer $routeId
     * @param integer $imageId
     * @return string
     */
    public static function delete($routeId, $imageId)
    {
        $routeDirectory = APP_IMAGES_DIRECTORY. DS. 'routes'. DS. $routeId;
        if (!is_dir($routeDirectory) && !is_readable($routeDirectory))
        {
            HttpError::send(500, "The routes image directory cannot be accessed. You must look permissions please.");
        }
        $pictureModel = new PictureModel($imageId);
        $fileUrl = explode('/', $pictureModel->image);
        $file = $fileUrl[count($fileUrl) - 1];
        
        if (!unlink($routeDirectory. DS. $file) || !$pictureModel->delete())
        {
            HttpError::send(500, "There was an error to delete the file ". $file);
        }
        
        return $file;
    }


    
    /**
     * 
     * @param string $fileName
     * @param string $fileType
     * @param string $routeDirectory
     * @param string $imgArrayName
     * @return PictureModel
     */
    private static function uploadImage($routeId, $fileName, $fileType, $routeDirectory, $tmpName)
    {
        if (preg_match('/.+\.(jpg|png|gif)$/', $fileName) && 
                    preg_match('/image\/(jpeg|png|gif)$/', $fileType) )
        {
            if (move_uploaded_file($tmpName, $routeDirectory. DS. $fileName))
            {
                $pictureModel = new PictureModel();
                $pictureModel->image = APP_URL. '/images/routes/'. $routeId. '/'. $fileName;
                $pictureModel->routeId = $routeId;
                $pictureModel->save();
                return $pictureModel;
            }
        }
    }
}
