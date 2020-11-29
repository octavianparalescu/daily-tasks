<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config;

use DailyTasks\Framework\Domain\Key\DomainKey;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testContainerCreation()
    {
        $container = new ConfigContainer($this->createMock(DomainKey::class), [], []);
        $this->assertIsObject($container);
    }

    public function testSimpleKeyFromEnv()
    {
        $testName = 'test1';
        $testValue = 'test2';
        $testValueDefault = 'test3';
        $container = new ConfigContainer(
            $this->createMock(DomainKey::class), [$testName => $testValue], [$testName => $testValueDefault]
        );
        $this->assertEquals($testValue, $container->get($testName, $testName));
    }

    public function testSimpleKeyFromDefault()
    {
        $testName = 'test1';
        $testValue = 'test2';
        $container = new ConfigContainer($this->createMock(DomainKey::class), [], [$testName => $testValue]);
        $this->assertEquals($testValue, $container->get($testName));
    }

    public function testSimpleKeyDoesNotExist()
    {
        $testName = 'test1';
        $container = new ConfigContainer($this->createMock(DomainKey::class), [], []);
        $result = $container->get($testName);
        $this->assertNull($result);
    }
}
