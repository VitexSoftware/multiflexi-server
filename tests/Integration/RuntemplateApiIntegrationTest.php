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
 * Regression test for three RunTemplate bugs fixed in this session:
 *  - listRuntemplates/getRunTemplateById returned an ID-keyed object
 *    instead of a JSON array for the list endpoint;
 *  - `active` came back as int (0/1) instead of bool on single-record GET;
 *  - `success`/`fail` came back as '' instead of null when empty.
 */
class RuntemplateApiIntegrationTest extends ApiIntegrationTestCase
{
    public function testListRunTemplatesReturnsAJsonArrayWithBooleanActive(): void
    {
        $api = new \MultiFlexi\Api\Server\RuntemplateApi();
        $response = $api->listRuntemplates($this->jsonRequest('GET', '/runtemplates.json'), $this->newResponse(), 'json');

        static::assertSame(200, $response->getStatusCode());
        $decoded = json_decode((string) $response->getBody(), true);
        static::assertIsArray($decoded);
        static::assertSame($decoded === [] ? [] : range(0, \count($decoded) - 1), array_keys($decoded), 'response must be a sequential JSON array, not an ID-keyed object');

        if ($decoded === []) {
            static::markTestSkipped('no runtemplates in database to test against');
        }

        static::assertIsBool($decoded[0]['active']);
    }

    public function testGetRunTemplateByIdActiveIsBooleanAndEmptySuccessFailAreNull(): void
    {
        $api = new \MultiFlexi\Api\Server\RuntemplateApi();
        $listResponse = $api->listRuntemplates($this->jsonRequest('GET', '/runtemplates.json'), $this->newResponse(), 'json');
        $runtemplates = json_decode((string) $listResponse->getBody(), true);

        if (empty($runtemplates)) {
            static::markTestSkipped('no runtemplates in database to test against');
        }

        $id = (int) $runtemplates[0]['id'];
        $response = $api->getRunTemplateById($this->jsonRequest('GET', '/runtemplate/'.$id.'.json'), $this->newResponse(), $id, 'json');
        static::assertSame(200, $response->getStatusCode());

        $decoded = json_decode((string) $response->getBody(), true);
        static::assertIsBool($decoded['active'], 'active must decode to a JSON boolean, not 0/1');

        foreach (['success', 'fail'] as $field) {
            static::assertNotSame('', $decoded[$field], "{$field} must be null when empty, not an empty string");
        }
    }
}
