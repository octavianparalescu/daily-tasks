<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config;


/**
 * Class Container
 * @package DailyTasks\Framework\Config
 * @psalm-immutable
 */
class ConfigContainer
{
    private array $defaultConfig;
    private array $envConfig;

    public function __construct(array $envConfig, array $defaultConfig)
    {
        $this->defaultConfig = $defaultConfig;
        $this->envConfig = $envConfig;
    }

    /**
     * Gets a config value.
     *
     * @param string $fieldName
     *
     * @return mixed|null
     * @throws Exception
     */
    public function get(string $fieldName)
    {
        if (empty($fieldName)) {
            throw new Exception('Cannot retrieve a configuration value without a field name.');
        }

        return $this->getEnvValueOrDefaultValue($fieldName);
    }

    /**
     * @param string $fieldName
     *
     * @return mixed|null
     */
    private function getEnvValueOrDefaultValue(string $fieldName)
    {
        if (isset($this->envConfig[$fieldName])) {
            return $this->envConfig[$fieldName];
        } elseif (isset($this->defaultConfig[$fieldName])) {
            return $this->defaultConfig[$fieldName];
        } else {
            return null;
        }
    }
}