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
 * CustomerTest Class Doc Comment.
 *
 * @author  OpenAPI Generator team
 *
 * @see    https://github.com/openapitools/openapi-generator
 *
 * @coversDefaultClass \MultiFlexi\Api\Model\Customer
 */
class CustomerTest extends TestCase
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
     * Test "Customer".
     */
    public function testCustomer(): void
    {
        $testCustomer = new Customer();
        $namespacedClassname = Customer::getModelsNamespace().'\\Customer';
        $this->assertSame('\\'.Customer::class, $namespacedClassname);
        $this->assertTrue(
            class_exists($namespacedClassname),
            sprintf('Assertion failed that "%s" class exists', $namespacedClassname),
        );
        self::markTestIncomplete(
            'Test of "Customer" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "id".
     */
    public function testPropertyId(): void
    {
        self::markTestIncomplete(
            'Test of "id" property in "Customer" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "enabled".
     */
    public function testPropertyEnabled(): void
    {
        self::markTestIncomplete(
            'Test of "enabled" property in "Customer" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "settings".
     */
    public function testPropertySettings(): void
    {
        self::markTestIncomplete(
            'Test of "settings" property in "Customer" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "email".
     */
    public function testPropertyEmail(): void
    {
        self::markTestIncomplete(
            'Test of "email" property in "Customer" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "firstname".
     */
    public function testPropertyFirstname(): void
    {
        self::markTestIncomplete(
            'Test of "firstname" property in "Customer" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "lastname".
     */
    public function testPropertyLastname(): void
    {
        self::markTestIncomplete(
            'Test of "lastname" property in "Customer" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "password".
     */
    public function testPropertyPassword(): void
    {
        self::markTestIncomplete(
            'Test of "password" property in "Customer" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "login".
     */
    public function testPropertyLogin(): void
    {
        self::markTestIncomplete(
            'Test of "login" property in "Customer" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "datCreate".
     */
    public function testPropertyDatCreate(): void
    {
        self::markTestIncomplete(
            'Test of "datCreate" property in "Customer" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "datSave".
     */
    public function testPropertyDatSave(): void
    {
        self::markTestIncomplete(
            'Test of "datSave" property in "Customer" model has not been implemented yet.',
        );
    }

    /**
     * Test getOpenApiSchema static method.
     *
     * @covers ::getOpenApiSchema
     */
    public function testGetOpenApiSchema(): void
    {
        $schemaArr = Customer::getOpenApiSchema();
        $this->assertIsArray($schemaArr);
    }
}