<?php

namespace App\Controllers;

use Horizom\Http\Response;
use Psr\Http\Message\ResponseInterface;

class MainController
{
    public function index(Response $response): ResponseInterface
    {
        return $response->view('index');
    }

    public function hello(Response $response, $name): ResponseInterface
    {
        return $response->view('hello', ["name" => $name]);
    }
}
