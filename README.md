# CodeIgniter4 PSR-7 Bridge

## The PSR-7 Bridge

> The PSR-7 bridge converts [codeigniter4-http](https://codeigniter4.github.io/userguide/incoming/message.html)
objects from and to objects implementing HTTP message interfaces defined
by the [PSR-7](http://www.php-fig.org/psr/psr-7/).

## Table of Contents

- <a href="#installation">Installation</a>
- <a href="#usage">Usage</a>
    - <a href="converting-from-incomingrequest-objects-to-psr-7">Converting from IncomingRequest Objects to PSR-7</a>

## Installation

```sh
$ composer require agungsugiarto/codeigniter4-psr7bridge
```

The bridge also needs a PSR-7 and [PSR-17](https://www.php-fig.org/psr/psr-17/) implementation to convert
``IncomingRequest`` objects to PSR-7 objects. The following command installs the
``nyholm/psr7`` library, a lightweight and fast PSR-7 implementation.
```sh
$ composer require nyholm/psr7
```

## Usage
### Converting from IncomingRequest Objects to PSR-7
---------------------------------------------------

The bridge provides an interface of a factory called
``CodeIgniter\Psr7Bridge\Interfaces\HttpPsr7FactoryInterface``
that builds objects implementing PSR-7 interfaces from ``IncommingRequest`` objects.

The following code snippet explains how to convert a ``CodeIgniter\HTTP\IncomingRequest``
to a ``Nyholm\Psr7\ServerRequest`` class implementing the
``Psr\Http\Message\ServerRequestInterface`` interface:

```php
<?php

use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\Psr7Bridge\HttpPsr7Factory;
use Nyholm\Psr7\Factory\Psr17Factory;

$requestCodeIgniter = new IncomingRequest(config('App'));

$psr17Factory = new Psr17Factory();
$psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
$psrRequest = $psrHttpFactory->createRequest($requestCodeIgniter);
```

And now from a ``CodeIgniter\HTTP\Response`` to a
``Nyholm\Psr7\Response`` class implementing the
``Psr\Http\Message\ResponseInterface`` interface:

```php
<?php

use CodeIgniter\HTTP\Response;
use CodeIgniter\Psr7Bridge\HttpPsr7Factory;
use Nyholm\Psr7\Factory\Psr17Factory;

$responseCodeIgniter = new Response(config('App'));

$psr17Factory = new Psr17Factory();
$psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
$psrResponse = $psrHttpFactory->createResponse($responseCodeIgniter);
```

## License

Released under the MIT License, see [LICENSE](https://github.com/agungsugiarto/codeigniter4-psr7bridge/blob/master/LICENSE.md).
