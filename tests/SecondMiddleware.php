<?php

namespace Dev\Tests;

use Dev\Tests\PsrMessage\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SecondMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getAttribute('reject') === 'second') {
            return new Response('Rejected in SecondMiddleware');
        }

        $response = $handler->handle($request);

        if (in_array($request->getAttribute('collect'), ['0', '2'], true)) {
            return new Response((string) $response->getBody() . ' + SecondMiddleware');
        }

        return $response;
    }
}