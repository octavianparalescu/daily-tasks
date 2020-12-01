<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Domain\Key;


use DailyTasks\Framework\Data\ComposedKey;
use DailyTasks\Framework\Data\StringableProperties;

class DomainKey implements ComposedKey
{
    use StringableProperties;

    private string $configIdentifier;

    public function __construct(string $configIdentifier)
    {
        $this->configIdentifier = $configIdentifier;
    }

    /**
     * @return string
     */
    public function getConfigIdentifier(): string
    {
        return $this->configIdentifier;
    }
}