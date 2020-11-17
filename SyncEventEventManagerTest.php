<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Event;

use DailyTasks\Framework\Event\Contract\EventInterface;
use DailyTasks\Framework\Event\Contract\ListenerInterface;
use PHPUnit\Framework\TestCase;
use Throwable;

class SyncEventEventManagerTest extends TestCase
{
    public function testSubscribe()
    {
        [$listener1, $listener2, $listener3, $event1, $event2] = $this->setupListenersAndEvents();

        $eventManager = new SyncEventEventManager();
        $eventManager->subscribe($listener1, get_class($event1));
        $eventManager->subscribe($listener2, get_class($event1));
        $eventManager->subscribe($listener3, get_class($event2));

        $listeners = $eventManager->getListeners();
        $this->assertEquals(
            $listeners[get_class($event1)],
            [
                $listener1,
                $listener2,
            ]
        );
        $this->assertEquals(
            $listeners[get_class($event2)],
            [
                $listener3,
            ]
        );
    }

    public function testShouldThrowWhenSubscribingToNonExistentEvents()
    {
        $testListener1 = new class implements ListenerInterface {
            public function handle(EventInterface $event)
            {
            }
        };

        $eventManager = new SyncEventEventManager();

        try {
            $eventManager->subscribe($testListener1, 'non-existing-test-className');
            $this->fail();
        } catch (Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
        }
    }

    public function testPublish()
    {
        [$listener1, $listener2, $listener3, $event1, $event2] = $this->setupListenersAndEvents();

        $eventManager = new SyncEventEventManager();
        $eventManager->subscribe($listener2, get_class($event1));
        $eventManager->subscribe($listener1, get_class($event1));
        $eventManager->subscribe($listener3, get_class($event2));

        $eventManager->publish($event1);
        $eventManager->publish($event2);
        $this->expectOutputString('listener2listener1listener3');
    }

    /**
     * @return array
     */
    private function setupListenersAndEvents(): array
    {
        $listener1 = new class implements ListenerInterface {
            public function handle(EventInterface $event)
            {
                echo 'listener1';
            }
        };
        $listener2 = new class implements ListenerInterface {
            public function handle(EventInterface $event)
            {
                echo 'listener2';
            }
        };
        $listener3 = new class implements ListenerInterface {
            public function handle(EventInterface $event)
            {
                echo 'listener3';
            }
        };
        $event1 = new class implements EventInterface {
        };
        $event2 = new class implements EventInterface {
        };

        return [$listener1, $listener2, $listener3, $event1, $event2];
    }
}
