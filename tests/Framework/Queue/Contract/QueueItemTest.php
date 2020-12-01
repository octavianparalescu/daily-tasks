<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Contract;

use PHPUnit\Framework\TestCase;

/**
 * Class QueueItemTest
 * @covers  \DailyTasks\Framework\Queue\Contract\QueueItem
 * @package DailyTasks\Framework\Queue\Contract
 */
class QueueItemTest extends TestCase
{
    public function testSerializationAndDeserialization()
    {
        $anObject = new TestQueueItem('test', ['test1', 'test2']);
        $serialized = $anObject->serialize();
        $this->assertIsArray($serialized);
        $deserialized = TestQueueItem::deserialize($serialized);
        $this->assertEquals($anObject->getTestString(), $deserialized->getTestString());
        $this->assertEquals($anObject->getTestArray(), $deserialized->getTestArray());
    }
}
