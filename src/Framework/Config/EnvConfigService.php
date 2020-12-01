<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config;


use DailyTasks\Framework\Domain\Key\DomainKey;

class EnvConfigService
{
    /**
     * @var ConfigManager
     */
    private static ConfigManager $configManager;

    public static function setConfig(ConfigManager $configManager)
    {
        self::$configManager = $configManager;
    }

    public static function get(string $domain, string $field)
    {
        return self::$configManager->getEnvDomainConfig(new DomainKey($domain))
                                   ->get($field);
    }
}