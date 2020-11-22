<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue;


use DailyTasks\Framework\Data\Exception;
use DailyTasks\Framework\Queue\Entity\Queue;
use DailyTasks\Framework\Queue\Map\QueueMap;

class QueueManager
{
    public QueueMap $activeQueues;

    public function __construct()
    {
        $this->activeQueues = new QueueMap();
    }

    public function registerQueue(Queue $queue): void
    {
        $this->activeQueues->add($queue);
    }

    /**
     * @param string $queueName
     *
     * @throws Exception
     */
    public function work(string $queueName)
    {
        /** @var Queue $queue */
        $queue = $this->activeQueues->getByKey($queueName);

        $queue->work($queueName);
    }

    public function workDomain(string $domainName)
    {
    }
}