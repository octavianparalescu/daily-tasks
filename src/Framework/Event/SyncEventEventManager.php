<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Event;


use DailyTasks\Framework\Event\Contract\EventInterface;
use DailyTasks\Framework\Event\Contract\EventManagerInterface;
use DailyTasks\Framework\Event\Contract\ListenerInterface;

class SyncEventEventManager implements EventManagerInterface
{
    protected array $listeners = [];

    /**
     * We check if the listener is a callable during the
     *
     * @param ListenerInterface $listener
     * @param string            $eventClassName
     *
     * @throws Exception
     */
    public function subscribe(ListenerInterface $listener, string $eventClassName)
    {
        if (!class_exists($eventClassName)) {
            throw new Exception("Cannot subscribe to non-existing event $eventClassName.");
        }

        if (!array_key_exists($eventClassName, $this->listeners)) {
            $this->listeners[$eventClassName] = [];
        }

        $this->listeners[$eventClassName][] = $listener;
    }

    public function publish(EventInterface $event)
    {
        $eventClass = get_class($event);
        if (!empty($this->listeners[$eventClass])) {
            /** @var ListenerInterface $subscriber */
            foreach ($this->listeners[$eventClass] as $subscriber) {
                $subscriber->handle($event);
            }
        }
    }

    /**
     * @return array
     */
    public function getListeners(): array
    {
        return $this->listeners;
    }
}