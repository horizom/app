<?php

use Horizom\Routing\RouteCollector;

/** 
 * @var RouteCollector $router
 */

$router->group(['prefix' => 'api'], function (RouteCollector $router) {
    $router->get('/', 'ApiController@index');
    $router->get('/status', 'ApiController@status');
    $router->get('/version', 'ApiController@version');
});
