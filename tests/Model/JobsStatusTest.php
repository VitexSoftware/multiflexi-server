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
 * JobsStatusTest Class Doc Comment.
 *
 * @author  OpenAPI Generator team
 *
 * @see    https://github.com/openapitools/openapi-generator
 *
 * @coversDefaultClass \MultiFlexi\Api\Model\JobsStatus
 */
class JobsStatusTest extends TestCase
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
     * Test "JobsStatus".
     */
    public function testJobsStatus(): void
    {
        $testJobsStatus = new JobsStatus();
        $namespacedClassname = JobsStatus::getModelsNamespace().'\\JobsStatus';
        $this->assertSame('\\'.JobsStatus::class, $namespacedClassname);
        $this->assertTrue(
            class_exists($namespacedClassname),
            sprintf('Assertion failed that "%s" class exists', $namespacedClassname),
        );
        self::markTestIncomplete(
            'Test of "JobsStatus" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "timestamp".
     */
    public function testPropertyTimestamp(): void
    {
        self::markTestIncomplete(
            'Test of "timestamp" property in "JobsStatus" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "successfulJobs".
     */
    public function testPropertySuccessfulJobs(): void
    {
        self::markTestIncomplete(
            'Test of "successfulJobs" property in "JobsStatus" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "failedJobs".
     */
    public function testPropertyFailedJobs(): void
    {
        self::markTestIncomplete(
            'Test of "failedJobs" property in "JobsStatus" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "incompleteJobs".
     */
    public function testPropertyIncompleteJobs(): void
    {
        self::markTestIncomplete(
            'Test of "incompleteJobs" property in "JobsStatus" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "totalApplications".
     */
    public function testPropertyTotalApplications(): void
    {
        self::markTestIncomplete(
            'Test of "totalApplications" property in "JobsStatus" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "repeatedJobs".
     */
    public function testPropertyRepeatedJobs(): void
    {
        self::markTestIncomplete(
            'Test of "repeatedJobs" property in "JobsStatus" model has not been implemented yet.',
        );
    }

    /**
     * Test attribute "totalJobs".
     */
    public function testPropertyTotalJobs(): void
    {
        self::markTestIncomplete(
            'Test of "totalJobs" property in "JobsStatus" model has not been implemented yet.',
        );
    }

    /**
     * Test getOpenApiSchema static method.
     *
     * @covers ::getOpenApiSchema
     */
    public function testGetOpenApiSchema(): void
    {
        $schemaArr = JobsStatus::getOpenApiSchema();
        $this->assertIsArray($schemaArr);
    }
}
