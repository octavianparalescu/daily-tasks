<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue;


use DailyTasks\Framework\Data\Exception;
use DailyTasks\Framework\Queue\Contract\QueueImplementationInterface;
use DailyTasks\Framework\Queue\Contract\QueueItem;
use DailyTasks\Framework\Queue\Contract\QueueWorkerInterface;
use DailyTasks\Framework\Queue\Entity\PheanstalkInstance;
use DailyTasks\Framework\Queue\Factory\Key\PheanstalkInstanceKeyFactory;
use DailyTasks\Framework\Queue\Key\PheanstalkInstanceKey;
use DailyTasks\Framework\Queue\Map\PheanstalkInstanceMap;
use DailyTasks\Framework\Queue\Options\PheanstalkQueueOptions;

class PheanstalkQueueImplementation implements QueueImplementationInterface
{
    private static ?PheanstalkInstanceMap $pheanstalkInstances = null;
    /**
     * @var PheanstalkInstanceKey
     */
    private PheanstalkInstanceKey $keyForThisPheanstalkInstance;

    public function __construct(PheanstalkQueueOptions $options)
    {
        if (!self::$pheanstalkInstances) {
            self::$pheanstalkInstances = new PheanstalkInstanceMap();
        }

        $this->keyForThisPheanstalkInstance = PheanstalkInstanceKeyFactory::fromOptions($options);
        self::$pheanstalkInstances->add(
            new PheanstalkInstance(
                $this->keyForThisPheanstalkInstance, $options->getHost(), $options->getPort(), $options->getConnectTimeout()
            )
        );
    }

    public function produce(string $queueName, QueueItem $queueItem)
    {
        $serializedMessage = $queueItem->serialize();
        $this->getCurrentConnection()
             ->publish($queueName, json_encode($serializedMessage));
    }

    public function run(string $queueName, QueueWorkerInterface $queueWorker)
    {
        $serializedMessage = $this->getCurrentConnection()
                                  ->watch($queueName);
        $message = json_decode($serializedMessage, true);
        $job = call_user_func([$message[QueueItem::PARAM_CLASS_NAME], 'deserialize'], $message);
        $queueWorker->handle($job);
    }

    /**
     * @return PheanstalkInstance
     * @throws Exception
     */
    private function getCurrentConnection(): PheanstalkInstance
    {
        return self::$pheanstalkInstances->getByKey($this->keyForThisPheanstalkInstance);
    }
}