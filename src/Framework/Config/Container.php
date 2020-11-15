<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config;


use DailyTasks\Framework\Config\Converter\ComposedFieldNameConverter as Converter;

/**
 * Class Container
 * @package DailyTasks\Framework\Config
 * @psalm-immutable
 */
class Container
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
     * Field name examples:
     * MYSQL -> returns an array if the config file is defined with a MYSQL array or the single value called MYSQL
     * MYSQL_HOSTNAME -> returns the single value HOSTNAME from the array MYSQL
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
     * @return mixed
     * @throws Exception
     */
    private function getEnvValueOrDefaultValue(string $fieldName)
    {
        if (isset($this->envConfig[$fieldName])) {
            return $this->envConfig[$fieldName];
        } elseif (isset($this->defaultConfig[$fieldName])) {
            return $this->defaultConfig[$fieldName];
        } else {
            throw new Exception("Could not find config item with name $fieldName.");
        }
    }
}