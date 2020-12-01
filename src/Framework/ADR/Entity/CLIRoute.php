<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Entity;


use DailyTasks\Framework\ADR\Contract\RouteInterface;
use DailyTasks\Framework\Domain\Entity\Domain;

class CLIRoute implements RouteInterface
{
    private string $path;
    private string $actionClass;
    /**
     * @var Domain
     */
    private Domain $domain;

    /**
     * CLIRoute constructor.
     *
     * @param string $path
     * @param string $actionClass
     * @param Domain $domain
     */
    public function __construct(string $path, string $actionClass, Domain $domain)
    {
        $this->path = $path;
        $this->actionClass = $actionClass;
        $this->domain = $domain;
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

    /**
     * @return Domain
     */
    public function getDomain(): Domain
    {
        return $this->domain;
    }
}