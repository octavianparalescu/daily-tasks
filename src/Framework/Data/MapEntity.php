<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data;


use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

abstract class MapEntity implements Countable, IteratorAggregate, JsonSerializable
{
    private array $data;

    public function __construct(array $initialData = [])
    {
        $this->data = [];
        /** @var Identifiable $datum */
        foreach ($initialData as $datum) {
            $this->add($datum);
        }
    }

    /**
     * Should return the class name of the to-be contained objects
     * @return string
     */
    abstract public function getEntitiesType(): string;

    public function add(Identifiable $entity): void
    {
        $entitiesType = $this->getEntitiesType();
        if (!($entity instanceof $entitiesType)) {
            throw new \Exception('Tried to add ' . (get_class($entity)) . ' to map of type ' . $entitiesType);
        }
        $this->data[$this->getKeyString($entity->getKey())] = $entity;
    }

    /**
     * @param string|ComposedKey $key
     *
     * @throws Exception
     */
    public function remove($key): void
    {
        if (is_string($key) || (is_object($key) && $key instanceof ComposedKey)) {
            $key = $this->getKeyString($key);
            unset($this->data[$key]);
        } else {
            throw new Exception('Key not a string or a ComposedKey when removing object from map.');
        }
    }

    /**
     * @param $key
     *
     * @return Identifiable|mixed|null
     * @throws Exception
     */
    public function getByKey($key): ?Identifiable
    {
        if (is_string($key) || (is_object($key) && $key instanceof ComposedKey)) {
            $key = $this->getKeyString($key);
            if (!isset($this->data[$key])) {
                return null;
            }

            return $this->data[$key];
        } else {
            throw new Exception('Key not a string or a ComposedKey when retrieving object from map.');
        }
    }

    /**
     * @param MapEntity $map
     *
     * @throws \Exception
     */
    public function addRange(MapEntity $map): void
    {
        foreach ($map->getIterator() as $item) {
            $this->add($item);
        }
    }

    /**
     * @return ArrayIterator|Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * @return false|mixed|string
     */
    public function jsonSerialize()
    {
        return json_encode($this->data);
    }

    /**
     * @param string|ComposedKey $entity
     *
     * @return string
     */
    private function getKeyString($entity)
    {
        return (string) $entity;
    }
}