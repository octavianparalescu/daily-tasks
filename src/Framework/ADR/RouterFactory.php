<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR;


use DailyTasks\Framework\ADR\Entity\CLIRoute;
use DailyTasks\Framework\ADR\Entity\HTTPRoute;
use DailyTasks\Framework\ADR\HTTPRouting\HTTPRouteMatcher;
use DailyTasks\Framework\ADR\Key\HTTPRouteKey;
use DailyTasks\Framework\ADR\Map\RouteMap;
use DailyTasks\Framework\Application\Medium;
use DailyTasks\Framework\Config\ConfigContainer;

class RouterFactory
{
    /**
     * @var ConfigContainer
     */
    private ConfigContainer $configContainer;
    /**
     * @var HTTPRouteMatcher
     */
    private HTTPRouteMatcher $HTTPRouteMatcher;

    public function __construct(
        ConfigContainer $configContainer,
        HTTPRouteMatcher $HTTPRouteMatcher
    ) {
        $this->configContainer = $configContainer;
        $this->HTTPRouteMatcher = $HTTPRouteMatcher;
    }

    public function createInstance()
    {
        $domains = $this->configContainer->get('domains');
        $routesMap = new RouteMap();
        foreach ($domains as $configKey => $nameSpace) {
            foreach (Medium::IMPLEMENTATIONS as $medium) {
                $domainConfig = $this->configContainer->get('domain_' . $configKey);
                $routes = $domainConfig[$medium . '_routes'] ?? [];
                if ($medium === Medium::IMPLEMENTATION_WEB) {
                    foreach ($routes as $path => $verbs) {
                        $routesMap->addRange(
                            new RouteMap(
                                array_map(
                                    fn($verb, $actionClass) => new HTTPRoute(
                                        new HTTPRouteKey($verb, $path), $verb, $path, $actionClass
                                    ),
                                    array_keys($verbs),
                                    $verbs
                                )
                            )
                        );
                    }
                } elseif ($medium === Medium::IMPLEMENTATION_CLI) {
                    foreach ($routes as $path => $actionClass) {
                        $routesMap->add(
                            new CLIRoute(
                                $path, $actionClass
                            )
                        );
                    }
                }
            }
        }

        return new Router($routesMap, $this->HTTPRouteMatcher);
    }
}