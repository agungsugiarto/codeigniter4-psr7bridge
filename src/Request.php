<?php

namespace CodeIgniter\Psr7Bridge;

use CodeIgniter\HTTP\RequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Interfaces\HeadersInterface;
use Slim\Psr7\Request as Psr7Request;

class Request extends Psr7Request implements RequestInterface
{
    /**
     * @param string           $method        The request method
     * @param UriInterface     $uri           The request URI object
     * @param HeadersInterface $headers       The request headers collection
     * @param array            $cookies       The request cookies collection
     * @param array            $serverParams  The server environment variables
     * @param StreamInterface  $body          The request body object
     * @param array            $uploadedFiles The request uploadedFiles collection
     * 
     * @throws InvalidArgumentException on invalid HTTP method
     */
    public function __construct(
        $method,
        UriInterface $uri,
        HeadersInterface $headers,
        array $cookies,
        array $serverParams,
        StreamInterface $body,
        array $uploadedFiles = []
    ) {
        parent::__construct(
            $method,
            $uri,
            $headers,
            $cookies,
            $serverParams,
            $body,
            $uploadedFiles
        );
    }
    
    /**
     * @inheritdoc
     */
    public function getMethod(bool $upper = false): string
    {
        return parent::getMethod();
    }

    /**
     * @inheritdoc
     * 
     * Not implemented.
     */
    public function getIPAddress(): string
    {
        throw new \Exception("Not implemented. use class {CodeIgniter\\HTTP\\Request}");
    }

    /**
     * @inheritdoc
     * 
     * Not implemented.
     */
    public function isValidIP(string $ip, ?string $which = null): bool
    {
        throw new \Exception("Not implemented. use class {CodeIgniter\\HTTP\\Request}");
    }

    /**
     * @inheritdoc
     * 
     * Not implemented.
     */
    public function getServer($index = null, $filter = null)
    {
        throw new \Exception("Not implemented. use class {CodeIgniter\\HTTP\\Request}");
    }
}
