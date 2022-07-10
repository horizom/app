<?php

use Horizom\Routing\RouteCollector;

/** 
 * @var RouteCollector $router
 */

$router->get('/', 'MainController@index');
$router->get('/hello/{name}', 'MainController@hello');
