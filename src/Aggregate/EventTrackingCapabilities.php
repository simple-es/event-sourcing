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
     * @param AggregateHistory $aggregateHistory
     * @return TracksEvents
     */
    public static function fromHistory(AggregateHistory $aggregateHistory)
    {
        $aggregate = new static();
        $aggregate->replayHistory($aggregateHistory);

        return $aggregate;
    }

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
        $method = $this->whenMethod($event);

        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($event);
    }

    /**
     * @param DomainEvent $event
     *
     * @return string
     */
    private function whenMethod(DomainEvent $event)
    {
        $classPart = get_class($event);

        if (($pos = strrpos($classPart, '\\')) !== false) {
            $classPart = substr($classPart, $pos + 1);
        }

        return 'when' . ucfirst($classPart);
    }

    private function __construct()
    {
    }
}
