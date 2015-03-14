<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Aggregate;

use SimpleES\EventSourcing\Event\AggregateHistory;
use SimpleES\EventSourcing\Event\DomainEvent;
use SimpleES\EventSourcing\Event\DomainEvents;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
trait EventTrackingCapabilities
{
    /**
     * @var DomainEvent[]
     */
    private $recordedEvents = [];

    /**
     * @return DomainEvents
     */
    public function recordedEvents()
    {
        return new DomainEvents(
            $this->recordedEvents
        );
    }

    /**
     * @return bool
     */
    public function hasRecordedEvents()
    {
        return (bool) $this->recordedEvents;
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
        /** @var DomainEvent $event */
        foreach ($aggregateHistory as $event) {
            $this->when($event);
        }
    }

    /**
     * @param DomainEvent $event
     */
    private function recordThat(DomainEvent $event)
    {
        $this->recordedEvents[] = $event;

        $this->when($event);
    }

    /**
     * @param DomainEvent $event
     */
    private function when(DomainEvent $event)
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
