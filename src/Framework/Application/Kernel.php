<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


use DailyTasks\Framework\Config\ConfigContainerFactory;
use DailyTasks\Framework\Config\ConfigManager;
use DailyTasks\Framework\Config\EnvConfigService;
use DailyTasks\Framework\DI\Container;
use DailyTasks\Framework\DI\Resolver;
use DailyTasks\Framework\Domain\Factory\DomainMapFactory;
use DailyTasks\Framework\Domain\Map\DomainMap;
use DailyTasks\Framework\Exception;

require_once __DIR__ . '/helpers.php';

class Kernel
{
    const FORMAT_DATE_TIME = 'Y-m-d H:i:s';
    const FORMAT_DATE = 'Y-m-d';
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
        $domainMap = $this->setupDomains();
        $this->setupConfigManager($domainMap);

        $this->setupMedium();
        $this->setupEnv();

        /** @var ActionController $actionController */
        $actionController = $this->resolver->resolve(ActionController::class);

        return $actionController->execute();
    }

    /**
     * @return DomainMap
     * @throws \DailyTasks\Framework\Config\Exception
     * @throws \DailyTasks\Framework\DI\Exception
     */
    private function setupDomains(): DomainMap
    {
        $domainMap = DomainMapFactory::fromConfigPath($this->defaultConfigsFolderPath);
        $this->resolver->getContainer()
                       ->set(DomainMap::class, $domainMap);

        return $domainMap;
    }

    /**
     * @param DomainMap $domainMap
     *
     * @throws \DailyTasks\Framework\DI\Exception
     */
    private function setupConfigManager(DomainMap $domainMap): void
    {
        /** @var ConfigContainerFactory $configFactory */
        $configFactory = $this->resolver->resolve(ConfigContainerFactory::class);
        $configManager = new ConfigManager(
            $domainMap, $this->defaultConfigsFolderPath, $this->envFolderPath, $configFactory
        );

        EnvConfigService::setConfig($configManager);

        $this->resolver->getContainer()
                       ->set(ConfigManager::class, $configManager);
    }

    private function setupMedium(): void
    {
        $this->resolver->getContainer()
                       ->set(Medium::class, new Medium($this->medium));
    }

    private function setupEnv(): void
    {
        $env = new Env(getenv(Env::ENV_FIELD_NAME));
        $this->resolver->getContainer()
                       ->set(Env::class, $env);

        EnvService::setEnv($env);
    }
}