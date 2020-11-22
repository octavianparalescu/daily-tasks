<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue;


use DailyTasks\Framework\Queue\Contract\QueueImplementationInterface;
use DailyTasks\Framework\Queue\Contract\QueueItem;
use DailyTasks\Framework\Queue\Contract\QueueWorkerInterface;

class SyncQueueImplementation implements QueueImplementationInterface
{
    private ?QueueItem $queueItem = null;
    /**
     * @var QueueWorkerInterface
     */
    private QueueWorkerInterface $worker;

    public function __construct(QueueWorkerInterface $worker)
    {
        $this->worker = $worker;
    }

    public function produce(string $queueName, QueueItem $queueItem)
    {
        $this->queueItem = $queueItem;
        $this->run($queueName, $this->worker);
    }

    public function run(string $queueName, QueueWorkerInterface $queueWorker)
    {
        $queueWorker->handle($this->queueItem);
    }
}