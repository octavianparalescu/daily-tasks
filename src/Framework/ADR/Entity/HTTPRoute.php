<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Entity;


use DailyTasks\Framework\ADR\Contract\RouteInterface;
use DailyTasks\Framework\ADR\Key\HTTPRouteKey;

class HTTPRoute implements RouteInterface
{
    /**
     * @var HTTPRouteKey
     */
    private HTTPRouteKey $key;
    private string $actionClass;

    public function __construct(HTTPRouteKey $key, string $actionClass)
    {
        $this->key = $key;
        $this->actionClass = $actionClass;
    }

    /**
     * @return string
     */
    public function getHttpVerb(): string
    {
        return $this->getKey()
                    ->getHttpVerb();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->getKey()
                    ->getPath();
    }

    /**
     * @return HTTPRouteKey
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getActionClass(): string
    {
        return $this->actionClass;
    }
}