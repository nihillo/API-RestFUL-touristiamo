<?php
/**
 * This file is used to router the request to the controller and to response.
 * We must be careful with the token because it's the user credential.
 * On the other hand, We split the routes in different files to do 
 * this one easier readable.
 */

require_once APP_DIRECTORY. DS. 'routes'. DS. 'userRoutes.php';
require_once APP_DIRECTORY. DS. 'routes'. DS. 'routeRoutes.php';
require_once APP_DIRECTORY. DS. 'routes'. DS. 'cityRoutes.php';
require_once APP_DIRECTORY. DS. 'routes'. DS. 'countryRoutes.php';