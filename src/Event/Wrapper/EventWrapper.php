<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Wrapper;

use SimpleES\EventSourcing\Event\AggregateHistory;
use SimpleES\EventSourcing\Event\DomainEvent;
use SimpleES\EventSourcing\Event\DomainEvents;
use SimpleES\EventSourcing\Event\NameResolver\ResolvesEventNames;
use SimpleES\EventSourcing\Event\Stream\EnvelopsEvent;
use SimpleES\EventSourcing\Event\Stream\EventId;
use SimpleES\EventSourcing\Event\Stream\EventStream;
use SimpleES\EventSourcing\Exception\InvalidType;
use SimpleES\EventSourcing\Identifier\GeneratesIdentifiers;
use SimpleES\EventSourcing\Identifier\Identifies;
use SimpleES\EventSourcing\Metadata\Metadata;
use SimpleES\EventSourcing\Timestamp\Timestamp;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EventWrapper implements WrapsEvents
{
    /**
     * @var GeneratesIdentifiers
     */
    private $identifierGenerator;

    /**
     * @var ResolvesEventNames
     */
    private $eventNameResolver;

    /**
     * @var string
     */
    private $eventEnvelopeClass;

    /**
     * @var array
     */
    private $aggregateVersions;

    /**
     * @param GeneratesIdentifiers $identifierGenerator
     * @param ResolvesEventNames   $eventNameResolver
     * @param string               $eventEnvelopeClass
     */
    public function __construct(
        GeneratesIdentifiers $identifierGenerator,
        ResolvesEventNames $eventNameResolver,
        $eventEnvelopeClass
    ) {
        $this->identifierGenerator = $identifierGenerator;
        $this->eventNameResolver   = $eventNameResolver;
        $this->eventEnvelopeClass  = $eventEnvelopeClass;

        $this->guardEventEnvelopeClass($this->eventEnvelopeClass);
    }

    /**
     * {@inheritdoc}
     */
    public function wrap(Identifies $aggregateId, DomainEvents $domainEvents)
    {
        $lookupKey = (string) $aggregateId;

        if (!isset($this->aggregateVersions[$lookupKey])) {
            $this->aggregateVersions[$lookupKey] = -1;
        }

        $envelopes = [];

        $eventEnvelopeClass = $this->eventEnvelopeClass;

        /** @var DomainEvent $event */
        foreach ($domainEvents as $event) {
            $aggregateVersion = ++$this->aggregateVersions[$lookupKey];

            $envelopes[] = $eventEnvelopeClass::envelop(
                EventId::fromString($this->identifierGenerator->generateIdentifier()),
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
    public function unwrap(EventStream $envelopeStream)
    {
        $lookupKey = (string) $envelopeStream->aggregateId();

        $events = [];

        /** @var EnvelopsEvent $envelope */
        foreach ($envelopeStream as $envelope) {
            $this->aggregateVersions[$lookupKey] = $envelope->aggregateVersion();

            $events[] = $envelope->event();
        }

        return new AggregateHistory($envelopeStream->aggregateId(), $events);
    }

    /**
     * @param string $class
     * @throws InvalidType
     */
    private function guardEventEnvelopeClass($class)
    {
        $interface = 'SimpleES\EventSourcing\Event\Stream\EnvelopsEvent';

        if (!is_string($class) || !is_subclass_of($class, $interface)) {
            throw InvalidType::create($class, $interface);
        }
    }
}
