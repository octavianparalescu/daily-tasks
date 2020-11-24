<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


use DailyTasks\Framework\Exception;

class Medium
{
    public const IMPLEMENTATION_CLI = 'cli';
    public const IMPLEMENTATION_WEB = 'web';
    public const IMPLEMENTATIONS = [self::IMPLEMENTATION_CLI, self::IMPLEMENTATION_WEB];
    private string $medium;

    public function __construct(string $medium)
    {
        if (!in_array($medium, self::IMPLEMENTATIONS)) {
            throw new Exception('Implementation not enabled: ' . $medium);
        }
        $this->medium = $medium;
    }

    /**
     * @return string
     */
    public function getMedium(): string
    {
        return $this->medium;
    }
}