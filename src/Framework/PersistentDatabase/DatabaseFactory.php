<?php
declare(strict_types=1);

namespace DailyTasks\Framework\PersistentDatabase;


use DailyTasks\Framework\Config\ConfigManager;
use DailyTasks\Framework\DI\Contract\ServiceFactoryInterface;
use DailyTasks\Framework\Domain\Entity\Domain;
use Doctrine\DBAL\DriverManager;

class DatabaseFactory implements ServiceFactoryInterface
{
    /**
     * @var ConfigManager
     */
    private ConfigManager $configManager;
    /**
     * @var Domain
     */
    private Domain $domain;

    public function __construct(ConfigManager $configManager, Domain $domain)
    {
        $this->configManager = $configManager;
        $this->domain = $domain;
    }

    public function createInstance()
    {
        return new Database(
            DriverManager::getConnection(
                $this->configManager->getDomainConfig($this->domain->getKey())
                                    ->get('db')
            )
        );
    }
}