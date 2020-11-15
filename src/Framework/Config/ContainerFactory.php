<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config;


use DailyTasks\Framework\Config\Converter\EnvArrayToConfigurationConverter;
use DailyTasks\Framework\Config\Converter\EnvFileToArrayConverter;
use DailyTasks\Framework\Config\Converter\FolderToConfigurationConverter;

class ContainerFactory
{
    public static function create(
        FolderToConfigurationConverter $folderConverter,
        EnvFileToArrayConverter $envConverter,
        EnvArrayToConfigurationConverter $envArrayToConfigurationConverter,
        ?string $defaultConfigFolderPath = null,
        ?string $envFilePath = null
    ) {
        $defaultConfig = [];
        if ($defaultConfigFolderPath) {
            $defaultConfig = $folderConverter->convertFromFolder($defaultConfigFolderPath);
        }
        $envConfiguration = [];
        if (is_readable($envFilePath)) {
            $envArray = $envConverter->convertFile($envFilePath);
            $envConfiguration = $envArrayToConfigurationConverter->convertArrayToConfiguration($envArray);
        }

        return new Container($envConfiguration, $defaultConfig);
    }
}