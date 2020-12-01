<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Action;


use DailyTasks\Framework\ADR\Contract\ActionFactoryInterface;
use DailyTasks\Framework\ADR\Contract\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HTTPActionFactory implements ActionFactoryInterface
{
    public function createAction(): ActionInterface
    {
        return new HTTPAction(Request::createFromGlobals(), new Response());
    }
}