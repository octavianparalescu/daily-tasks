<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config;


use DailyTasks\Framework\Config\Converter\EnvArrayToConfigurationConverter;
use DailyTasks\Framework\Config\Converter\EnvFileToArrayConverter;
use DailyTasks\Framework\Config\Converter\FolderToConfigurationConverter;

class ConfigContainerFactory
{
    /**
     * @var FolderToConfigurationConverter
     */
    private FolderToConfigurationConverter $folderConverter;
    /**
     * @var EnvFileToArrayConverter
     */
    private EnvFileToArrayConverter $envConverter;
    /**
     * @var EnvArrayToConfigurationConverter
     */
    private EnvArrayToConfigurationConverter $envArrayToConfigurationConverter;

    public function __construct(
        FolderToConfigurationConverter $folderConverter,
        EnvFileToArrayConverter $envConverter,
        EnvArrayToConfigurationConverter $envArrayToConfigurationConverter
    ) {
        $this->folderConverter = $folderConverter;
        $this->envConverter = $envConverter;
        $this->envArrayToConfigurationConverter = $envArrayToConfigurationConverter;
    }

    /**
     * @param string|null $defaultConfigFolderPath
     * @param string|null $envFilePath
     *
     * @return ConfigContainer
     * @throws Exception
     */
    public function create(
        ?string $defaultConfigFolderPath = null,
        ?string $envFilePath = null
    ): ConfigContainer {
        $defaultConfig = [];
        if ($defaultConfigFolderPath) {
            $defaultConfig = $this->folderConverter->convertFromFolder($defaultConfigFolderPath);
        }
        $envConfiguration = [];
        if (is_readable($envFilePath)) {
            $envArray = $this->envConverter->convertFile($envFilePath);
            $envConfiguration = $this->envArrayToConfigurationConverter->convertArrayToConfiguration($envArray);
        }

        return new ConfigContainer($envConfiguration, $defaultConfig);
    }
}