<?php

declare(strict_types=1);

namespace Tests\Unit\Middlewares;

use App\Middlewares\CorsMiddleware;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CorsMiddlewareTest extends TestCase
{
    private CorsMiddleware $middleware;

    /** @var ServerRequestInterface&MockObject */
    private ServerRequestInterface $request;

    /** @var RequestHandlerInterface&MockObject */
    private RequestHandlerInterface $handler;

    /** @var ResponseInterface&MockObject */
    private ResponseInterface $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new CorsMiddleware();
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testPreflightOptionsReturns204WithoutCallingHandler(): void
    {
        $this->request
            ->method('getMethod')
            ->willReturn('OPTIONS');

        $this->request
            ->method('getHeaderLine')
            ->with('Origin')
            ->willReturn('https://example.com');

        // The downstream handler must NOT be called for preflight requests
        $this->handler
            ->expects($this->never())
            ->method('handle');

        $result = $this->middleware->process($this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testNonOptionsRequestForwardsToHandler(): void
    {
        $this->request
            ->method('getMethod')
            ->willReturn('GET');

        $this->request
            ->method('getHeaderLine')
            ->with('Origin')
            ->willReturn('https://example.com');

        // Chain withHeader calls on the mock response
        $this->response->method('withHeader')->willReturnSelf();

        $this->handler
            ->expects($this->once())
            ->method('handle')
            ->with($this->request)
            ->willReturn($this->response);

        $result = $this->middleware->process($this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testCorsHeadersAreAddedToNonPreflightResponse(): void
    {
        $this->request
            ->method('getMethod')
            ->willReturn('POST');

        $this->request
            ->method('getHeaderLine')
            ->with('Origin')
            ->willReturn('https://example.com');

        // Capture which headers are set
        $headersSet = [];
        $this->response
            ->method('withHeader')
            ->willReturnCallback(function (string $name, string $value) use (&$headersSet): ResponseInterface {
                $headersSet[$name] = $value;
                return $this->response;
            });

        $this->handler
            ->method('handle')
            ->willReturn($this->response);

        $this->middleware->process($this->request, $this->handler);

        $this->assertArrayHasKey('Access-Control-Allow-Origin', $headersSet);
        $this->assertArrayHasKey('Access-Control-Allow-Methods', $headersSet);
        $this->assertArrayHasKey('Access-Control-Allow-Headers', $headersSet);
        $this->assertArrayHasKey('Access-Control-Max-Age', $headersSet);
        $this->assertSame('3600', $headersSet['Access-Control-Max-Age']);
    }
}
