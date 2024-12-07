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

/**
 * This is an example of using OAuth2 Application Flow in a specification to describe security to your API.
 * The version of the OpenAPI document: 1.0.0
 * Contact: vitezslav.dvorak@spojenet.cz
 * Generated by: https://github.com/openapitools/openapi-generator.git.
 */

/**
 * NOTE: This class is auto generated by the openapi generator program.
 * https://github.com/openapitools/openapi-generator
 * Please update the test case below to test the model.
 */

namespace MultiFlexi\Api\Model;

use PHPUnit\Framework\TestCase;

/**
 * CredentialTest Class Doc Comment.
 *
 * @author  OpenAPI Generator team
 *
 * @see    https://github.com/openapitools/openapi-generator
 *
 * @coversDefaultClass \MultiFlexi\Api\Model\Credential
 */
class CredentialTest extends TestCase
{
    /**
     * Setup before running any test cases.
     */
    public static function setUpBeforeClass(): void
    {
    }

    /**
     * Clean up after running all test cases.
     */
    public static function tearDownAfterClass(): void
    {
    }

    /**
     * Setup before running each test case.
     */
    protected function setUp(): void
    {
    }

    /**
     * Clean up after running each test case.
     */
    protected function tearDown(): void
    {
    }

    /**
     * Test "Credential".
     */
    public function testCredential(): void
    {
        $testCredential = new Credential();
        $namespacedClassname = Credential::getModelsNamespace().'\\Credential';
        $this->assertSame('\\'.Credential::class, $namespacedClassname);
        $this->assertTrue(
            class_exists($namespacedClassname),
            sprintf('Assertion failed that "%s" class exists', $namespacedClassname),
        );
        self::markTestIncomplete(
            'Test of "Credential" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "id".
     */
    public function testPropertyId(): void
    {
        self::markTestIncomplete(
            'Test of "id" property in "Credential" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "userId".
     */
    public function testPropertyUserId(): void
    {
        self::markTestIncomplete(
            'Test of "userId" property in "Credential" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "companyId".
     */
    public function testPropertyCompanyId(): void
    {
        self::markTestIncomplete(
            'Test of "companyId" property in "Credential" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "name".
     */
    public function testPropertyName(): void
    {
        self::markTestIncomplete(
            'Test of "name" property in "Credential" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "type".
     */
    public function testPropertyType(): void
    {
        self::markTestIncomplete(
            'Test of "type" property in "Credential" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "value".
     */
    public function testPropertyValue(): void
    {
        self::markTestIncomplete(
            'Test of "value" property in "Credential" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "datCreate".
     */
    public function testPropertyDatCreate(): void
    {
        self::markTestIncomplete(
            'Test of "datCreate" property in "Credential" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "datUpdate".
     */
    public function testPropertyDatUpdate(): void
    {
        self::markTestIncomplete(
            'Test of "datUpdate" property in "Credential" model has not been implemented yet.',
        );
    }

    /**
     * Test getOpenApiSchema static method.
     *
     * @covers ::getOpenApiSchema
     */
    public function testGetOpenApiSchema(): void
    {
        $schemaArr = Credential::getOpenApiSchema();
        $this->assertIsArray($schemaArr);
    }
}
