<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Contract;


interface ActionFactoryInterface
{
    public function createAction(): ActionInterface;
}