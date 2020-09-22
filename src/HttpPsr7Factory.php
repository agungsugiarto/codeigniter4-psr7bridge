<?php

namespace CodeIgniter\Psr7Bridge;

use CodeIgniter\HTTP\DownloadResponse;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Psr7Bridge\Interfaces\HttpPsr7FactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;

/**
 * Builds Psr/HttpMessage interface using a PSR-17 implementation.
 * 
 * @author Agung Sugiarto <me.agungsugiarto@gmail.com>
 */
class HttpPsr7Factory implements HttpPsr7FactoryInterface
{
    /**
     * @var \PSR\Http\Message\ServerRequestFactoryInterface
     */
    protected $serverRequestFactory;

    /**
     * @var \Psr\Http\Message\StreamFactoryInterface
     */
    protected $streamFactory;

    /**
     * @var \Psr\Http\Message\UploadedFileFactoryInterface
     */
    protected $uploadedFileFactory;

    /**
     * @var \Psr\Http\Message\ResponseFactoryInterface
     */
    protected $responseFactory;
    
    /**
     * Http Psr7 Factory Constructor.
     * 
     * @param \PSR\Http\Message\ServerRequestFactoryInterface $serverRequestFactory
     * @param \Psr\Http\Message\UploadedFileFactoryInterface  $streamFactory
     * @param \Psr\Http\Message\UploadedFileFactoryInterface  $uploadedFileFactory
     * @param \Psr\Http\Message\ResponseFactoryInterface      $responseFactory
     *
     * @return void
     */
    public function __construct(
        ServerRequestFactoryInterface $serverRequestFactory,
        StreamFactoryInterface $streamFactory,
        UploadedFileFactoryInterface $uploadedFileFactory,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->serverRequestFactory = $serverRequestFactory;
        $this->streamFactory = $streamFactory;
        $this->uploadedFileFactory = $uploadedFileFactory;
        $this->responseFactory = $responseFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(IncomingRequest $requestCodeIgniter)
    {
        $request = $this->serverRequestFactory->createServerRequest(
            $requestCodeIgniter->getMethod(),
            $requestCodeIgniter->uri->getQuery(),
            $requestCodeIgniter->getServer()
        );

        foreach ($requestCodeIgniter->getHeaders() as $name => $value) {
            $request = $request->withHeader($name, $value->getValue());
        }

        $body = $requestCodeIgniter->getBody() === null
            ? $this->streamFactory->createStreamFromResource('php://memory', 'wb+')
            : $requestCodeIgniter->getBody();

        $request = $request
            ->withBody($body)
            ->withCookieParams($requestCodeIgniter->getCookie())
            ->withQueryParams($requestCodeIgniter->uri->getSegments())
            ->withParsedBody($requestCodeIgniter->getVar());

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse(Response $responseCodeIgniter)
    {
        $response = $this->responseFactory->createResponse(
            $responseCodeIgniter->getStatusCode(),
            $responseCodeIgniter->getReason()
        );

        if ($responseCodeIgniter instanceof DownloadResponse) {
            $stream = $this->streamFactory->createStreamFromFile('php://temp', 'wb+');
            $stream->write($responseCodeIgniter->sendBody());

            $response->withBody($stream);
        }

        foreach ($responseCodeIgniter->getHeaders() as $name => $value) {
            $response = $response->withHeader($name, $value->getValue());
        }

        $response = $response->withProtocolVersion($responseCodeIgniter->getProtocolVersion());

        return $response;
    }
}
