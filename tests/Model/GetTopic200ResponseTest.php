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
 * GetTopic200ResponseTest Class Doc Comment.
 *
 * @author  OpenAPI Generator team
 *
 * @see    https://github.com/openapitools/openapi-generator
 *
 * @coversDefaultClass \MultiFlexi\Api\Model\GetTopic200Response
 */
class GetTopic200ResponseTest extends TestCase
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
     * Test "GetTopic200Response".
     */
    public function testGetTopic200Response(): void
    {
        $testGetTopic200Response = new GetTopic200Response();
        $namespacedClassname = GetTopic200Response::getModelsNamespace().'\\GetTopic200Response';
        $this->assertSame('\\'.GetTopic200Response::class, $namespacedClassname);
        $this->assertTrue(
            class_exists($namespacedClassname),
            sprintf('Assertion failed that "%s" class exists', $namespacedClassname),
        );
        self::markTestIncomplete(
            'Test of "GetTopic200Response" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "name".
     */
    public function testPropertyName(): void
    {
        self::markTestIncomplete(
            'Test of "name" property in "GetTopic200Response" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "description".
     */
    public function testPropertyDescription(): void
    {
        self::markTestIncomplete(
            'Test of "description" property in "GetTopic200Response" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "color".
     */
    public function testPropertyColor(): void
    {
        self::markTestIncomplete(
            'Test of "color" property in "GetTopic200Response" model has not been implemented yet.',
        );
    }

    /**
     * Test getOpenApiSchema static method.
     *
     * @covers ::getOpenApiSchema
     */
    public function testGetOpenApiSchema(): void
    {
        $schemaArr = GetTopic200Response::getOpenApiSchema();
        $this->assertIsArray($schemaArr);
    }
}
