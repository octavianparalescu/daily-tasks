<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


use DailyTasks\Framework\Config\ConfigContainerFactory;
use DailyTasks\Framework\Config\ConfigManager;
use DailyTasks\Framework\DI\Container;
use DailyTasks\Framework\DI\Resolver;
use DailyTasks\Framework\Domain\Factory\DomainMapFactory;
use DailyTasks\Framework\Domain\Map\DomainMap;
use DailyTasks\Framework\Exception;

class Kernel
{
    private Resolver $resolver;
    private ?string $envFolderPath;
    private ?string $defaultConfigsFolderPath;
    private ?string $medium;

    public function __construct(
        ?string $medium = 'web',
        ?string $envFolderPath = ROOT_PATH,
        ?string $defaultConfigsFolderPath = ROOT_PATH . '/config'
    ) {
        $this->envFolderPath = $envFolderPath;
        $this->defaultConfigsFolderPath = $defaultConfigsFolderPath;
        $this->medium = $medium;
        $this->resolver = new Resolver(new Container());
    }

    /**
     * @return mixed
     * @throws \DailyTasks\Framework\Config\Exception
     * @throws \DailyTasks\Framework\DI\Exception
     * @throws Exception
     */
    public function run()
    {
        $domainMap = DomainMapFactory::fromConfigPath($this->defaultConfigsFolderPath);
        $this->resolver->getContainer()
                       ->set(DomainMap::class, $domainMap);

        /** @var ConfigContainerFactory $configFactory */
        $configFactory = $this->resolver->resolve(ConfigContainerFactory::class);
        $configManager = new ConfigManager(
            $domainMap, $this->defaultConfigsFolderPath, $this->envFolderPath, $configFactory
        );
        $this->resolver->getContainer()
                       ->set(ConfigManager::class, $configManager);
        $this->resolver->getContainer()
                       ->set(Medium::class, new Medium($this->medium));

        /** @var ActionController $actionController */
        $actionController = $this->resolver->resolve(ActionController::class);

        return $actionController->execute();
    }
}