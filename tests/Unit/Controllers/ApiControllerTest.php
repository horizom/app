<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers;

use App\Controllers\ApiController;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Unit tests for ApiController.
 *
 * Because ApiController relies on framework helpers (response(), app()),
 * we use function stubs defined in bootstrap to isolate the unit under test.
 */
final class ApiControllerTest extends TestCase
{
    private ApiController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ApiController();
    }

    public function testIndexReturnsResponseInterface(): void
    {
        $response = $this->controller->index();

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testStatusReturnsResponseInterface(): void
    {
        $response = $this->controller->status();

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testVersionReturnsResponseInterface(): void
    {
        $response = $this->controller->version();

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testStatusResponseContainsUpPayload(): void
    {
        $response = $this->controller->status();
        $body = (string) $response->getBody();
        $decoded = json_decode($body, true);

        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('status', $decoded);
        $this->assertSame('UP', $decoded['status']);
    }

    public function testStatusResponseHasJsonContentType(): void
    {
        $response = $this->controller->status();

        $this->assertSame(
            'application/json',
            $response->getHeaderLine('Content-Type'),
        );
    }

    public function testVersionResponseContainsVersionKey(): void
    {
        $response = $this->controller->version();
        $body = (string) $response->getBody();
        $decoded = json_decode($body, true);

        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('version', $decoded);
    }
}
