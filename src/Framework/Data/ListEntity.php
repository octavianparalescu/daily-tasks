<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data;


use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

abstract class ListEntity implements Countable, IteratorAggregate, JsonSerializable
{
    private string $entitiesType;
    private array $data;

    public function __construct($entitiesType, array $initialData = [])
    {
        $this->entitiesType = $entitiesType;
        $this->data = $initialData;
    }

    public function add(Immutable $entity)
    {
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