<?php
declare(strict_types=1);

namespace DailyTasks\Task\Action;


use DailyTasks\Framework\ADR\Action\HTTPResourceAction;
use DailyTasks\Framework\ADR\Contract\ActionHandlerInterface;
use DailyTasks\Framework\ADR\Contract\ActionInterface;
use DailyTasks\Framework\ADR\Security\HTTPCorsHandler;
use DailyTasks\Framework\Config\ConfigContainer;
use DailyTasks\Framework\Data\Validator\Validator;
use DailyTasks\Task\Converter\TaskArrayConverter;
use DailyTasks\Task\Repository\TaskDbRepository;
use DailyTasks\Task\Validator\TaskValidator;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class CreateTaskAction implements ActionHandlerInterface
{
    use HTTPResourceAction, HTTPCorsHandler;

    /**
     * @var TaskDbRepository
     */
    private TaskDbRepository $taskDbRepository;
    /**
     * @var TaskValidator
     */
    private TaskValidator $taskValidator;
    /**
     * @var ConfigContainer
     */
    private ConfigContainer $configContainer;
    /**
     * @var TaskArrayConverter
     */
    private TaskArrayConverter $taskArrayConverter;
    /**
     * @var Validator
     */
    private Validator $validator;

    public function __construct(
        TaskDbRepository $taskDbRepository,
        Validator $validator,
        TaskValidator $taskValidator,
        ConfigContainer $configContainer,
        TaskArrayConverter $taskArrayConverter
    ) {
        $this->taskDbRepository = $taskDbRepository;
        $this->taskValidator = $taskValidator;
        $this->configContainer = $configContainer;
        $this->taskArrayConverter = $taskArrayConverter;
        $this->validator = $validator;
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
            // Retrieve the browser request
            /** @var Request $request */
            $request = $action->getRequest();

            // Retrieve the json-encoded entity
            $taskDataJson = $request->getContent();

            // Decode the json to an array
            $taskData = json_decode($taskDataJson, true);

            // Validate the task fields
            $validationErrors = $this->validator->validate($this->taskValidator, $taskData);

            // Send validation errors if any and halt execution
            if ($validationErrors->count()) {
                $this->returnValidationErrorsResultToHTTP($action->getResponse(), $validationErrors);

                return;
            }

            // Convert the task data to a entity
            $taskEntity = $this->taskArrayConverter->convertArrayToEntity($taskData);

            // Save the task to the database
            $taskEntity = $this->taskDbRepository->save($taskEntity);

            if ($taskEntity) {
                // Convert the saved entity to an array so it can be sent as a response
                $taskArray = $this->taskArrayConverter->convertEntityToArray($taskEntity);

                // Send fetch result to browser/service
                $this->returnFetchResultToHTTP($action->getResponse(), $taskArray);
            } else {
                // If SQL insert failed, there is an error with the server
                $this->returnServerErrorToHTTP($action->getResponse());
            }
        } catch (Throwable $throwable) {
            $this->returnServerErrorToHTTP($action->getResponse(), $throwable);
        }
    }
}