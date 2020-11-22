<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Entity;


use DailyTasks\Framework\Data\Identifiable;
use DailyTasks\Framework\Queue\Contract\QueueImplementationInterface;
use DailyTasks\Framework\Queue\Contract\QueueItem;
use DailyTasks\Framework\Queue\Contract\QueueWorkerInterface;

class Queue implements Identifiable
{
    private string $queueName;
    /**
     * @var QueueImplementationInterface
     */
    private QueueImplementationInterface $queueImplementation;
    /**
     * @var QueueWorkerInterface
     */
    private QueueWorkerInterface $worker;

    public function __construct(
        string $queueName,
        QueueImplementationInterface $queueImplementation,
        QueueWorkerInterface $worker
    ) {
        $this->queueName = $queueName;
        $this->queueImplementation = $queueImplementation;
        $this->worker = $worker;
    }

    /**
     * @return string
     */
    public function getQueueName(): string
    {
        return $this->queueName;
    }

    /**
     * @return QueueImplementationInterface
     */
    public function getQueueImplementation(): QueueImplementationInterface
    {
        return $this->queueImplementation;
    }

    /**
     * @return QueueWorkerInterface
     */
    public function getWorker(): QueueWorkerInterface
    {
        return $this->worker;
    }

    public function getKey(): string
    {
        return $this->getQueueName();
    }

    public function publish(QueueItem $queueItem)
    {
        $this->getQueueImplementation()
             ->produce($this->queueName, $queueItem);
    }

    public function work(string $queueName): void
    {
        $this->getQueueImplementation()
             ->run($queueName, $this->worker);
    }
}