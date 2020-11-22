<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Factory;


use DailyTasks\Framework\Queue\Contract\QueueImplementationInterface;
use DailyTasks\Framework\Queue\Contract\QueueWorkerInterface;
use DailyTasks\Framework\Queue\Entity\Queue;

class QueueFactory
{
    public static function createQueue(
        string $queueName,
        QueueImplementationInterface $queueImplementation,
        QueueWorkerInterface $worker
    ) {
        return new Queue($queueName, $queueImplementation, $worker);
    }
}