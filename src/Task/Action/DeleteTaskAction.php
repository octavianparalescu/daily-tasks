<?php
declare(strict_types=1);

namespace DailyTasks\Task\Action;


use DailyTasks\Framework\ADR\Action\HTTPResourceAction;
use DailyTasks\Framework\ADR\Contract\ActionHandlerInterface;
use DailyTasks\Framework\ADR\Contract\ActionInterface;
use DailyTasks\Framework\ADR\Security\HTTPCorsHandler;
use DailyTasks\Framework\Config\ConfigContainer;
use DailyTasks\Task\Repository\TaskDbRepository;
use Symfony\Component\HttpFoundation\ParameterBag;
use Throwable;

class DeleteTaskAction implements ActionHandlerInterface
{
    use HTTPResourceAction, HTTPCorsHandler;

    /**
     * @var TaskDbRepository
     */
    private TaskDbRepository $taskDbRepository;
    /**
     * @var ConfigContainer
     */
    private ConfigContainer $configContainer;

    public function __construct(TaskDbRepository $taskDbRepository, ConfigContainer $configContainer)
    {
        $this->taskDbRepository = $taskDbRepository;
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

        try {
            // Retrieve task id from the url parameters
            $taskId = $parameters->get('id');

            // Delete the task from the database
            $deleteStatus = $this->taskDbRepository->delete($taskId);

            // Return the status of the delete request
            $this->returnDeleteToHTTP($action->getResponse(), $deleteStatus);
        } catch (Throwable $throwable) {
            $this->returnServerErrorToHTTP($action->getResponse());
        }
    }
}