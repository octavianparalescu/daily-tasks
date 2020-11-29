<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Domain\Factory;


use DailyTasks\Framework\Config\Exception;
use DailyTasks\Framework\Domain\Entity\Domain;
use DailyTasks\Framework\Domain\Key\DomainKey;
use DailyTasks\Framework\Domain\Map\DomainMap;

class DomainMapFactory
{
    private const DOMAINS_CONFIG_FILE = 'domains.php';

    /**
     * @param string $defaultConfigFolder
     *
     * @return DomainMap
     * @throws Exception
     */
    public static function fromConfigPath(string $defaultConfigFolder): DomainMap
    {
        $filePath = $defaultConfigFolder . '/' . self::DOMAINS_CONFIG_FILE;

        if (!is_readable($filePath)) {
            throw new Exception(
                "Domain config file is not accessible: $filePath."
            );
        }
        /** @noinspection PhpIncludeInspection */
        $fileConfigArray = include($filePath);

        if (!is_array($fileConfigArray)) {
            throw new Exception(
                "Domain config file is not returning an array: $filePath."
            );
        }

        $domainMap = new DomainMap();
        foreach ($fileConfigArray as $configItem => $namespace) {
            $domainMap->add(new Domain(new DomainKey($configItem), $namespace));
        }

        return $domainMap;
    }
}