<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


use DailyTasks\Framework\ADR\Action\HTTPActionFactory;
use DailyTasks\Framework\DI\Resolver;
use DailyTasks\Framework\Exception;

class ActionController
{
    /**
     * @var ActionHandler
     */
    private ActionHandler $actionHandler;
    /**
     * @var Medium
     */
    private Medium $medium;
    /**
     * @var Resolver
     */
    private Resolver $di;

    /**
     * ActionController constructor.
     *
     * @param Medium        $medium
     * @param ActionHandler $actionHandler
     * @param Resolver      $di
     */
    public function __construct(Medium $medium, ActionHandler $actionHandler, Resolver $di)
    {
        $this->actionHandler = $actionHandler;
        $this->medium = $medium;
        $this->di = $di;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function execute()
    {
        switch ($this->medium->getMedium()) {
            case Medium::IMPLEMENTATION_WEB:
                /** @var HTTPActionFactory $factory */
                $factory = $this->di->resolve(HTTPActionFactory::class);
            break;
            // todo: add CLI action factory
            default:
                throw new Exception('Medium not supported: ' . $this->medium->getMedium());
        }

        $action = $factory->createAction();

        return $this->actionHandler->handle($action);
    }
}