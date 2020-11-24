<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


use DailyTasks\Framework\Config\ConfigContainer;
use DailyTasks\Framework\Config\ConfigContainerFactory;
use DailyTasks\Framework\DI\Container;
use DailyTasks\Framework\DI\Resolver;

class Kernel
{
    private Resolver $resolver;
    private ?string $envFilePath;
    private ?string $defaultConfigsFolderPath;
    private ?string $medium;

    public function __construct(
        ?string $medium = 'web',
        ?string $envFilePath = ROOT_PATH . '/.env',
        ?string $defaultConfigsFolderPath = ROOT_PATH . '/config'
    ) {
        $this->envFilePath = $envFilePath;
        $this->defaultConfigsFolderPath = $defaultConfigsFolderPath;
        $this->medium = $medium;
        $this->resolver = new Resolver(new Container());
    }

    public function run()
    {
        /** @var ConfigContainerFactory $configFactory */
        $configFactory = $this->resolver->resolve(ConfigContainerFactory::class);
        $config = $configFactory->create(
            $this->defaultConfigsFolderPath,
            $this->envFilePath
        );

        if ($config->get('di') !== null) {
            $this->resolver->setRules($config->get('di'));
        }

        $this->resolver->getContainer()
                       ->set(ConfigContainer::class, $config);
        $this->resolver->getContainer()
                       ->set(Medium::class, new Medium($this->medium));

        /** @var ActionController $actionController */
        $actionController = $this->resolver->resolve(ActionController::class);

        return $actionController->execute();
    }
}