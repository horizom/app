<?php

/** 
 * @var Horizom\Routing\RouteCollector $router
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Horizom the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', 'MainController@index');
$router->get('/hello/{name}', 'MainController@hello');
