<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Event\Contract;


interface EventManagerInterface
{
    public function subscribe(ListenerInterface $listener, string $eventClassName);

    public function publish(EventInterface $event);
}