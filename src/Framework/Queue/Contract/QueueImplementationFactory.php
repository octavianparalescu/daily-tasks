<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Contract;


interface QueueImplementationFactory
{
    public function createQueueImplementation(string $queueImplementationClass, array $options);
}