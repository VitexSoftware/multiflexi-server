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
 * Regression test for the apps.json array-vs-object bug: the schema
 * declares `type: array`, but the handler used to build an ID-keyed
 * associative array and hand it to json_encode, which PHP renders as a
 * JSON object.
 */
class AppApiIntegrationTest extends ApiIntegrationTestCase
{
    public function testListAppsReturnsAJsonArrayNotAnObject(): void
    {
        $api = new \MultiFlexi\Api\Server\AppApi();
        $response = $api->listApps($this->jsonRequest('GET', '/apps.json'), $this->newResponse(), 'json');

        static::assertSame(200, $response->getStatusCode());
        $decoded = json_decode((string) $response->getBody(), true);
        static::assertIsArray($decoded);
        static::assertSame($decoded === [] ? [] : range(0, \count($decoded) - 1), array_keys($decoded), 'response must be a sequential JSON array, not an ID-keyed object');

        if ($decoded !== []) {
            static::assertArrayHasKey('id', $decoded[0], 'first element must be an App record, confirming a true list');
        }
    }

    public function testGetAppByIdExitCodesIsKeyedByCodeNotAList(): void
    {
        $api = new \MultiFlexi\Api\Server\AppApi();
        $listResponse = $api->listApps($this->jsonRequest('GET', '/apps.json'), $this->newResponse(), 'json');
        $apps = json_decode((string) $listResponse->getBody(), true);

        if (empty($apps)) {
            static::markTestSkipped('no apps in database to test against');
        }

        $response = $api->getAppById($this->jsonRequest('GET', '/app/'.$apps[0]['id'].'.json'), $this->newResponse(), (int) $apps[0]['id'], 'json');
        static::assertSame(200, $response->getStatusCode());
        $decoded = json_decode((string) $response->getBody(), true);
        static::assertArrayHasKey('exitCodes', $decoded);

        if ($decoded['exitCodes'] !== []) {
            static::assertArrayNotHasKey(0, $decoded['exitCodes'], 'exitCodes must be keyed by exit-code string, not a sequential list');
        }
    }
}
