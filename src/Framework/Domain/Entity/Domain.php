<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Domain\Entity;


use DailyTasks\Framework\Data\Identifiable;
use DailyTasks\Framework\Domain\Key\DomainKey;

class Domain implements Identifiable
{
    /**
     * @var DomainKey
     */
    private DomainKey $key;
    private string $namespace;

    public function __construct(DomainKey $key, string $namespace)
    {
        $this->key = $key;
        $this->namespace = $namespace;
    }

    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getConfigIdentifier(): string
    {
        return $this->getKey()
                    ->getConfigIdentifier();
    }
}