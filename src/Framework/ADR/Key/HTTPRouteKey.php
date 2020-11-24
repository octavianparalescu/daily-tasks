<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Key;


use DailyTasks\Framework\Data\ComposedKey;
use DailyTasks\Framework\Data\Traits\StringableProperties;

class HTTPRouteKey implements ComposedKey
{
    use StringableProperties;

    private string $httpVerb;
    private string $route;

    /**
     * HTTPRouteKey constructor.
     *
     * @param string $httpVerb
     * @param string $route
     */
    public function __construct(string $httpVerb, string $route)
    {
        $this->httpVerb = $httpVerb;
        $this->route = $route;
    }
}