<?php

declare(strict_types=1);

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CorsMiddleware implements MiddlewareInterface
{
    private const ALLOWED_METHODS = 'GET, POST, PUT, DELETE, PATCH, OPTIONS';
    private const ALLOWED_HEADERS = 'Content-Type, Accept, Authorization, X-Requested-With';
    private const MAX_AGE = '3600';

    /**
     * Allowed origins. Override '*' with a specific list for production.
     * Example: ['https://myapp.com', 'https://www.myapp.com']
     *
     * @var string[]
     */
    private array $allowedOrigins = ['*'];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Handle preflight OPTIONS request without forwarding to the next handler
        if ($request->getMethod() === 'OPTIONS') {
            return $this->buildPreflightResponse($request);
        }

        $response = $handler->handle($request);

        return $this->addCorsHeaders($request, $response);
    }

    private function buildPreflightResponse(ServerRequestInterface $request): ResponseInterface
    {
        return $this->addCorsHeaders($request, response(204))
            ->withHeader('Content-Length', '0');
    }

    private function addCorsHeaders(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $origin = $this->resolveAllowedOrigin($request->getHeaderLine('Origin'));

        return $response
            ->withHeader('Access-Control-Max-Age', self::MAX_AGE)
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Methods', self::ALLOWED_METHODS)
            ->withHeader('Access-Control-Allow-Headers', self::ALLOWED_HEADERS)
            ->withHeader('Vary', 'Origin');
    }

    private function resolveAllowedOrigin(string $requestOrigin): string
    {
        if (in_array('*', $this->allowedOrigins, true)) {
            return '*';
        }

        if (in_array($requestOrigin, $this->allowedOrigins, true)) {
            return $requestOrigin;
        }

        return $this->allowedOrigins[0] ?? '*';
    }
}
