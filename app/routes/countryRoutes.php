<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use touristiamo\controller\country\CountryCtrl as CountryCtrl;

/**
 * Routes for Countries Zone - Using Country controller
 */
$app->group('/countries', function()
{
    $this->get('', function(Request $request, Response $response, $args)
    {
        return $response->getBody()
                ->write(CountryCtrl::getCountries());
    });
    $this->post('', function(Request $request, Response $response, $args)
    {
        return $response->getBody()
                ->write(CountryCtrl::newCountry($request->getQueryParams(), 
                        $request->getHeader('Authorization')));
    });
    $this->put('/{id:[0-9]+}', function(Request $request, Response $response, $args)
    {
        return $response->getBody()
                ->write(CountryCtrl::editCountry($args['id'], $request->getHeader('Authorization'), 
                        $request->getQueryParams()));
    });
    $this->delete('/{id:[0-9]+}', function(Request $request, Response $response, $args)
    {
        return $response->getBody()
                ->write(CountryCtrl::deleteCountry($args['id'], $request->getHeader('Authorization')));
    });
});

