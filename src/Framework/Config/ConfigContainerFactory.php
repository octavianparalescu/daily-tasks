<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config;


use DailyTasks\Framework\Config\Converter\EnvFileToArrayConverter;
use DailyTasks\Framework\Config\Converter\FileToConfigurationConverter;
use DailyTasks\Framework\Domain\Key\DomainKey;

class ConfigContainerFactory
{
    /**
     * @var EnvFileToArrayConverter
     */
    private EnvFileToArrayConverter $envConverter;
    /**
     * @var FileToConfigurationConverter
     */
    private FileToConfigurationConverter $fileToConfigurationConverter;

    public function __construct(
        FileToConfigurationConverter $fileToConfigurationConverter,
        EnvFileToArrayConverter $envConverter
    ) {
        $this->envConverter = $envConverter;
        $this->fileToConfigurationConverter = $fileToConfigurationConverter;
    }

    /**
     * @param DomainKey   $domainKey
     * @param string|null $defaultConfigFolderPath
     * @param string|null $envFolder
     *
     * @return ConfigContainer
     * @throws Exception
     */
    public function create(
        DomainKey $domainKey,
        string $defaultConfigFolderPath,
        string $envFolder
    ): ConfigContainer {
        $configPath = $this->getDefaultConfigFIlePath($defaultConfigFolderPath, $domainKey);
        $defaultConfig = [];
        if (is_readable($configPath)) {
            $defaultConfig = $this->fileToConfigurationConverter->convertFromFile($configPath);
        }

        $envFile = $this->getEnvFileNameFormat($envFolder, $domainKey);

        // We only read the env file if it exists as it is not mandatory
        $envArray = [];
        if (is_readable($envFile)) {
            $envArray = $this->envConverter->convertFile($envFile);
        }

        return new ConfigContainer($domainKey, $envArray, $defaultConfig);
    }

    /**
     * @param string|null $envFolder
     * @param DomainKey   $domainKey
     *
     * @return string
     */
    private function getEnvFileNameFormat(?string $envFolder, DomainKey $domainKey): string
    {
        return $envFolder . '/.' . $domainKey->getConfigIdentifier() . '.env';
    }

    /**
     * @param string|null $defaultConfigFolderPath
     * @param DomainKey   $domainKey
     *
     * @return string
     */
    private function getDefaultConfigFIlePath(?string $defaultConfigFolderPath, DomainKey $domainKey): string
    {
        return $defaultConfigFolderPath . '/' . $domainKey->getConfigIdentifier() . '.php';
    }
}