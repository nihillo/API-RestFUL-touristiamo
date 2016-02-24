<?php

/**
 * Load Classes from namespaces
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use touristiamo\controller\user\RegisterCtrl as RegisterCtrl;
use touristiamo\controller\user\UserCtrl as UserCtrl;



/**
 * Routes for Auth Zone - Using Auth controller
 */
$app->group('/users', function () 
{
    $this->group('/register', function () 
    {
        $registerCtrl = new RegisterCtrl();
        /**
         * This route is used to register new users
         */
        $this->post('', function (Request $request, Response $response, $args) use ($registerCtrl)
        {
            $registerCtrl->register($request->getQueryParams());
        });
        
        /**
         * This one is used to active user
         */
        $this->get('/active/{token}', function (Request $request, Response $response, $args) use ($registerCtrl)
        {
            $registerCtrl->active($args['token']);
        });
    });
    
    $userCtrl = new UserCtrl();
    /**
     * This one is used to auth users. This return the user token
     */
    $this->post('/auth', function(Request $request, Response $response, $args) use ($userCtrl)
    {
        $userCtrl->login($request->getQueryParams()['email'], $request->getQueryParams()['password']);
    });
    
    $this->post('/generateToken', function(Request $request, Response $response, $args) use ($userCtrl)
    {
        $userCtrl->generateNewToken($request->getQueryParams()['token']);
    });
    $this->put('/{id:[0-9]+}/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'PUT ID: '. $args['id']. ' - '. $args['token'];
    });
    $this->delete('/{id:[0-9]+}/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'DELETE ID: '. $args['id']. ' - '. $args['token'];
    });
    $this->get('/comments/{id:[0-9]+}', function(Request $request, Response $response, $args)
    {
       // TODO: Los comentarios pueden ser publicos o privados segun elección?????
        echo 'get comments '. $args['id'];
        $userCtrl = new RegisterCtrl();
    });
});

/**
 * Routes for Routes Zone - Using Route controller
 */
$app->group('/routes', function()
{
    $this->get('', function(Request $request, Response $response, $args)
    {
        echo 'get de todas las rutas';
    });
    $this->get('/{country}', function(Request $request, Response $response, $args)
    {
        echo 'get de routes de '. $args['country'];
    });
    $this->get('/{country}/{city}', function(Request $request, Response $response, $args)
    {
        echo 'get de routes de '. $args['city']. 'de la city '. $args['country'];
    });
    $this->get('/{id:[0-9]+}/comments', function(Request $request, Response $response, $args)
    {
        echo 'Comments de '. $args['id'];
    });
    $this->post('/{token:[0-9a-fA-F]{40}}', function(Request $request, Response $response, $args)
    {
        echo 'Post de routes con token '. $args['token'];
    });
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