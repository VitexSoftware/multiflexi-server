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
 * Regression test for job detail fetching: MultiFlexi\Job (Ease\Sand-based)
 * must be able to open its own DB connection independently of whatever the
 * request-handling code path already set up, the same way MultiFlexi\User
 * does inside BasicAuthenticator on every request. If DB_TYPE/DB_CONNECTION
 * were never loaded into the process environment (the bug fixed in
 * public/index.php - see Shared::init() there), any Ease\Sand-based model
 * throws "Unimplemented Database type" the first time it opens a connection.
 */
class JobApiIntegrationTest extends ApiIntegrationTestCase
{
    public function testGetJobByIdReturnsJobData(): void
    {
        $api = new \MultiFlexi\Api\Server\JobApi();
        $listResponse = $api->listJobs($this->jsonRequest('GET', '/jobs.json'), $this->newResponse(), 'json');
        static::assertSame(200, $listResponse->getStatusCode());

        $jobs = json_decode((string) $listResponse->getBody(), true);

        if (empty($jobs)) {
            static::markTestSkipped('no jobs in database to test against');
        }

        $id = (int) array_key_first($jobs);
        $response = $api->getJobById($this->jsonRequest('GET', '/job/'.$id.'.json'), $this->newResponse(), $id, 'json');

        static::assertSame(200, $response->getStatusCode());

        $decoded = json_decode((string) $response->getBody(), true);
        static::assertSame($id, (int) $decoded['id']);
    }
}
