<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


use DailyTasks\Framework\ADR\Contract\ActionFactoryInterface;

class ActionController
{
    /**
     * @var ActionFactoryInterface
     */
    private ActionFactoryInterface $actionFactory;
    /**
     * @var ActionHandler
     */
    private ActionHandler $actionHandler;

    public function __construct(ActionFactoryInterface $actionFactory, ActionHandler $actionHandler)
    {
        $this->actionFactory = $actionFactory;
        $this->actionHandler = $actionHandler;
    }

    public function execute()
    {
        $action = $this->actionFactory->createAction();

        return $this->actionHandler->handle($action);
    }
}