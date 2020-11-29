<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR;


use DailyTasks\Framework\ADR\Entity\CLIRoute;
use DailyTasks\Framework\ADR\Entity\HTTPRoute;
use DailyTasks\Framework\ADR\HTTPRouting\HTTPRouteMatcher;
use DailyTasks\Framework\ADR\Key\HTTPRouteKey;
use DailyTasks\Framework\ADR\Map\RouteMap;
use DailyTasks\Framework\Application\Medium;
use DailyTasks\Framework\Config\ConfigManager;
use DailyTasks\Framework\DI\Contract\ServiceFactoryInterface;
use DailyTasks\Framework\Domain\Entity\Domain;
use DailyTasks\Framework\Domain\Map\DomainMap;

class RouterFactory implements ServiceFactoryInterface
{
    /**
     * @var HTTPRouteMatcher
     */
    private HTTPRouteMatcher $HTTPRouteMatcher;
    /**
     * @var DomainMap
     */
    private DomainMap $domainMap;
    /**
     * @var ConfigManager
     */
    private ConfigManager $configManager;

    /**
     * RouterFactory constructor.
     *
     * @param DomainMap        $domainMap
     * @param ConfigManager    $configManager
     * @param HTTPRouteMatcher $HTTPRouteMatcher
     */
    public function __construct(
        DomainMap $domainMap,
        ConfigManager $configManager,
        HTTPRouteMatcher $HTTPRouteMatcher
    ) {
        $this->HTTPRouteMatcher = $HTTPRouteMatcher;
        $this->domainMap = $domainMap;
        $this->configManager = $configManager;
    }

    public function createInstance()
    {
        $routesMap = new RouteMap();
        /** @var Domain $domain */
        foreach ($this->domainMap->getIterator() as $domain) {
            foreach (Medium::IMPLEMENTATIONS as $medium) {
                $routes = $this->configManager->getDomainConfig($domain->getKey())
                                              ->get($medium . '_routes') ?? [];
                if (!empty($routes)) {
                    if ($medium === Medium::IMPLEMENTATION_WEB) {
                        $this->createRouteWeb($routes, $routesMap);
                    } elseif ($medium === Medium::IMPLEMENTATION_CLI) {
                        $this->createRouteCLI($routes, $routesMap);
                    }
                }
            }
        }

        return new Router($routesMap, $this->HTTPRouteMatcher);
    }

    /**
     * @param array    $routes
     * @param RouteMap $routesMap
     *
     * @throws \Exception
     */
    private function createRouteWeb(array $routes, RouteMap $routesMap): void
    {
        foreach ($routes as $path => $verbs) {
            $routesMap->addRange(
                new RouteMap(
                    array_map(
                        fn($verb, $actionClass) => new HTTPRoute(
                            new HTTPRouteKey($verb, $path), $actionClass
                        ),
                        array_keys($verbs),
                        $verbs
                    )
                )
            );
        }
    }

    /**
     * @param array    $routes
     * @param RouteMap $routesMap
     *
     * @throws \DailyTasks\Framework\Data\Exception
     */
    private function createRouteCLI(array $routes, RouteMap $routesMap): void
    {
        foreach ($routes as $path => $actionClass) {
            $routesMap->add(
                new CLIRoute(
                    $path, $actionClass
                )
            );
        }
    }
}