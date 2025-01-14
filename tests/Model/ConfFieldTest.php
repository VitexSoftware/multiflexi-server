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
 * The version of the OpenAPI document: 1.1.0
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
 * ConfFieldTest Class Doc Comment.
 *
 * @author  OpenAPI Generator team
 *
 * @see    https://github.com/openapitools/openapi-generator
 *
 * @coversDefaultClass \MultiFlexi\Api\Model\ConfField
 */
class ConfFieldTest extends TestCase
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
     * Test "ConfField".
     */
    public function testConfField(): void
    {
        $testConfField = new ConfField();
        $namespacedClassname = ConfField::getModelsNamespace().'\\ConfField';
        $this->assertSame('\\'.ConfField::class, $namespacedClassname);
        $this->assertTrue(
            class_exists($namespacedClassname),
            sprintf('Assertion failed that "%s" class exists', $namespacedClassname),
        );
        self::markTestIncomplete(
            'Test of "ConfField" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "id".
     */
    public function testPropertyId(): void
    {
        self::markTestIncomplete(
            'Test of "id" property in "ConfField" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "appId".
     */
    public function testPropertyAppId(): void
    {
        self::markTestIncomplete(
            'Test of "appId" property in "ConfField" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "keyname".
     */
    public function testPropertyKeyname(): void
    {
        self::markTestIncomplete(
            'Test of "keyname" property in "ConfField" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "type".
     */
    public function testPropertyType(): void
    {
        self::markTestIncomplete(
            'Test of "type" property in "ConfField" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "description".
     */
    public function testPropertyDescription(): void
    {
        self::markTestIncomplete(
            'Test of "description" property in "ConfField" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "defval".
     */
    public function testPropertyDefval(): void
    {
        self::markTestIncomplete(
            'Test of "defval" property in "ConfField" model has not been implemented yet.',
        );
    }

    /**
     * Test getOpenApiSchema static method.
     *
     * @covers ::getOpenApiSchema
     */
    public function testGetOpenApiSchema(): void
    {
        $schemaArr = ConfField::getOpenApiSchema();
        $this->assertIsArray($schemaArr);
    }
}
