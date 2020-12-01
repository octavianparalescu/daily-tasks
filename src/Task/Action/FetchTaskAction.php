<?php
declare(strict_types=1);

namespace DailyTasks\Task\Action;


use DailyTasks\Framework\ADR\Action\HTTPResourceAction;
use DailyTasks\Framework\ADR\Contract\ActionHandlerInterface;
use DailyTasks\Framework\ADR\Contract\ActionInterface;
use DailyTasks\Framework\ADR\Security\HTTPCorsHandler;
use DailyTasks\Framework\Config\ConfigContainer;
use DailyTasks\Task\Converter\TaskArrayConverter;
use DailyTasks\Task\Repository\TaskDbRepository;
use Symfony\Component\HttpFoundation\ParameterBag;
use Throwable;

class FetchTaskAction implements ActionHandlerInterface
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
    /**
     * @var TaskArrayConverter
     */
    private TaskArrayConverter $taskArrayConverter;

    public function __construct(
        TaskDbRepository $taskDbRepository,
        ConfigContainer $configContainer,
        TaskArrayConverter $taskArrayConverter
    ) {
        $this->taskDbRepository = $taskDbRepository;
        $this->configContainer = $configContainer;
        $this->taskArrayConverter = $taskArrayConverter;
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

            // Retrieve the task from the database
            $taskEntity = $this->taskDbRepository->fetch($taskId);

            // Convert it to an array so it can be sent as a response
            $taskArray = $this->taskArrayConverter->convertEntityToArray($taskEntity);

            // Send fetch result to browser/service
            $this->returnFetchResultToHTTP($action->getResponse(), $taskArray);
        } catch (Throwable $throwable) {
            $this->returnServerErrorToHTTP($action->getResponse(), $throwable);
        }
    }
}