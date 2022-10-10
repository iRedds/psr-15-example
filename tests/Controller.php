<?php

namespace Dev\Tests;

use Dev\Tests\PsrMessage\Response;
use Psr\Http\Message\ResponseInterface;

class Controller
{
    public function index(): ResponseInterface
    {
        return new Response('Controller called');
    }
}