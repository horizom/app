<?php

use Horizom\Routing\RouteCollector;

/** 
 * @var RouteCollector $router
 */

$router->group(['prefix' => 'api'], function (RouteCollector $router) {
    $router->any('/', 'ApiController@index');
    $router->any('/status', 'ApiController@status');
    $router->any('/version', 'ApiController@version');
});
