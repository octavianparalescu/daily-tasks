<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI\Contract;


interface ServiceFactoryInterface
{
    public function createInstance();
}