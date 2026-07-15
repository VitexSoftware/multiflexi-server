<?php

declare(strict_types=1);

/**
 * This file is part of the MultiFlexi package
 *
 * https://multiflexi.eu/
 *
 * (c) Vítězslav Dvořák <http://vitexsoftware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MultiFlexi\Api\Integration;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;

/**
 * Base class for tests that hit a real database.
 *
 * These are opt-in: they only run when DB_CONNECTION (and friends) are
 * present in the environment, matching the pattern used by
 * python3-multiflexi's live-demo contract test. Unlike the generated
 * tests/Api/*Test.php stubs (which openapi-generator overwrites on every
 * `make server` regeneration and therefore stay markTestIncomplete),
 * these exercise the real hand-written Api\Server\* classes end-to-end
 * against the database, the same way the CLI and production do.
 */
abstract class ApiIntegrationTestCase extends TestCase
{
    protected static ResponseFactory $responseFactory;
    protected static ServerRequestFactory $requestFactory;
    protected static StreamFactory $streamFactory;

    public static function setUpBeforeClass(): void
    {
        if (!getenv('DB_CONNECTION')) {
            self::markTestSkipped('DB_CONNECTION not set - skipping DB-backed integration tests. Set DB_CONNECTION/DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD to run them.');
        }

        \Ease\Shared::instanced();

        self::$responseFactory = new ResponseFactory();
        self::$requestFactory = new ServerRequestFactory();
        self::$streamFactory = new StreamFactory();
    }

    protected function jsonRequest(string $method, string $uri, ?array $body = null): \Psr\Http\Message\ServerRequestInterface
    {
        $request = self::$requestFactory->createServerRequest($method, $uri);

        if ($body !== null) {
            $request = $request->withBody(self::$streamFactory->createStream(json_encode($body)));
        }

        return $request;
    }

    protected function newResponse(): \Psr\Http\Message\ResponseInterface
    {
        return self::$responseFactory->createResponse();
    }
}
