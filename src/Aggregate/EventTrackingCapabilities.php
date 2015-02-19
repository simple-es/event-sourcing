<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Aggregate;

use SimpleES\EventSourcing\Collection\AggregateHistory;
use SimpleES\EventSourcing\Collection\EventStream;
use SimpleES\EventSourcing\Event\SerializableEvent;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
trait EventTrackingCapabilities
{
    /**
     * @var SerializableEvent[]
     */
    private $recordedEvents = [];

    /**
     * @return EventStream
     */
    public function recordedEvents()
    {
        return new EventStream(
            $this->recordedEvents
        );
    }

    /**
     * @return bool
     */
    public function hasRecordedEvents()
    {
        return (bool)$this->recordedEvents;
    }

    /**
     * @return void
     */
    public function clearRecordedEvents()
    {
        $this->recordedEvents = [];
    }

    /**
     * @param AggregateHistory $aggregateHistory
     */
    private function replayHistory(AggregateHistory $aggregateHistory)
    {
        /** @var SerializableEvent $event */
        foreach ($aggregateHistory as $event) {
            $this->when($event);
        }
    }

    /**
     * @param SerializableEvent $event
     */
    private function recordThat(SerializableEvent $event)
    {
        $this->recordedEvents[] = $event;

        $this->when($event);
    }

    /**
     * @param SerializableEvent $event
     */
    private function when(SerializableEvent $event)
    {
        $method = get_class($event);

        if (($pos = strrpos($method, '\\')) !== false) {
            $method = substr($method, $pos + 1);
        }

        $method = 'when' . ucfirst($method);

        if (is_callable([$this, $method])) {
            call_user_func([$this, $method], $event);
        }
    }
}
