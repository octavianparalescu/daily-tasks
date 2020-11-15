<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI;

use DailyTasks\Framework\DI\TestClasses\TestClassCircular1;
use DailyTasks\Framework\DI\TestClasses\TestClassNoConstructor;
use DailyTasks\Framework\DI\TestClasses\TestClassNoTypeHint;
use DailyTasks\Framework\DI\TestClasses\TestClassOneLevelDependency;
use DailyTasks\Framework\DI\TestClasses\TestClassTwoLevelsDependencies;
use DailyTasks\Framework\DI\TestClasses\TestInterface;
use PHPUnit\Framework\TestCase;
use stdClass;
use Throwable;

class ResolverTest extends TestCase
{
    public function testShouldReturnDirectlyIfInContainer()
    {
        $className = stdClass::class;
        $object = new $className();
        $container = new Container([$className => $object]);
        $resolver = new Resolver($container);
        $this->assertEquals($object, $resolver->resolve($className));
        $this->assertNotEmpty($container->get($className));
    }

    public function testShouldThrowIfClassDoesNotExist()
    {
        $container = new Container();
        $resolver = new Resolver($container);
        try {
            $resolver->resolve('test_class_should_not_exist');
            $this->fail();
        } catch (Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
            $this->assertStringContainsString('not found', $exception->getMessage());
        }
    }

    public function testShouldThrowIfClassIsNotInstantiable()
    {
        $container = new Container();
        $resolver = new Resolver($container);
        try {
            $resolver->resolve(TestInterface::class);
            $this->fail();
        } catch (Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
            $this->assertStringContainsString('instantiable', $exception->getMessage());
        }
    }

    public function testShouldReturnDirectlyIfNoConstructor()
    {
        $className = TestClassNoConstructor::class;
        $object = new $className();
        $container = new Container();
        $resolver = new Resolver($container);
        $this->assertEmpty($container->get($className));
        $this->assertEquals($object, $resolver->resolve($className));
        $this->assertNotEmpty($container->get($className));
    }

    public function testShouldReturnDirectlyIfNoConstructorParameters()
    {
        $className = TestClassNoConstructor::class;
        $object = new $className();
        $container = new Container();
        $resolver = new Resolver($container);
        $this->assertEmpty($container->get($className));
        $this->assertEquals($object, $resolver->resolve($className));
        $this->assertNotEmpty($container->get($className));
    }

    public function testShouldInstantiateFirstLevel()
    {
        $className = TestClassOneLevelDependency::class;
        $container = new Container();
        $resolver = new Resolver($container);
        $this->assertEmpty($container->get($className));
        $this->assertInstanceOf($className, $resolver->resolve($className));
        $this->assertNotEmpty($container->get($className));
        $this->assertInstanceOf($className, $container->get($className));
    }

    public function testShouldInstantiateSecondLevel()
    {
        $className = TestClassTwoLevelsDependencies::class;
        $container = new Container();
        $resolver = new Resolver($container);
        $this->assertEmpty($container->get($className));
        $this->assertInstanceOf($className, $resolver->resolve($className));
        $this->assertNotEmpty($container->get($className));
        $this->assertInstanceOf($className, $container->get($className));
        $objectInContainer = $container->get($className);
        $this->assertInstanceOf(TestClassOneLevelDependency::class, $objectInContainer->classOneLevelDependency);
    }

    public function testShouldThrowIfIsCircularDependencyDirectly()
    {
        $container = new Container();
        $resolver = new Resolver($container);
        try {
            $resolver->resolve(TestClassCircular1::class);
            $this->fail();
        } catch (Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
            $this->assertStringContainsString('circular', $exception->getMessage());
        }
    }

    public function testShouldThrowIfParameterNoTypeHint()
    {
        $container = new Container();
        $resolver = new Resolver($container);
        try {
            $resolver->resolve(TestClassNoTypeHint::class);
            $this->fail();
        } catch (Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
            $this->assertStringContainsString('type-hinted', $exception->getMessage());
        }
    }

    public function testShouldThrowIfIsCircularDependencyLevelTwo()
    {
        $container = new Container();
        $resolver = new Resolver($container);
        try {
            $resolver->resolve(TestClassCircular1::class);
            $this->fail();
        } catch (Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
            $this->assertStringContainsString('circular', $exception->getMessage());
        }
    }
}
