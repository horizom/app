<?php

use Aura\Router\Map;
use Horizom\Http\Route;

Route::get('home', '/', 'main@index');
Route::get('hello', '/hello/{name}', 'main@hello');
Route::route('test', '/test', 'main@test')->allows(["POST", "GET"]);

Route::attach('api.', '/api', function (Map $map) {
    $map->get('read', '/user/{id}', 'api/user@read');
    $map->post('create', '/user', 'api/user@create');
    $map->patch('update', '/user/{id}', 'api/user@update');
    $map->delete('delete', '/user/{id}', 'api/user@delete');
});