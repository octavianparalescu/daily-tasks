<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


use DailyTasks\Framework\ADR\Contract\ActionHandlerInterface;
use DailyTasks\Framework\ADR\Contract\ActionInterface;
use DailyTasks\Framework\ADR\Router;
use DailyTasks\Framework\Config\ConfigContainer;
use DailyTasks\Framework\Config\ConfigManager;
use DailyTasks\Framework\DI\Resolver;
use DailyTasks\Framework\Domain\Entity\Domain;

class ActionHandler
{
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
    /**
     * @var ConfigManager
     */
    private ConfigManager $configManager;

    public function __construct(
        Medium $medium,
        Router $router,
        Resolver $resolver,
        ConfigManager $configManager
    ) {
        $this->medium = $medium;
        $this->router = $router;
        $this->resolver = $resolver;
        $this->configManager = $configManager;
    }

    public function handle(ActionInterface $action)
    {
        $route = $this->router->mapPathToRoute($this->medium, $action->getPath(), $action->getVerb());

        $this->resolver->getContainer()
                       ->set(
                           Domain::class,
                           $route->getMatchedRoute()
                                 ->getDomain()
                       );
        $this->resolver->getContainer()
                       ->set(
                           ConfigContainer::class,
                           $this->configManager->getDomainConfig(
                               $route->getMatchedRoute()
                                     ->getDomain()
                                     ->getKey()
                           )
                       );

        /** @var ActionHandlerInterface $actionObject */
        $actionObject = $this->resolver->resolve(
            $route->getMatchedRoute()
                  ->getActionClass()
        );

        $actionObject->handle($action, $route->getParameterBag());

        return $action->getResponse();
    }
}