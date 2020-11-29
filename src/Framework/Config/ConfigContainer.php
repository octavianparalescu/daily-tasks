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
    private array $envConfig;
    /**
     * @var DomainKey
     */
    private DomainKey $key;

    public function __construct(DomainKey $key, array $envConfig, array $defaultConfig)
    {
        $this->defaultConfig = $defaultConfig;
        $this->envConfig = $envConfig;
        $this->key = $key;
    }

    /**
     * Gets a config value.
     *
     * @param string      $fieldName
     * @param string|null $envFieldName
     * @param string|null $defaultValue
     *
     * @return mixed|null
     * @throws Exception
     */
    public function get(string $fieldName, ?string $envFieldName = null, ?string $defaultValue = null)
    {
        if (empty($fieldName)) {
            throw new Exception('Cannot retrieve a configuration value without a field name.');
        }

        $valueFromConfigs = $this->getFromEnvOrFromDefaults($fieldName, $envFieldName);

        return $valueFromConfigs ?? $defaultValue;
    }

    /**
     * @param string      $fieldName
     * @param string|null $envFieldName
     *
     * @return mixed|null
     */
    private function getFromEnvOrFromDefaults(string $fieldName, ?string $envFieldName)
    {
        if (isset($this->envConfig[$envFieldName])) {
            return $this->envConfig[$envFieldName];
        } elseif (isset($this->defaultConfig[$fieldName])) {
            return $this->defaultConfig[$fieldName];
        } else {
            return null;
        }
    }

    public function getFromEnv($fieldName)
    {
        if (empty($fieldName)) {
            throw new Exception('Cannot retrieve a configuration value without a field name.');
        }

        return $this->envConfig[$fieldName] ?? null;
    }

    /**
     * @return DomainKey
     */
    public function getKey()
    {
        return $this->key;
    }
}