<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace touristiamo\helper;
use touristiamo\models\UsersModel as UserModel;
use touristiamo\exception\BDException as BDException;
use touristiamo\error\HttpError as HttpError;

/**
 * Description of UserActiveHelper
 *
 * @author cristobal
 */
class UserHelper
{
    /**
     * Check if the user is activated
     * @param integer $id
     * @return boolean
     */
    public static function isActive($id)
    {
        try
        {
            if ( !($userModel = new UserModel($id)) )
            {
                return false;
            }
            return ($userModel->activated) ? true : false;
        } catch (BDException $e)
        {
            HttpError::send(400, $e->getBdMessage());
        }
        
    }
    
    /**
     * Check if the user is banned
     * @param integer $id
     * @return boolean
     */
    public static function isBanned($id)
    {
        try
        {
            if ( !($userModel = new UserModel($id)) )
            {
                return false;
            }
            return ($userModel->banned) ? true : false;
        } catch (BDException $e)
        {
            HttpError::send(400, $e->getBdMessage());
        }
        
    }
    
    /**
     * Admin User (1)
     * @param integer $id
     */
    public static function isAdmin($id)
    {
        try
        {
            if ( !($userModel = new UserModel($id)) )
            {
                return false;
            }
            return ($userModel->accessLevel == 1) ? true : false;
        } catch (BDException $e)
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
    
    /**
     * Touristiamo user (2)
     */
    public static function isTouristiamo($id)
    {
        try
        {
            if ( !($userModel = new UserModel($id)) )
            {
                return false;
            }
            return ($userModel->accessLevel == 2) ? true : false;
        } catch (BDException $e)
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
    
    /**
     * Standard user (3)
     */
    public static function isStandard($id)
    {
        try
        {
            if ( !($userModel = new UserModel($id)) )
            {
                return false;
            }
            return ($userModel->accessLevel == 3) ? true : false;
        } catch (BDException $e)
        {
            HttpError::send(400, $e->getBdMessage());
        }
    }
}
