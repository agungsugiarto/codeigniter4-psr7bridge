<?php

namespace Fluent\HttpMessageBridge;

use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\Response;
use Fluent\HttpMessageBridge\Interfaces\HttpMessageFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;

class PsrHttpFactory implements HttpMessageFactoryInterface
{
    /**
     * @var ServerRequestFactoryInterface
     */
    protected $serverRequestFactory;

    /**
     * @var StreamFactoryInterface
     */
    protected $streamFactory;

    /**
     * @var UploadedFileFactoryInterface
     */
    protected $uploadedFileFactory;

    /**
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

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
    public function createRequest(Request $request): ServerRequestInterface
    {
        $requestFactory = $this->serverRequestFactory->createServerRequest(
            $request->getMethod(true),
            $request->getUri()->__toString(),
            $request->getServer()
        );

        foreach ($request->headers() as $value) {
            try {
                $requestFactory = $requestFactory->withHeader($value->getName(), $value->getValue());
            } catch (\InvalidArgumentException $e) {
                // ignore invalid header
            }
        }

        $body = $request->getBody() === null
            ? $this->streamFactory->createStreamFromResource('php://memory', 'wb+')
            : $this->streamFactory->createStreamFromResource($request->getBody());

        // Get the property query from the URI, it is not accessible.
        $reflected = new \ReflectionClass($request->getUri());
        $property = $reflected->getProperty('query');
        $property->setAccessible(true);
        $queryParams = $property->getValue($request->getUri());

        $requestFactory = $requestFactory
            ->withBody($body)
            ->withUploadedFiles($request->getFiles())
            ->withCookieParams($request->getCookie())
            ->withQueryParams($queryParams)
            ->withParsedBody($request->getVar());

        return $requestFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse(Response $response): ResponseInterface
    {
        $responseFactory = $this->responseFactory->createResponse(
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );

        $responseFactory = $responseFactory->withBody(
            $this->streamFactory->createStream(
                $response->getBody() ?? 'php://memory'
            )
        );

        $headers = $response->headers();
        $cookies = $response->getCookies();
        if (! empty($cookies)) {
            $headers['Set-Cookie'] = [];

            foreach ($cookies as $cookie) {
                $headers['Set-Cookie'][] = $cookie->__toString();
            }
        }

        foreach ($headers as $value) {
            try {
                $responseFactory = $responseFactory->withHeader($value->getName(), $value->getValue());
            } catch (\InvalidArgumentException $e) {
                // ignore invalid header
            }
        }

        $protocolVersion = $response->getProtocolVersion();
        $responseFactory = $responseFactory->withProtocolVersion($protocolVersion);

        return $responseFactory;
    }
}
