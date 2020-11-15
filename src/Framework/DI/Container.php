<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI;


class Container
{
    private array $serviceList = [];

    public function __construct(array $serviceList = [])
    {
        $this->serviceList = $serviceList;
    }

    public function get(string $className): ?object
    {
        if (array_key_exists($className, $this->serviceList)) {
            return $this->serviceList[$className];
        }

        return null;
    }

    /**
     * @param string $className
     * @param object $object
     *
     * @return void
     * @throws Exception
     */
    public function set(string $className, object $object): void
    {
        if (array_key_exists($className, $this->serviceList)) {
            throw new Exception('Object already exists for ' . $className);
        }

        $this->serviceList[$className] = $object;
    }
}