<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Contract;


use DailyTasks\Framework\Data\Immutable;
use ReflectionClass;
use ReflectionObject;

abstract class QueueItem implements Immutable
{
    public const PARAM_CLASS_NAME = 'object_class';
    private const PARAM_PROPERTIES = 'properties';

    public function serialize(): array
    {
        $properties = [];
        $reflectionObject = new ReflectionObject($this);
        foreach ($reflectionObject->getProperties() as $property) {
            $property->setAccessible(true);
            $properties [$property->getName()] = $property->getValue($this);
        }

        return [
            self::PARAM_CLASS_NAME => get_class($this),
            self::PARAM_PROPERTIES => $properties,
        ];
    }

    public static function deserialize(array $message): self
    {
        $class = $message[self::PARAM_CLASS_NAME];
        $properties = $message[self::PARAM_PROPERTIES];
        $reflectionClass = new ReflectionClass($class);
        /** @var self $object */
        $object = $reflectionClass->newInstanceWithoutConstructor();
        $reflectionObject = new ReflectionObject($object);
        $reflectionProperties = $reflectionObject->getProperties();

        foreach ($reflectionProperties as $property) {
            $property->setAccessible(true);
            $property->setValue($object, $properties[$property->getName()]);
        }

        return $object;
    }
}