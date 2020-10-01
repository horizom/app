<?php

namespace App\Controller;

use Horizom\Http\Request;
use Horizom\Http\Response;
use App\Model\User;

class Main
{
    public function __construct()
    {
        # code...
    }

    public function index(Request $request, Response $response, array $attributes)
    {
        $user = User::where('id', 1)->first()->toArray();

        $data['user'] = $user;
        $data['name'] = "Horizom Framawork";

        return $response->view('index', $data);
    }

    public function hello(Request $request, Response $response, array $attributes)
    {
        $data['name'] = $request->getAttribute("name");

        return $response->view('hello', $data);
    }

    public function api(Request $request, Response $response, array $attributes)
    {
        $data = [
            'status' => true,
            'message' => "Request successfuly",
            'payload' => 6757869
        ];

        return $response->json($data);
    }
}
