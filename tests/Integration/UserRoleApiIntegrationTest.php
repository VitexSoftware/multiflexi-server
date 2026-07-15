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

/**
 * End-to-end test for UserRoleApi against the rbac_roles / rbac_user_roles
 * tables formalized in multiflexi-database migration
 * 20260715015632_rbac_roles.php. Uses a dedicated throwaway user so it
 * never touches a real account's role assignments.
 */
class UserRoleApiIntegrationTest extends ApiIntegrationTestCase
{
    private static ?int $testUserId = null;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $user = new \MultiFlexi\User();
        $login = 'phpunit_userrole_test_'.bin2hex(random_bytes(4));
        $user->dbsync([
            'login' => $login,
            'email' => $login.'@example.invalid',
            'firstname' => 'PHPUnit',
            'lastname' => 'Test',
            'enabled' => 1,
        ]);
        self::$testUserId = (int) $user->getMyKey();

        if (self::$testUserId <= 0) {
            static::markTestSkipped('could not create a throwaway test user');
        }
    }

    public static function tearDownAfterClass(): void
    {
        if (self::$testUserId) {
            $user = new \MultiFlexi\User(self::$testUserId);
            $user->deleteFromSQL(['id' => self::$testUserId]);
        }
    }

    public function testGetUserRolesOnFreshUserIsEmpty(): void
    {
        $api = new \MultiFlexi\Api\Server\UserRoleApi();
        $response = $api->getUserRoles($this->jsonRequest('GET', '/user/'.self::$testUserId.'/roles.json'), $this->newResponse(), self::$testUserId, 'json');

        static::assertSame(200, $response->getStatusCode());
        static::assertSame([], json_decode((string) $response->getBody(), true));
    }

    public function testGetUserRolesOnMissingUserIs404(): void
    {
        $api = new \MultiFlexi\Api\Server\UserRoleApi();
        $response = $api->getUserRoles($this->jsonRequest('GET', '/user/999999999/roles.json'), $this->newResponse(), 999999999, 'json');

        static::assertSame(404, $response->getStatusCode());
    }

    public function testSetUserRolesAssignsAndReplaces(): void
    {
        $api = new \MultiFlexi\Api\Server\UserRoleApi();

        $response = $api->setUserRoles(
            $this->jsonRequest('POST', '/user/'.self::$testUserId.'/roles/', ['roles' => ['viewer', 'user']]),
            $this->newResponse(),
            self::$testUserId,
        );
        static::assertSame(200, $response->getStatusCode());
        $decoded = json_decode((string) $response->getBody(), true);
        static::assertSame(['user', 'viewer'], $decoded['roles'], 'roles come back ordered by name');

        // replace=true (default) must drop roles not in the new list
        $response = $api->setUserRoles(
            $this->jsonRequest('POST', '/user/'.self::$testUserId.'/roles/', ['roles' => ['admin']]),
            $this->newResponse(),
            self::$testUserId,
        );
        static::assertSame(200, $response->getStatusCode());
        static::assertSame(['admin'], json_decode((string) $response->getBody(), true)['roles']);
    }

    public function testSetUserRolesRejectsUnknownRole(): void
    {
        $api = new \MultiFlexi\Api\Server\UserRoleApi();
        $response = $api->setUserRoles(
            $this->jsonRequest('POST', '/user/'.self::$testUserId.'/roles/', ['roles' => ['this_role_does_not_exist']]),
            $this->newResponse(),
            self::$testUserId,
        );

        static::assertSame(400, $response->getStatusCode());
        static::assertArrayHasKey('error', json_decode((string) $response->getBody(), true));
    }
}
