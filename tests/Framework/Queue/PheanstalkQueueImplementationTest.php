<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue;

use DailyTasks\Framework\Queue\Contract\QueueItem;
use DailyTasks\Framework\Queue\Contract\QueueWorkerInterface;
use DailyTasks\Framework\Queue\Options\PheanstalkQueueOptions;
use PHPUnit\Framework\TestCase;

class PheanstalkQueueImplementationTest extends TestCase
{
    /**
     * @var PheanstalkQueueOptions
     */
    private static PheanstalkQueueOptions $options;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$options = new PheanstalkQueueOptions('127.0.0.1', 1130);

        // Create a beanstalkd instance
        exec('beanstalkd -l ' . self::$options->getHost() . '  -p ' . self::$options->getPort() . " > /logs/beanstalkd.log &");
    }

    public function testPush()
    {
        $message = new class extends QueueItem {
            public string $data = 'test';
        };
        $queueImplementation = new PheanstalkQueueImplementation(self::$options);
        $queueImplementation->produce('test', $message);
        $queueImplementation->produce('test', $message);
        $queueImplementation->produce('test', $message);
        $queueImplementation->produce('test', $message);
        $this->addToAssertionCount(1);
    }

    /**
     * @depends testPush
     */
    public function testReceive()
    {
        $queueImplementation = new PheanstalkQueueImplementation(self::$options);

        $this->expectOutputString('test');
        $queueImplementation->run(
            'test',
            new class implements QueueWorkerInterface {
                public function handle(QueueItem $queueItem)
                {
                    echo $queueItem->data;
                }
            }
        );
    }
}
