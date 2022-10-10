<?php
namespace Dev\Tests;

use Dev\RequestHandler;
use Dev\Tests\PsrMessage\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Dev\RequestHandler
 */
class RequestHandlerTest extends TestCase
{
    protected RequestHandler $handler;

    public function setUp(): void
    {
        $this->handler = new RequestHandler(
            [
                new FirstMiddleware(),
                new SecondMiddleware(),
            ],
            [Controller::class, 'index']
        );
    }

    public function testDefault(): void
    {
        $request = new ServerRequest();
        $response = $this->handler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame('Controller called', (string) $response->getBody());
    }

    public function testRejectInFirstMiddleware(): void
    {
        $request = new ServerRequest();
        $request->withAttribute('reject', 'first');

        $response = $this->handler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame('Rejected in FirstMiddleware', (string) $response->getBody());
    }

    public function testRejectInSecondMiddleware(): void
    {
        $request = new ServerRequest();
        $request->withAttribute('reject', 'second');

        $response = $this->handler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame('Rejected in SecondMiddleware', (string) $response->getBody());
    }


    /**
     * @dataProvider editResponseDataProvider
     */
    public function testEditResponseInMiddleware(string $type, string $body): void
    {
        $request = new ServerRequest();
        $request->withAttribute('collect', $type);

        $response = $this->handler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame($body, (string) $response->getBody());
    }

    public function editResponseDataProvider(): array
    {
        return [
            'Controller + all'    => ['0', 'Controller called + SecondMiddleware + FirstMiddleware'],
            'Controller + first'  => ['1', 'Controller called + FirstMiddleware'],
            'Controller + second' => ['2', 'Controller called + SecondMiddleware'],
        ];
    }
}