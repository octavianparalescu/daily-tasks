<?php
declare(strict_types=1);

namespace DailyTasks\Task\Action;


use DailyTasks\Framework\ADR\Action\HTTPResourceAction;
use DailyTasks\Framework\ADR\Contract\ActionHandlerInterface;
use DailyTasks\Framework\ADR\Contract\ActionInterface;
use DailyTasks\Framework\ADR\Security\HTTPCorsHandler;
use DailyTasks\Framework\Application\Kernel;
use DailyTasks\Framework\Config\ConfigContainer;
use DailyTasks\Framework\Data\Validator\Entity\ValidationError;
use DailyTasks\Framework\Data\Validator\FieldValidators\DateTimeValidator;
use DailyTasks\Framework\Data\Validator\Key\ValidationErrorKey;
use DailyTasks\Framework\Data\Validator\Map\ValidationErrorMap;
use DailyTasks\Task\Converter\TaskArrayConverter;
use DailyTasks\Task\Repository\TaskDbRepository;
use DateTime;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class FetchTasksByDueDateAction implements ActionHandlerInterface
{
    use HTTPResourceAction, HTTPCorsHandler;

    private const DATE_PARAMETER = 'date';
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
    /**
     * @var DateTimeValidator
     */
    private DateTimeValidator $dateTimeValidator;

    public function __construct(
        TaskDbRepository $taskDbRepository,
        ConfigContainer $configContainer,
        TaskArrayConverter $taskArrayConverter,
        DateTimeValidator $dateTimeValidator
    ) {
        $this->taskDbRepository = $taskDbRepository;
        $this->configContainer = $configContainer;
        $this->taskArrayConverter = $taskArrayConverter;
        $this->dateTimeValidator = $dateTimeValidator;
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
            // Fetch the HTTP request
            /** @var Request $request */
            $request = $action->getRequest();

            // Fetch the due date parameter value
            $dueDate = $request->query->get(self::DATE_PARAMETER);

            // Validate the date
            $success = $this->dateTimeValidator->validate($dueDate, ['format' => Kernel::FORMAT_DATE]);

            // Send the validation error and halt if there are any validation errors for the date
            if (!$success) {
                $this->returnValidationErrorsResultToHTTP(
                    $action->getResponse(),
                    new ValidationErrorMap(
                        [new ValidationError(new ValidationErrorKey(self::DATE_PARAMETER, 'date-valid'), 'Date format invalid.')]
                    )
                );

                return;
            }

            // Retrieve the tasks from the database
            $taskEntities = $this->taskDbRepository->getListByDueDate(
                DateTime::createFromFormat(Kernel::FORMAT_DATE, $dueDate)
            );

            // Convert it to an array so it can be sent as a response
            $tasksArray = $this->taskArrayConverter->convertEntityListToArray($taskEntities);

            // Send fetch result to browser/service
            $this->returnMultipleFetchResultToHTTP($action->getResponse(), $tasksArray);
        } catch (Throwable $throwable) {
            $this->returnServerErrorToHTTP($action->getResponse(), $throwable);
        }
    }
}