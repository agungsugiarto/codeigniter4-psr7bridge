<?php

namespace Fluent\HttpMessageBridge\Interfaces;

use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface HttpMessageFactoryInterface
{
    /**
     * Creates a PSR-7 Request instance from a CodeIgniter4.
     */
    public function createRequest(Request $request): ServerRequestInterface;

    /**
     * Creates a PSR-7 Response instance from a CodeIgniter4.
     */
    public function createResponse(Response $response): ResponseInterface;
}
