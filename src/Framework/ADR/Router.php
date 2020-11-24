<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR;


use DailyTasks\Framework\ADR\Contract\RouteInterface;
use DailyTasks\Framework\ADR\Contract\RouterInterface;
use DailyTasks\Framework\ADR\Entity\HTTPRoute;
use DailyTasks\Framework\ADR\Entity\ResolvedRoute;
use DailyTasks\Framework\ADR\HTTPRouting\HTTPRouteMatcher;
use DailyTasks\Framework\ADR\Map\RouteMap;
use DailyTasks\Framework\Application\Medium;

/**
 * Class Router
 * @package DailyTasks\Framework\ADR
 * @todo    CLI Route Matcher class, Route to Path method
 */
class Router implements RouterInterface
{
    private const REGEX_ALLOWED_CHARACTERS = '~[^\[\]_a-zA-Z0-9\-_/]~';
    /**
     * @var HTTPRouteMatcher
     */
    private HTTPRouteMatcher $HTTPRouteMatcher;
    /**
     * @var RouteMap
     */
    private RouteMap $routes;

    public function __construct(RouteMap $routes, HTTPRouteMatcher $HTTPRouteMatcher)
    {
        $this->routes = $routes;
        $this->HTTPRouteMatcher = $HTTPRouteMatcher;
    }

    public function mapPathToRoute(Medium $medium, string $path, ?string $verb = null): ?ResolvedRoute
    {
        if (preg_match(self::REGEX_ALLOWED_CHARACTERS, $path)) {
            throw new Exception('Illegal characters found in path ' . $path);
        }

        /** @var RouteInterface $route */
        foreach ($this->routes as $route) {
            if ($route instanceof HTTPRoute && $medium->getMedium() === Medium::IMPLEMENTATION_WEB) {
                $resolvedRoute = $this->HTTPRouteMatcher->match($route, $path, $verb);
                if ($resolvedRoute) {
                    return $resolvedRoute;
                }
            }
        }

        return null;
    }
}