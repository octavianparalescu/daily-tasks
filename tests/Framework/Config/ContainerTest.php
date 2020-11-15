<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config;

use DailyTasks\Framework\Config\Converter\ComposedFieldNameConverter;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testContainerCreation()
    {
        $container = new Container([], []);
        $this->assertIsObject($container);
    }

    public function testSimpleKeyFromEnv()
    {
        $testName = 'test1';
        $testValue = 'test2';
        $testValueDefault = 'test3';
        $container = new Container([$testName => $testValue], [$testName => $testValueDefault]);
        $this->assertEquals($testValue, $container->get($testName));
    }

    public function testSimpleKeyFromDefault()
    {
        $testName = 'test1';
        $testValue = 'test2';
        $container = new Container([], [$testName => $testValue]);
        $this->assertEquals($testValue, $container->get($testName));
    }

    public function testSimpleKeyDoesNotExist()
    {
        $testName = 'test1';
        $container = new Container([], []);
        try {
            $container->get($testName);
            $this->fail();
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
        }
    }
}
