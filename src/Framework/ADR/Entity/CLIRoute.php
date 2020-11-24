<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Entity;


use DailyTasks\Framework\ADR\Contract\RouteInterface;

class CLIRoute implements RouteInterface
{
    private string $path;
    private string $actionClass;

    public function __construct(string $path, string $actionClass)
    {
        $this->path = $path;
        $this->actionClass = $actionClass;
    }

    public function getKey()
    {
        return $this->getPath();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getActionClass(): string
    {
        return $this->actionClass;
    }
}