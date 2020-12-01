<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config;


use DailyTasks\Framework\Data\Identifiable;
use DailyTasks\Framework\Domain\Key\DomainKey;

/**
 * Class Container
 * @package DailyTasks\Framework\Config
 * @psalm-immutable
 */
class ConfigContainer implements Identifiable
{
    private array $defaultConfig;
    /**
     * @var DomainKey
     */
    private DomainKey $key;

    public function __construct(DomainKey $key, array $defaultConfig)
    {
        $this->defaultConfig = $defaultConfig;
        $this->key = $key;
    }

    /**
     * Gets a config value.
     *
     * @param string      $fieldName
     * @param string|null $defaultValue
     *
     * @return mixed|null
     * @throws Exception
     */
    public function get(string $fieldName, ?string $defaultValue = null)
    {
        if (empty($fieldName)) {
            throw new Exception('Cannot retrieve a configuration value without a field name.');
        }

        $valueFromConfigs = $this->getFromDefaultConfig($fieldName);

        return $valueFromConfigs ?? $defaultValue;
    }

    /**
     * @param string      $fieldName
     *
     * @return mixed|null
     */
    private function getFromDefaultConfig(string $fieldName)
    {
        if (isset($this->defaultConfig[$fieldName])) {
            return $this->defaultConfig[$fieldName];
        } else {
            return null;
        }
    }

    /**
     * @return DomainKey
     */
    public function getKey()
    {
        return $this->key;
    }
}