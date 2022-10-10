<?php

namespace Dev;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{

    protected array $middlewares = [];
    protected array $handler;
    /**
     * @param MiddlewareInterface[] $middlewares
     * @param array                 $handler // [Controller::class, 'method']
     */
    public function __construct(array $middlewares, array $handler)
    {
        $this->middlewares = $middlewares;
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (count($this->middlewares) > 0) {
            $middleware = array_shift($this->middlewares);

            return $middleware->process($request, $this);
        } else {
            [$controller, $method] = $this->handler;

            return (new $controller)->$method();
        }
    }
}