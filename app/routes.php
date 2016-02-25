<?php

/**
 * Load Classes from namespaces
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use touristiamo\controller\user\RegisterCtrl as RegisterCtrl;
use touristiamo\controller\user\UserCtrl as UserCtrl;
use touristiamo\controller\route\RouteCtrl as RouteCtrl;
use touristiamo\controller\route\RouteAdminCtrl as RouteAdminCtrl;


/**
 * Routes for Auth Zone - Using Auth controller
 */
$app->group('/users', function () 
{
    $this->group('/register', function () 
    {
        /**
         * This route is used to register new users
         */
        $this->post('', function (Request $request, Response $response, $args)
        {
            return $response->getBody()
                    ->write(RegisterCtrl::register($request->getQueryParams()) );
        });
        
        /**
         * This one is used to active user
         */
        $this->get('/active/{token}', function (Request $request, Response $response, $args)
        {
            return $response->getBody()
                    ->write(RegisterCtrl::active($args['token']) );
        });
    });
    
    /**
     * This one is used to auth users. This return the user token
     */
    $this->post('/auth', function(Request $request, Response $response, $args)
    {
        return $response->getBody()
                ->write(UserCtrl::login($request->getQueryParams()) );
    });
    
    /**
     * Generate a new token
     */
    $this->post('/generateToken', function(Request $request, Response $response, $args)
    {
        return $response->getBody()
                ->write(UserCtrl::generateNewToken($request->getQueryParams()) );
    });
    
    /**
     * Update user information
     */
    $this->put('/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        return $response->getBody()
                ->write(UserCtrl::updateInformation($args['token'], $request->getQueryParams()) );
    });
    
    /**
     * Disable user from the app.
     */
    $this->delete('/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        return $response->getBody()
                ->write(UserCtrl::disable($args['token']) );
    });
    
    
    $this->get('/{token:[0-9a-fA-F]{40}}/comments', function(Request $request, Response $response, $args)
    {
        return $response->getBody()
                ->write(UserCtrl::getAllComments($args['token']));
    });
});

/**
 * Routes for Routes Zone - Using Route controller
 */
$app->group('/routes', function()
{
    /**
     * Get all routes from the data base
     */
    $this->get('', function(Request $request, Response $response, $args)
    {
        return $request->getBody()
                ->write(RouteCtrl::getAll());
    });
    /**
     * Get all routes by country id
     */
    $this->get('/country/{countryId:[0-9]+}', function(Request $request, Response $response, $args)
    {
        return $request->getBody()
                ->write(RouteCtrl::getAllByCountry($args['countryId']));
    });
    /**
     * Get all routes by city id
     */
    $this->get('/city/{cityId:[0-9]+}', function(Request $request, Response $response, $args)
    {
        return $request->getBody()
                ->write(RouteCtrl::getAllByCity($args['cityId']));
    });
    /**
     * Get all comments from a route by id
     */
    $this->get('/{id:[0-9]+}/comments', function(Request $request, Response $response, $args)
    {
        return $request->getBody()
                ->write(RouteCtrl::getComments($args['id']));
    });
    /**
     * Get route score
     */
    $this->get('/{id:[0-9]+}/score', function(Request $request, Response $response, $args)
    {
        return $request->getBody()
                ->write(RouteCtrl::getScore($args['id']));
    });
    /**
     * Create a new route. Only the user with accesslevel 2 and 1 can post new routes.
     */
    $this->post('/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        return $request->getBody()
                ->write( RouteAdminCtrl::createRoute($args['token'], $request->getQueryParams()) );
    });
    
    // TODO: Seguir por aqui
    $this->post('/{id:[0-9]+}/comments', function(Request $request, Response $response, $args)
    {
        echo 'Post new commenst of '. $args['id'];
    });
    $this->put('/{routeId:[0-9]+}/comments/{commentId:[0-9]}/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'Put new commenst of '. $args['routeId']. ' comment id '. $args['commentId']. 
                ' y token '. $args['token'];
    });
    $this->delete('/{routeId:[0-9]+}/comments/{commentId:[0-9]}/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'Delete new commenst of '. $args['routeId']. ' comment id '. $args['commentId']. 
                ' y token '. $args['token'];
    });
    $this->put('/{id:[0-9]+}/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'Put routes '. $args['id']. ' y token: '. $args['token'];
    });
    $this->delete('/{id:[0-9]+}/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'Delete routes '. $args['id']. ' y token: '. $args['token'];
    });
    
    /** 
     * Routes images
     */
    $this->get('/{id:[0-9]+}/images', function(Request $request, Response $response, $args)
    {
        echo 'Imges de '. $args['id'];
    });
    $this->post('/{id:[0-9]+}/images/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'Post images de '. $args['id']. ' y token: '. $args['token'];
    });
    $this->put('/{routeId:[0-9]+}/images/{imageId:[0-9]}/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'Put images de la ruta '. $args['routeId']. ' y el id de imagen '. $args['imageId']. 
                ' y token: '. $args['token'];
    });
    $this->delete('/{routeId:[0-9]+}/images/{imageId:[0-9]}/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'Delete images de la ruta '. $args['routeId']. ' y el id de imagen '. $args['imageId'].
                ' y token: '. $args['token'];
    });
});

/**
 * Routes for Cities Zone - Using City controller
 */
$app->group('/cities', function()
{
    $this->get('', function(Request $request, Response $response, $args)
    {
        echo 'get de todas las ciudades';
    });
    $this->post('/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'get de todas las ciudades'.
                ' y token: '. $args['token'];;
    });
    $this->put('/{id:[0-9]+}/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'get de todas las ciudades'.
                ' y token: '. $args['token'];;
    });
    $this->delete('/{id:[0-9]+}/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'get de todas las ciudades'.
                ' y token: '. $args['token'];;
    });
});

/**
 * Routes for Countries Zone - Using Country controller
 */
$app->group('/countries', function()
{
    $this->get('', function(Request $request, Response $response, $args)
    {
        echo 'get de todas las ciudades';
    });
    $this->post('/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'post ciudad';
    });
    $this->put('/{id:[0-9]+}/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'Put pais'. $args['id'];
    });
    $this->delete('/{id:[0-9]+}/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'Delete ciudad '. $args['id'];
    });
});