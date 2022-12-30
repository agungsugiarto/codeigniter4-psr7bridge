<?php

namespace Fluent\HttpMessageBridge\Tests;

use CodeIgniter\HTTP\Request;
use Config\Services;
use PHPUnit\Framework\TestCase;
use Nyholm\Psr7\Factory\Psr17Factory;
use Fluent\HttpMessageBridge\PsrHttpFactory;
use Fluent\HttpMessageBridge\Interfaces\HttpMessageFactoryInterface;

class PsrHttpFactoryTest extends TestCase
{
    private $factory;
    private $tmpDir;

    protected function buildHttpMessageFactory(): HttpMessageFactoryInterface
    {
        $factory = new Psr17Factory();

        return new PsrHttpFactory($factory, $factory, $factory, $factory);
    }

    protected function setUp(): void
    {
        $this->factory = $this->buildHttpMessageFactory();
        $this->tmpDir = sys_get_temp_dir();
    }
}