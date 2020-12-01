<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Entity;


use DailyTasks\Framework\ADR\Contract\RouteInterface;
use DailyTasks\Framework\ADR\Key\HTTPRouteKey;
use DailyTasks\Framework\Domain\Entity\Domain;

class HTTPRoute implements RouteInterface
{
    /**
     * @var HTTPRouteKey
     */
    private HTTPRouteKey $key;
    private string $actionClass;
    /**
     * @var Domain
     */
    private Domain $domain;

    /**
     * HTTPRoute constructor.
     *
     * @param HTTPRouteKey $key
     * @param string       $actionClass
     * @param Domain       $domain
     */
    public function __construct(HTTPRouteKey $key, string $actionClass, Domain $domain)
    {
        $this->key = $key;
        $this->actionClass = $actionClass;
        $this->domain = $domain;
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

    /**
     * @return Domain
     */
    public function getDomain(): Domain
    {
        return $this->domain;
    }
}