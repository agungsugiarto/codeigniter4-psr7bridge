<?php

namespace CodeIgniter\Psr7Bridge\Tests\Integration;

use CodeIgniter\Psr7Bridge\Request;
use Http\Psr7Test\ServerRequestIntegrationTest;
use Slim\Psr7\Headers;

class ServerRequestTest extends ServerRequestIntegrationTest
{
    use BaseTestFactories;
    
    /**
     * @return Request
     */
    public function createSubject(): Request
    {
        return new Request(
            'GET',
            $this->buildUri('/'),
            new Headers(),
            $_COOKIE,
            $_SERVER,
            $this->buildStream('')
        );
    }
}
