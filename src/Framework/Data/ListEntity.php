<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data;


use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

abstract class ListEntity implements Countable, IteratorAggregate, JsonSerializable
{
    private array $data;

    public function __construct(array $initialData = [])
    {
        $this->data = $initialData;
    }

    /**
     * Should return the class name of the to-be contained objects
     * @return string
     */
    abstract public function getEntitiesType(): string;

    public function add(Immutable $entity)
    {
        $entitiesType = $this->getEntitiesType();
        if (!($entity instanceof $entitiesType)) {
            throw new Exception('Tried to add ' . (get_class($entity)) . ' to map of type ' . $entitiesType);
        }
        if (!$entity) {
            return;
        }

        $this->data [] = $entity;
    }

    public function remove(Immutable $entity)
    {
        foreach ($this->data as $key => $datum) {
            if ($datum === $entity) {
                unset($this->data[$key]);
            }
        }
    }

    /**
     * @param IteratorAggregate $listEntity
     *
     * @throws \Exception
     */
    public function addRange(IteratorAggregate $listEntity)
    {
        foreach ($listEntity->getIterator() as $item) {
            $this->add($item);
        }
    }

    public function first(): ?Immutable
    {
        if (!$this->count()) {
            return null;
        }

        $reversedArray = array_reverse($this->data);

        return array_pop($reversedArray);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

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
}