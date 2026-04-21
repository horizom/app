<?php

declare(strict_types=1);

namespace App\Middlewares;

use Throwable;
use Horizom\Core\ErrorHandlerInterface;
use Horizom\Routing\Exception\NotFoundException;
use Horizom\Routing\Exception\MethodNotAllowedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ErrorHandlerMiddleware implements ErrorHandlerInterface
{
    public function handle(Throwable $e, ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $code = 500;
        $title = 'Internal Error';
        $message = 'An internal error has occurred.';
        $headers = [];

        if ($e instanceof NotFoundException) {
            $code = 404;
            $title = 'Not Found';
            $message = 'The requested resource was not found.';
        } elseif ($e instanceof MethodNotAllowedException) {
            $code = 405;
            $title = 'Method Not Allowed';
            $message = 'The method is not allowed for the requested URL.';
            $headers = ['Allow' => implode(', ', $e->getAllowedMethods())];
        }

        $isDebug = (bool) config('app.pretty_debug', false);

        $data = [
            'code' => $code,
            'title' => $title,
            'message' => $message,
        ];

        // Only expose stack trace in debug mode — never in production
        if ($isDebug) {
            $data['trace'] = $e->getTraceAsString();
        }

        $server = collect($request->getServerParams());
        $paramKey = 'HTTP_X_REQUESTED_WITH';

        if ($server->has($paramKey) && strtolower((string) $server->get($paramKey)) === 'xmlhttprequest') {
            $response = response($code, $headers)->json($data);
        } else {
            $response = response($code, $headers)->view('errors', $data);
        }

        return $response;
    }
}
