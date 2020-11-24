<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Contract;


use Symfony\Component\HttpFoundation\ParameterBag;

interface ActionHandlerInterface
{
    public function handle(ActionInterface $action, ParameterBag $parameters);
}