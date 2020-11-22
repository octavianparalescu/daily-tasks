<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Contract;


interface QueueWorkerInterface
{
    public function handle(QueueItem $queueItem);
}