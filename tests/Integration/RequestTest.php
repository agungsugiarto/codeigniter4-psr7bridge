<?php

namespace CodeIgniter\Psr7Bridge\Tests\Integration;

use CodeIgniter\Psr7Bridge\Request;
use Http\Psr7Test\RequestIntegrationTest;
use Psr\Http\Message\RequestInterface;
use Slim\Psr7\Headers;

class RequestTest extends RequestIntegrationTest
{
    use BaseTestFactories;

    public function createSubject(): RequestInterface
    {
        return new Request(
            'GET',
            $this->buildUri('/'),
            new Headers(),
            [],
            [],
            $this->buildStream('')
        );
    }
}