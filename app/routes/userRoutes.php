<?php

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
        $this->get('/active/{token:[a-fA-F0-9]{40}}', function (Request $request, Response $response, $args)
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
                ->write( UserCtrl::generateNewToken($request->getQueryParams(), 
                        $request->getHeader('Authorization')) );
    });
    
    /**
     * Update user information
     */
    $this->put('/{id:[0-9]+}', function(Request $request, Response $response, $args)
    {
        return $response->getBody()
                ->write(UserCtrl::updateInformation($args['id'],
                        $request->getHeader('Authorization'), 
                        $request->getQueryParams()) );
    });
    
    /**
     * Disable user from the app.
     */
    $this->delete('/{id:[0-9]+}', function(Request $request, Response $response, $args)
    {
        return $response->getBody()
                ->write(UserCtrl::disable($args['id'], $request->getHeader('Authorization'), 
                        $request->getQueryParams()) );
    });
    
    
    $this->get('/{id:[0-9]+}/comments', function(Request $request, Response $response, $args)
    {
        return $response->getBody()
                ->write(UserCtrl::getAllComments($args['id'], $request->getHeader('Authorization') ));
    });
});