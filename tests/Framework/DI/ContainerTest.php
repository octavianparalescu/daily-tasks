<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI;

use DateTime;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class ContainerTest
 * @covers  \DailyTasks\Framework\DI\Container
 * @package DailyTasks\Framework\DI
 */
class ContainerTest extends TestCase
{
    public function testContainerCreation()
    {
        $container = new Container([]);
        $this->assertIsObject($container);
    }

    public function testDependenciesInTheConstructor()
    {
        $testDateTime = new DateTime();
        $container = new Container([DateTime::class => $testDateTime]);
        $this->assertEquals($testDateTime, $container->get(DateTime::class));
    }

    public function testDependenciesUsingTheSetter()
    {
        $testDateTime = new DateTime();
        $container = new Container([]);
        $container->set(DateTime::class, $testDateTime);
        $this->assertEquals($testDateTime, $container->get(DateTime::class));
    }

    public function testCannotReSetDependency()
    {
        $testDateTime = new DateTime();
        $container = new Container([]);
        $container->set(DateTime::class, $testDateTime);
        try {
            $container->set(DateTime::class, $testDateTime);
            $this->fail('When re-setting a dependency, the container should throw an exception');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
        }
    }
}
