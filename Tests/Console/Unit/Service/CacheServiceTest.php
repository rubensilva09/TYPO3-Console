<?php
declare(strict_types=1);
namespace Helhum\Typo3Console\Tests\Unit\Service;

/*
 * This file is part of the TYPO3 Console project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 */

use Helhum\Typo3Console\Service\CacheService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Cache\Exception\NoSuchCacheGroupException;

class CacheServiceTest extends UnitTestCase
{
    /**
     * @var \Helhum\Typo3Console\Service\CacheService
     */
    protected $subject;

    /**
     * Initializes configuration mock and sets the given configuration to the subject
     *
     * @param array $mockedConfiguration
     */
    protected function createCacheServiceWithConfiguration($mockedConfiguration)
    {
        $this->subject = new CacheService($mockedConfiguration);
    }

    /**
     * @test
     */
    public function cacheGroupsAreRetrievedCorrectlyFromConfiguration()
    {
        $this->createCacheServiceWithConfiguration(
            [
                'foo' => ['groups' => ['first', 'second']],
                'bar' => ['groups' => ['third', 'second']],
                'baz' => ['groups' => ['first', 'third']],
            ]
        );

        $expectedResult = [
            'first',
            'second',
            'third',
        ];

        $this->assertSame($expectedResult, $this->subject->getValidCacheGroups());
    }

    /**
     * @test
     */
    public function flushByGroupThrowsExceptionForInvalidGroups()
    {
        $this->expectException(NoSuchCacheGroupException::class);
        $this->createCacheServiceWithConfiguration(
            [
                'foo' => ['groups' => ['first', 'second']],
                'bar' => ['groups' => ['third', 'second']],
                'baz' => ['groups' => ['first', 'third']],
            ]
        );

        $this->subject->flushGroups(['not', 'first']);
    }
}
