<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;

final class MainController
{
    public function index(): ResponseInterface
    {
        return response()->view('main.index');
    }
}
