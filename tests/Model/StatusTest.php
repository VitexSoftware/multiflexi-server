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
 * StatusTest Class Doc Comment.
 *
 * @author  OpenAPI Generator team
 *
 * @see    https://github.com/openapitools/openapi-generator
 *
 * @coversDefaultClass \MultiFlexi\Api\Model\Status
 */
class StatusTest extends TestCase
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
     * Test "Status".
     */
    public function testStatus(): void
    {
        $testStatus = new Status();
        $namespacedClassname = Status::getModelsNamespace().'\\Status';
        $this->assertSame('\\'.Status::class, $namespacedClassname);
        $this->assertTrue(
            class_exists($namespacedClassname),
            sprintf('Assertion failed that "%s" class exists', $namespacedClassname),
        );
        self::markTestIncomplete(
            'Test of "Status" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "companies".
     */
    public function testPropertyCompanies(): void
    {
        self::markTestIncomplete(
            'Test of "companies" property in "Status" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "apps".
     */
    public function testPropertyApps(): void
    {
        self::markTestIncomplete(
            'Test of "apps" property in "Status" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "runtemplates".
     */
    public function testPropertyRuntemplates(): void
    {
        self::markTestIncomplete(
            'Test of "runtemplates" property in "Status" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "topics".
     */
    public function testPropertyTopics(): void
    {
        self::markTestIncomplete(
            'Test of "topics" property in "Status" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "credentials".
     */
    public function testPropertyCredentials(): void
    {
        self::markTestIncomplete(
            'Test of "credentials" property in "Status" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "credentialtypes".
     */
    public function testPropertyCredentialtypes(): void
    {
        self::markTestIncomplete(
            'Test of "credentialtypes" property in "Status" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "database".
     */
    public function testPropertyDatabase(): void
    {
        self::markTestIncomplete(
            'Test of "database" property in "Status" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "daemon".
     */
    public function testPropertyDaemon(): void
    {
        self::markTestIncomplete(
            'Test of "daemon" property in "Status" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "timestamp".
     */
    public function testPropertyTimestamp(): void
    {
        self::markTestIncomplete(
            'Test of "timestamp" property in "Status" model has not been implemented yet.',
        );
    }

    /**
     * Test getOpenApiSchema static method.
     *
     * @covers ::getOpenApiSchema
     */
    public function testGetOpenApiSchema(): void
    {
        $schemaArr = Status::getOpenApiSchema();
        $this->assertIsArray($schemaArr);
    }
}