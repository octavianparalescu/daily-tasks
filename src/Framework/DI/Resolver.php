<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI;


use DailyTasks\Framework\DI\Contract\ServiceFactoryInterface;
use ReflectionClass;
use ReflectionException;
use Throwable;

class Resolver
{
    /**
     * @var Container
     */
    private Container $container;
    private array $rules = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $className
     * @param array  $previousDependencies
     *
     * @return object
     * @throws Exception
     */
    public function resolve(string $className, array $previousDependencies = []): object
    {
        if ($className === get_class($this)) {
            return $this;
        }

        $object = $this->container->get($className);

        if ($object !== null) {
            return $object;
        }

        if ($staticReplacement = $this->getStaticReplacement($className)) {
            $className = $staticReplacement;
        }

        if (in_array($className, $previousDependencies)) {
            throw new Exception(
                'Class ' . $className . ' has a circular dependency. ' . $this->getDependencyGraph($previousDependencies)
            );
        }

        if ($factoryClass = $this->getFactory($className)) {
            /** @var ServiceFactoryInterface $factory */
            $factory = $this->resolve($factoryClass, array_merge($previousDependencies, [$className]));

            $object = $factory->createInstance();
        } else {
            try {
                $class = new ReflectionClass($className);
            } catch (ReflectionException $exception) {
                throw new Exception(
                    'Class ' . $className . ' not found. ' . $this->getDependencyGraph($previousDependencies), 0, $exception
                );
            }

            if (!$class->isInstantiable()) {
                throw new Exception(
                    'Class ' . $className . ' is not instantiable. ' . $this->getDependencyGraph($previousDependencies)
                );
            }

            $constructor = $class->getConstructor();
            if ($constructor === null) {
                // There are no parameters (no constructor), resolving is final
                $object = $this->initializeObject($className, [], $previousDependencies);
            } else {
                $parameters = $constructor->getParameters();
                if (empty($parameters)) {
                    // There are no parameters (constructor with no parameters), resolving is final
                    $object = $this->initializeObject($className, [], $previousDependencies);
                } else {
                    $dependencies = [];
                    foreach ($parameters as $parameter) {
                        $parameterClass = $parameter->getClass();
                        if ($parameterClass === null) {
                            throw new Exception(
                                'Class ' . $className . ' is not instantiable, parameter ' . $parameter->getName(
                                ) . ' doesn\'t have a type-hinted class. ' . $this->getDependencyGraph($previousDependencies)
                            );
                        } else {
                            $dependency = $this->container->get($parameterClass->getName());
                            if (!$dependency) {
                                $dependency = $this->resolve(
                                    $parameterClass->getName(),
                                    array_merge($previousDependencies, [$className])
                                );
                            }
                            $dependencies [] = $dependency;
                        }
                    }

                    $object = $this->initializeObject($className, $dependencies, $previousDependencies);
                }
            }
        }

        $this->container->set($className, $object);

        return $object;
    }

    /**
     * @param array $previousDependencies
     *
     * @return string
     */
    private function getDependencyGraph(array $previousDependencies): string
    {
        return 'Graph: ' . implode(',', $previousDependencies);
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @param string $className
     * @param array  $dependencies
     * @param array  $previousDependencies
     *
     * @return mixed
     * @throws Exception
     */
    private function initializeObject(string $className, array $dependencies, array $previousDependencies)
    {
        try {
            if (empty($dependencies)) {
                return new $className();
            } else {
                return new $className(...$dependencies);
            }
        } catch (Throwable $exception) {
            throw new Exception(
                "An exception was thrown whilst initializing $className: " . $exception->getMessage() . $this->getDependencyGraph(
                    $previousDependencies
                ), 0, $exception
            );
        }
    }

    private function getStaticReplacement(string $className)
    {
        if (isset($this->rules['static']) && isset($this->rules['static'][$className])) {
            return $this->rules['static'][$className];
        }

        return false;
    }

    /**
     * @param array $rules
     */
    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    private function getFactory(string $className)
    {
        if (isset($this->rules['factory']) && isset($this->rules['factory'][$className])) {
            return $this->rules['factory'][$className];
        }

        if (class_exists($className . 'Factory')) {
            return $className . 'Factory';
        }

        return false;
    }
}