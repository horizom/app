<?php

namespace App\Controllers;

use Horizom\Http\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class ApiController
{
    public function index(): ResponseInterface
    {
        $payload = [
            'message' => "Request successfuly",
            'data' => time()
        ];

        return response()->json($payload);
    }

    public function status(): ResponseInterface
    {
        return response()->json(['status' => 'UP']);
    }

    public function version(Response $response, ContainerInterface $container): ResponseInterface
    {
        return $response->json(['version' => $container->get("version")]);
    }
}
