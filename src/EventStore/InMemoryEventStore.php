<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\EventStore;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Collection\EventEnvelopeStream;
use SimpleES\EventSourcing\Event\EventEnvelope;
use SimpleES\EventSourcing\Exception\AggregateIdNotFound;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class InMemoryEventStore implements StoresEvents
{
    /**
     * @var EventEnvelope[]
     */
    private $store;

    /**
     * {@inheritdoc}
     */
    public function commit(EventEnvelopeStream $envelopeStream)
    {
        foreach ($envelopeStream as $eventEnvelope) {
            $this->store[] = $eventEnvelope;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdentifiesAggregate $aggregateId)
    {
        $eventEnvelopes = [];

        foreach ($this->store as $eventEnvelope) {
            if ($eventEnvelope->aggregateId()->equals($aggregateId)) {
                $eventEnvelopes[] = $eventEnvelope;
            }
        }

        if (!$eventEnvelopes) {
            throw AggregateIdNotFound::create($aggregateId);
        }

        return new EventEnvelopeStream($eventEnvelopes);
    }
}
