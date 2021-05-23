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

$router->group(['prefix' => 'api'], function ($router) {
    $router->post('/', 'ApiController@index');
    $router->post('/version', 'ApiController@version');
    $router->post('/status', 'ApiController@status');
});
