<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


use DailyTasks\Framework\ADR\Contract\ActionHandlerInterface;
use DailyTasks\Framework\ADR\Contract\ActionInterface;
use DailyTasks\Framework\ADR\Router;
use DailyTasks\Framework\Config\ConfigContainer;
use DailyTasks\Framework\DI\Resolver;

class ActionHandler
{
    /**
     * @var ConfigContainer
     */
    private ConfigContainer $configContainer;
    /**
     * @var Medium
     */
    private Medium $medium;
    /**
     * @var Router
     */
    private Router $router;
    /**
     * @var Resolver
     */
    private Resolver $resolver;

    public function __construct(
        ConfigContainer $configContainer,
        Medium $medium,
        Router $router,
        Resolver $resolver
    ) {
        $this->configContainer = $configContainer;
        $this->medium = $medium;
        $this->router = $router;
        $this->resolver = $resolver;
    }

    public function handle(ActionInterface $action)
    {
        $route = $this->router->mapPathToRoute($this->medium, $action->getPath(), $action->getVerb());
        /** @var ActionHandlerInterface $actionObject */
        $actionObject = $this->resolver->resolve(
            $route->getMatchedRoute()
                  ->getActionClass()
        );

        return $actionObject->handle($action, $route->getParameterBag());
    }
}