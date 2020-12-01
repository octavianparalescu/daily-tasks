<?php
declare(strict_types=1);

use DailyTasks\Framework\Application\EnvService;
use DailyTasks\Framework\Config\EnvConfigService;

function envConfig(string $domain, string $fieldName, $default = null)
{
    $value = EnvConfigService::get($domain, $fieldName);

    if ($value === null) {
        $value = $default;
    }

    return $value;
}

function env()
{
    return EnvService::get();
}

function domainFriendsFromEnvConfig(string $domain, int $friendsLimitFromEnvConfig = 255): array
{
    $friends = [];
    for ($i = 1; $i <= $friendsLimitFromEnvConfig; $i++) {
        $friend = envConfig($domain, 'DOMAIN_FRIEND' . $i);
        if ($friend) {
            $friends [] = $friend;
        } else {
            break;
        }
    }

    return $friends;
}