<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Key;


use DailyTasks\Framework\Data\ComposedKey;
use DailyTasks\Framework\Data\Traits\StringableProperties;

class HTTPRouteKey implements ComposedKey
{
    use StringableProperties;

    private string $httpVerb;
    private string $path;

    /**
     * HTTPRouteKey constructor.
     *
     * @param string $httpVerb
     * @param string $path
     */
    public function __construct(string $httpVerb, string $path)
    {
        $this->httpVerb = $httpVerb;
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getHttpVerb(): string
    {
        return $this->httpVerb;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}