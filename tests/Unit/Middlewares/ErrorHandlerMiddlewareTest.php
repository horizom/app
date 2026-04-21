<?php

declare(strict_types=1);

namespace Tests\Unit\Middlewares;

use App\Middlewares\ErrorHandlerMiddleware;
use Horizom\Routing\Exception\MethodNotAllowedException;
use Horizom\Routing\Exception\NotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

final class ErrorHandlerMiddlewareTest extends TestCase
{
    private ErrorHandlerMiddleware $middleware;

    /** @var ServerRequestInterface&MockObject */
    private ServerRequestInterface $request;

    /** @var RequestHandlerInterface&MockObject */
    private RequestHandlerInterface $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new ErrorHandlerMiddleware();
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);

        // Default: not an XHR request
        $this->request
            ->method('getServerParams')
            ->willReturn([]);
    }

    public function testNotFoundExceptionReturnsResponseInterface(): void
    {
        $exception = new NotFoundException();

        $result = $this->middleware->handle($exception, $this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testMethodNotAllowedExceptionReturnsResponseInterface(): void
    {
        $exception = new MethodNotAllowedException(['GET', 'POST']);

        $result = $this->middleware->handle($exception, $this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testGenericExceptionReturnsResponseInterface(): void
    {
        $exception = new RuntimeException('Something went wrong');

        $result = $this->middleware->handle($exception, $this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testXhrRequestReceivesJsonResponse(): void
    {
        $this->request
            ->method('getServerParams')
            ->willReturn(['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']);

        $exception = new NotFoundException();

        $result = $this->middleware->handle($exception, $this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertSame(
            'application/json',
            $result->getHeaderLine('Content-Type'),
        );
    }
}
