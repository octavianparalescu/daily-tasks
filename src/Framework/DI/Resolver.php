<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI;


use ReflectionClass;
use ReflectionException;

class Resolver
{
    /**
     * @var Container
     */
    private Container $container;

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
        $object = $this->container->get($className);

        if ($object !== null) {
            return $object;
        }

        if (in_array($className, $previousDependencies)) {
            throw new Exception(
                'Class ' . $className . ' has a circular dependency. Graph: ' . $this->getDependencyGraph($previousDependencies)
            );
        }

        try {
            $class = new ReflectionClass($className);
        } catch (ReflectionException $exception) {
            throw new Exception(
                'Class ' . $className . ' not found. Graph: ' . $this->getDependencyGraph($previousDependencies), 0, $exception
            );
        }

        if (!$class->isInstantiable()) {
            throw new Exception(
                'Class ' . $className . ' is not instantiable. Graph: ' . $this->getDependencyGraph($previousDependencies)
            );
        }

        $constructor = $class->getConstructor();
        if ($constructor === null) {
            // There are no parameters (no constructor), resolving is final
            $object = new $className();
        } else {
            $parameters = $constructor->getParameters();
            if (empty($parameters)) {
                // There are no parameters (constructor with no parameters), resolving is final
                $object = new $className();
            } else {
                $dependencies = [];
                foreach ($parameters as $parameter) {
                    $parameterClass = $parameter->getClass();
                    if ($parameterClass === null) {
                        throw new Exception(
                            'Class ' . $className . ' is not instantiable, parameter ' . $parameter->getName(
                            ) . ' doesn\'t have a type-hinted class. Graph: ' . $this->getDependencyGraph($previousDependencies)
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

                $object = new $className(...$dependencies);
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
        return implode(',', $previousDependencies);
    }
}