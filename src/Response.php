<?php

namespace CodeIgniter\Psr7Bridge;

use CodeIgniter\HTTP\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Interfaces\HeadersInterface;
use Slim\Psr7\Response as Psr7Response;

class Response extends Psr7Response implements ResponseInterface
{
    /**
     * @param int                   $status  The response status code.
     * @param HeadersInterface|null $headers The response headers.
     * @param StreamInterface|null  $body    The response body.
     */
    public function __construct(
        int $status = StatusCodeInterface::STATUS_OK,
        ?HeadersInterface $headers = null,
        ?StreamInterface $body = null
    ) {
        parent::__construct($status, $headers, $body);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatusCode(int $code, string $reason = '')
    {
        return parent::withStatus($code, $reason);
    }

    /**
     * {@inheritdoc}
     */
    public function getReason(): string
    {
        return parent::getReasonPhrase();
    }

    /**
     * {@inheritdoc}
     */
    public function setCache(array $options = [])
    {
        if (empty($options)) {
            return $this;
        }

        parent::withoutHeader('Cache-Control');
        parent::withoutHeader('ETag');

        if (isset($options['etag'])) {
            parent::withAddedHeader('ETag', $options['etag']);
            unset($options['etag']);
        }

        if (isset($options['last-modified'])) {
            $this->setLastModified($options['last-modified']);
            unset($options['last-modified']);
        }

        parent::withAddedHeader('Cache-control', $options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDate(\DateTime $date)
    {
        $date->setTimezone(new \DateTimeZone('UTC'));

        parent::withAddedHeader('Date', $date->format('D, d M Y H:i:s') . ' GMT');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setContentType(string $mime, string $charset = 'UTF-8')
    {
        if ((strpos($mime, 'charset=') < 1) && ! empty($charset)) {
            $mime .= '; charset=' . $charset;
        }

        parent::withoutHeader('Content-Type');
        parent::withAddedHeader('Content-Type', $mime);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastModified($date)
    {
        if ($date instanceof \DateTime) {
            $date->setTimezone(new \DateTimeZone('UTC'));
            parent::withAddedHeader('Last-Modified', $date->format('D, d M Y H:i:s') . ' GMT');
        } elseif (is_string($date)) {
            parent::withAddedHeader('Last-Modified', $date);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function noCache()
    {
        parent::withoutHeader('Cache-control');

        parent::withAddedHeader('Cache-control', ['no-store', 'max-age=0', 'no-cache']);

        return $this;
    }

    /**
     * {@inheritdoc}
     * 
     * Not implemented.
     */
    public function send()
    {
        throw new \Exception("Not implemented!. use class {CodeIgniter\\HTTP\\Request}");
    }
}
