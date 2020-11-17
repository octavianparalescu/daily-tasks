<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


use DailyTasks\Framework\Config\ContainerFactory;
use DailyTasks\Framework\DI\Container;
use DailyTasks\Framework\DI\Resolver;

class Kernel
{
    private Resolver $resolver;
    private ?string $envFilePath;
    private ?string $defaultConfigsFolderPath;

    public function __construct(
        ?string $envFilePath = ROOT_PATH . '/.env',
        ?string $defaultConfigsFolderPath = ROOT_PATH . '/config'
    ) {
        $this->resolver = new Resolver(new Container());
        $this->envFilePath = $envFilePath;
        $this->defaultConfigsFolderPath = $defaultConfigsFolderPath;
    }

    public function start()
    {
        /** @var ContainerFactory $configFactory */
        $configFactory = $this->resolver->resolve(ContainerFactory::class);
        $config = $configFactory->create(
            $this->defaultConfigsFolderPath,
            $this->envFilePath
        );
        $this->resolver->getContainer()
                       ->set(\DailyTasks\Framework\Config\Container::class, $config);
    }
}