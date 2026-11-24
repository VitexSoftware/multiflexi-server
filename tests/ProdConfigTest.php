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

namespace MultiFlexi\Api\Test;

use PHPUnit\Framework\TestCase;

/**
 * Production DI defaults must follow the MultiFlexi DB_* settings.
 *
 * @runTestsInSeparateProcesses
 */
class ProdConfigTest extends TestCase
{
    public function testPdoFollowsMultiFlexiDatabaseSettings(): void
    {
        $c = $this->build(['DB_CONNECTION' => 'mysql', 'DB_HOST' => 'db.example', 'DB_PORT' => '3307',
            'DB_DATABASE' => 'mf', 'DB_USERNAME' => 'u', 'DB_PASSWORD' => 'p']);
        $this->assertSame('mysql:host=db.example;port=3307;dbname=mf;charset=utf8mb4', $c->get('pdo.dsn'));
        $this->assertSame('u', $c->get('pdo.username'));
        $this->assertSame('p', $c->get('pdo.password'));
    }

    public function testSqliteDsn(): void
    {
        $c = $this->build(['DB_CONNECTION' => 'sqlite', 'DB_DATABASE' => '/tmp/mf.db']);
        $this->assertSame('sqlite:/tmp/mf.db', $c->get('pdo.dsn'));
    }

    /**
     * getenv() returns false when unset; strtolower(false) is fatal under strict_types.
     */
    public function testIndexHandlesUnsetAppEnv(): void
    {
        $index = (string) file_get_contents(__DIR__.'/../public/index.php');
        $this->assertStringContainsString("getenv('APP_ENV') ?: 'prod'", $index);
        $this->assertStringNotContainsString("getenv('APP_ENV') ?? 'prod'", $index);
    }

    /**
     * @param array<string, string> $env
     */
    private function build(array $env): \DI\Container
    {
        foreach ($env as $key => $value) {
            putenv($key.'='.$value);
        }

        $builder = new \DI\ContainerBuilder();
        $builder->addDefinitions(__DIR__.'/../config/prod/default.inc.php');

        return $builder->build();
    }
}
