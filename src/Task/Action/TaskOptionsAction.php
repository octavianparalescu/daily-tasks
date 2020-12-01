<?php
declare(strict_types=1);

namespace DailyTasks\Task\Action;


use DailyTasks\Framework\ADR\Contract\ActionHandlerInterface;
use DailyTasks\Framework\ADR\Contract\ActionInterface;
use DailyTasks\Framework\ADR\Security\HTTPCorsHandler;
use DailyTasks\Framework\Config\ConfigContainer;
use Symfony\Component\HttpFoundation\ParameterBag;

class TaskOptionsAction implements ActionHandlerInterface
{
    use HTTPCorsHandler;

    /**
     * @var ConfigContainer
     */
    private ConfigContainer $configContainer;

    /**
     * TaskOptionsAction constructor.
     *
     * @param ConfigContainer $configContainer
     */
    public function __construct(ConfigContainer $configContainer)
    {
        $this->configContainer = $configContainer;
    }

    public function handle(ActionInterface $action, ParameterBag $parameters)
    {
        // Accept browser CORS request from domain's friends
        $this->acceptRequestFromDomainFriends(
            $this->configContainer->get('domain_friends'),
            $action->getRequest(),
            $action->getResponse()
        );
    }
}