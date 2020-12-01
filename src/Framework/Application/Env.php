<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


use DailyTasks\Framework\Exception;

class Env
{
    public const ENV_FIELD_NAME = 'ENV';
    public const ENV_IMPLEMENTATION_DEV = 'dev';
    public const ENV_IMPLEMENTATION_TEST = 'test';
    public const ENV_IMPLEMENTATION_PROD = 'prod';
    public const ENVIRONMENTS_TO_IMPLEMENTATIONS = [
        'dev' => self::ENV_IMPLEMENTATION_DEV,
        'uat' => self::ENV_IMPLEMENTATION_TEST,
        'preprod' => self::ENV_IMPLEMENTATION_PROD,
        'prod' => self::ENV_IMPLEMENTATION_PROD,
    ];
    private string $implementation;
    private string $env;

    public function __construct(string $env)
    {
        $appImplementation = null;
        foreach (self::ENVIRONMENTS_TO_IMPLEMENTATIONS as $envPart => $implementation) {
            if (stripos($env, $envPart) !== false) {
                $appImplementation = $implementation;
            }
        }
        if (!$appImplementation) {
            throw new Exception(
                'The current environment is not supported: ' . $env . '. Supported: ' . implode(
                    ',',
                    array_keys(self::ENVIRONMENTS_TO_IMPLEMENTATIONS)
                )
            );
        }

        $this->implementation = $appImplementation;
        $this->env = $env;
    }

    /**
     * @return string
     */
    public function getImplementation(): string
    {
        return $this->implementation;
    }

    /**
     * @return string
     */
    public function getEnv(): string
    {
        return $this->env;
    }

    /**
     * @return bool
     */
    public function isDev(): bool
    {
        return $this->getEnv() === self::ENV_IMPLEMENTATION_DEV;
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->getEnv() === self::ENV_IMPLEMENTATION_TEST;
    }

    /**
     * @return bool
     */
    public function isProd(): bool
    {
        return $this->getEnv() === self::ENV_IMPLEMENTATION_PROD;
    }
}