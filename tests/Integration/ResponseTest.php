<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim-Psr7/blob/master/LICENSE.md (MIT License)
 */

namespace CodeIgniter\Psr7Bridge\Tests\Integration;

use CodeIgniter\Psr7Bridge\Response;
use Http\Psr7Test\ResponseIntegrationTest;

class ResponseTest extends ResponseIntegrationTest
{
    use BaseTestFactories;
    
    /**
     * @return Response that is used in the tests
     */
    public function createSubject(): Response
    {
        return new Response();
    }
}