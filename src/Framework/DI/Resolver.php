<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI;


use DailyTasks\Framework\Config\ConfigContainer;
use DailyTasks\Framework\DI\Contract\ServiceFactoryInterface;
use ReflectionClass;
use ReflectionException;
use Throwable;

class Resolver
{
    private const CONFIG_FIELD_NAME_STATIC = 'di_static';
    private const CONFIG_FIELD_NAME_FACTORY = 'di_factory';
    /**
     * @var Container
     */
    private Container $container;
    private ?ConfigContainer $rules = null;

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

        if ($className === get_class($this->container)) {
            return $this->container;
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

            if (!$factory instanceof ServiceFactoryInterface) {
                throw new Exception(
                    "Provided factory $factoryClass for $className is not a ServiceFactoryInterface. " . $this->getDependencyGraph(
                        $previousDependencies
                    )
                );
            }

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
                        try {
                            $parameterClass = $parameter->getClass();
                        } catch (Throwable $exception) {
                            throw new Exception(
                                'Class ' . $className . ' is not instantiable, parameter ' . $parameter->getName(
                                ) . ' doesn\'t have a type-hinted class. ' . $this->getDependencyGraph($previousDependencies),
                                0,
                                $exception
                            );
                        }
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
        if ($this->rules) {
            if ($this->rules->getFromEnv($this->getEnvFieldName('static', $className))) {
                return $this->rules->getFromEnv($this->getEnvFieldName('static', $className));
            }

            if ($this->rules->get(self::CONFIG_FIELD_NAME_STATIC) && $this->rules->get(
                    self::CONFIG_FIELD_NAME_STATIC
                )[$className]) {
                return $this->rules->get(self::CONFIG_FIELD_NAME_STATIC)[$className];
            }
        }

        return false;
    }

    /**
     * @param ConfigContainer $rules
     */
    public function setRules(ConfigContainer $rules): void
    {
        $this->rules = $rules;
    }

    private function getFactory(string $className)
    {
        if ($this->rules) {
            if ($this->rules->getFromEnv($this->getEnvFieldName('factory', $className))) {
                return $this->rules->getFromEnv($this->getEnvFieldName('factory', $className));
            }

            if ($this->rules->get(self::CONFIG_FIELD_NAME_FACTORY) && $this->rules->get(
                    self::CONFIG_FIELD_NAME_FACTORY
                )[$className]) {
                return $this->rules->get(self::CONFIG_FIELD_NAME_FACTORY)[$className];
            }
        }

        if (class_exists($className . 'Factory')) {
            return $className . 'Factory';
        }

        return false;
    }

    /**
     * Example: factory_FrameworkDatabasePersistent
     *
     * @param string $type
     * @param string $className
     *
     * @return string
     */
    private function getEnvFieldName(string $type, string $className): string
    {
        return $type . '_' . str_replace('/', '', $className);
    }
}