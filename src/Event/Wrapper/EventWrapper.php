<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Wrapper;

use SimpleES\EventSourcing\Aggregate\AggregateHistory;
use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Event\DomainEvent;
use SimpleES\EventSourcing\Event\DomainEvents;
use SimpleES\EventSourcing\Event\Resolver\ResolvesEventNames;
use SimpleES\EventSourcing\Event\Stream\EventEnvelope;
use SimpleES\EventSourcing\Event\Stream\EventId;
use SimpleES\EventSourcing\Event\Stream\EventStream;
use SimpleES\EventSourcing\Metadata\Metadata;
use SimpleES\EventSourcing\Timestamp\Timestamp;
use SimpleES\EventSourcing\Uuid\GeneratesUuids;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EventWrapper implements WrapsEvents
{
    /**
     * @var GeneratesUuids
     */
    private $uuidGenerator;

    /**
     * @var ResolvesEventNames
     */
    private $eventNameResolver;

    /**
     * @var array
     */
    private $aggregateVersions;

    /**
     * @param GeneratesUuids     $uuidGenerator
     * @param ResolvesEventNames $eventNameResolver
     */
    public function __construct(GeneratesUuids $uuidGenerator, ResolvesEventNames $eventNameResolver)
    {
        $this->uuidGenerator     = $uuidGenerator;
        $this->eventNameResolver = $eventNameResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function wrap(IdentifiesAggregate $aggregateId, DomainEvents $domainEvents)
    {
        $lookupKey = (string)$aggregateId;

        if (!isset($this->aggregateVersions[$lookupKey])) {
            $this->aggregateVersions[$lookupKey] = -1;
        }

        $envelopes = [];

        /** @var DomainEvent $event */
        foreach ($domainEvents as $event) {
            $aggregateVersion = ++$this->aggregateVersions[$lookupKey];

            $envelopes[] = new EventEnvelope(
                EventId::fromString($this->uuidGenerator->generateUuid()),
                $this->eventNameResolver->resolveEventName($event),
                $event,
                $aggregateId,
                $aggregateVersion,
                Timestamp::now(),
                new Metadata([])
            );
        }

        return new EventStream($aggregateId, $envelopes);
    }

    /**
     * {@inheritdoc}
     */
    public function unwrap(IdentifiesAggregate $aggregateId, EventStream $envelopeStream)
    {
        $lookupKey = (string)$aggregateId;

        $events = [];

        /** @var EventEnvelope $envelope */
        foreach ($envelopeStream as $envelope) {
            $this->aggregateVersions[$lookupKey] = $envelope->aggregateVersion();

            $events[] = $envelope->event();
        }

        return new AggregateHistory($aggregateId, $events);
    }
}
