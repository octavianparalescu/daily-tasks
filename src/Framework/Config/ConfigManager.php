<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config;


use DailyTasks\Framework\Config\Map\ConfigContainerMap;
use DailyTasks\Framework\Domain\Map\DomainMap;

class ConfigManager
{
    /**
     * @var DomainMap
     */
    private DomainMap $domainMap;
    private string $defaultConfigurationPath;
    private string $envFilePath;
    private ConfigContainerMap $configContainerMap;
    /**
     * @var ConfigContainerFactory
     */
    private ConfigContainerFactory $configContainerFactory;

    public function __construct(
        DomainMap $domainMap,
        string $defaultConfigurationPath,
        string $envFilePath,
        ConfigContainerFactory $configContainerFactory
    ) {
        $this->domainMap = $domainMap;
        $this->defaultConfigurationPath = $defaultConfigurationPath;
        $this->envFilePath = $envFilePath;
        $this->configContainerFactory = $configContainerFactory;
        $this->configContainerMap = new ConfigContainerMap();
    }

    public function getEntitiesType(): string
    {
        return ConfigContainer::class;
    }

    /**
     * @param $key
     *
     * @return ConfigContainer|null
     * @throws \DailyTasks\Framework\Data\Exception
     * @throws Exception
     */
    public function getDomainConfig($key): ?ConfigContainer
    {
        if ($existingItem = $this->configContainerMap->getByKey($key)) {
            return $existingItem;
        }

        // Create the config object on the fly
        $object = $this->configContainerFactory->create($key, $this->defaultConfigurationPath, $this->envFilePath);
        $this->configContainerMap->add($object);

        return $object;
    }
}