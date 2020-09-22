<?php

namespace CodeIgniter\Psr7Bridge\Interfaces;

use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\Response;

/**
 * Creates CodeIgniter 4 request and response instances from PSR-7.
 * 
 * @author Agung Sugiarto <me.agungsugiarto@gmail.com>
 */
interface HttpPsr7FactoryInterface
{
    /**
     * Creates CodeIgniter 4 request instance from a PSR-7.
     * 
     * @param CodeIgniter\HTTP\IncomingRequest $requestCodeIgniter
     * @return \PSR\Http\Message\ServerRequestInterface
     */
    public function createRequest(IncomingRequest $requestCodeIgniter);

    /**
     * Creates a Symfony Response instance from a PSR-7 one.
     * 
     * @param CodeIgniter\HTTP\Response $responseCodeIgniter
     * @return \PSR\Http\Message\ResponseInterface
     */
    public function createResponse(Response $responseCodeIgniter);
}