<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Wrapper;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Collection\AggregateHistory;
use SimpleES\EventSourcing\Collection\EventEnvelopeStream;
use SimpleES\EventSourcing\Collection\EventStream;
use SimpleES\EventSourcing\Event\EventEnvelope;
use SimpleES\EventSourcing\Event\SerializableEvent;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EventWrapper implements WrapsEvents
{
    /**
     * @var array
     */
    private $playheads;

    /**
     * {@inheritdoc}
     */
    public function wrap(IdentifiesAggregate $aggregateId, EventStream $eventStream)
    {
        $lookupKey = (string)$aggregateId;

        if (!isset($this->playheads[$lookupKey])) {
            $this->playheads[$lookupKey] = -1;
        }

        $envelopes = [];

        /** @var SerializableEvent $event */
        foreach ($eventStream as $event) {
            $playhead = ++$this->playheads[$lookupKey];

            $envelopes[] = EventEnvelope::wrap($event, $playhead);
        }

        return new EventEnvelopeStream($envelopes);
    }

    /**
     * {@inheritdoc}
     */
    public function unwrap(IdentifiesAggregate $aggregateId, EventEnvelopeStream $envelopeStream)
    {
        $lookupKey = (string)$aggregateId;

        $events = [];

        /** @var EventEnvelope $eventEnvelope */
        foreach ($envelopeStream as $eventEnvelope) {
            $this->playheads[$lookupKey] = $eventEnvelope->playhead();

            $events[] = $eventEnvelope->event();
        }

        return new AggregateHistory($aggregateId, $events);
    }
}
