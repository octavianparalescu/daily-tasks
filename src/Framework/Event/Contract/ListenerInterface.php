<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Event\Contract;


interface ListenerInterface
{
    public function handle(EventInterface $event);
}