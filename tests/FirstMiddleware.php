<?php

namespace Dev\Tests;

use Dev\Tests\PsrMessage\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FirstMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getAttribute('reject') === 'first') {
            return new Response('Rejected in FirstMiddleware');
        }

        $response = $handler->handle($request);

        if (in_array($request->getAttribute('collect'), ['0', '1'], true)) {
            return new Response((string) $response->getBody() . ' + FirstMiddleware');
        }

        return $response;
    }
}