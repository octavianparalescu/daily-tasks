<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Contract;


interface QueueImplementationInterface
{
    /**
     * Should add an item to the queue
     *
     * @param string    $queueName
     * @param QueueItem $queueItem
     *
     * @return void
     */
    public function produce(string $queueName, QueueItem $queueItem);

    /**
     * Should poll the queue for messages and call the queue's worker handler method with messages
     *
     * @param string               $queueName
     * @param QueueWorkerInterface $queueWorker
     *
     * @return void
     */
    public function run(string $queueName, QueueWorkerInterface $queueWorker);
}