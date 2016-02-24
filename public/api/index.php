<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}
require __DIR__. DIRECTORY_SEPARATOR. '..'. DIRECTORY_SEPARATOR. '..'. 
        DIRECTORY_SEPARATOR. 'app'. DIRECTORY_SEPARATOR. 'config.php';

require APP_DIRECTORY . DS. 'vendor'. DS. 'autoload.php';

session_start();

// Instantiate the app
$app = new \Slim\App();

// Register routes
require APP_DIRECTORY. DS. 'routes.php';

/*
 * Comentado por uso de namesapces;
 * 
 * 
// Register MVC parents and services
require APP_DIRECTORY. DS. 'service'. DS. 'DBService.php';
require APP_DIRECTORY. DS. 'Model.php';
require APP_DIRECTORY. DS. 'Controller.php';
require APP_DIRECTORY. DS. 'View.php';

// Register Error class for using in other class
require APP_DIRECTORY. DS. 'error'. DS. 'HttpError.php';
*/

// Run app
$app->run();