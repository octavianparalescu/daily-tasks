<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


class EnvService
{
    /**
     * @var Env
     */
    private static Env $env;

    public static function setEnv(Env $env): void
    {
        self::$env = $env;
    }

    public static function get(): Env
    {
        return self::$env;
    }
}