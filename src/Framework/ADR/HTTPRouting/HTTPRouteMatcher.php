<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\HTTPRouting;


use DailyTasks\Framework\ADR\Contract\RouteInterface;
use DailyTasks\Framework\ADR\Contract\RouteMatcherInterface;
use DailyTasks\Framework\ADR\Entity\HTTPRoute;
use DailyTasks\Framework\ADR\Entity\ResolvedRoute;
use Symfony\Component\HttpFoundation\ParameterBag;

class HTTPRouteMatcher implements RouteMatcherInterface
{
    private const REGEX_SPLIT_ROUTE = '~[/]([\[]{0,1}[a-zA-Z0-9_\-]+[\]]{0,1})~';
    private const REGEX_SPLIT_PATH = '~[/]([a-zA-Z0-9_\-]+)~';

    /**
     * @param RouteInterface $route
     * @param string         $path
     * @param string|null    $verb
     *
     * @return ResolvedRoute|null
     */
    public function match(RouteInterface $route, string $path, ?string $verb = null): ?ResolvedRoute
    {
        preg_match_all(self::REGEX_SPLIT_ROUTE, $route->getPath(), $matches);
        $splitRoute = $matches[1];
        preg_match_all(self::REGEX_SPLIT_PATH, $path, $matches);
        $splitPath = $matches[1];
        if (count($splitRoute) !== count($splitPath)) {
            return null;
        }
        $parameters = new ParameterBag();
        for ($position = 0; $position < count($splitRoute); $position++) {
            if (!$this->isParameter($splitRoute[$position])) {
                if ($splitRoute[$position] !== $splitPath[$position]) {
                    // Route doesn't match
                    return null;
                }
            } else {
                $parameters->set(str_replace(['[', ']'], '', $splitRoute[$position]), $splitPath[$position]);
            }
        }

        /** @var HTTPRoute $route */
        if ($route->getHttpVerb() !== $verb) {
            return null;
        }

        return new ResolvedRoute($route, $parameters);
    }

    private function isParameter($routePart)
    {
        if (substr($routePart, 0, 1) === '[' && substr($routePart, -1, 1) === ']') {
            return true;
        }

        return false;
    }
}