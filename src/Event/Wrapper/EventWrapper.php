<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Wrapper;

use SimpleES\EventSourcing\Event\AggregateHistory;
use SimpleES\EventSourcing\Event\DomainEvent;
use SimpleES\EventSourcing\Event\DomainEvents;
use SimpleES\EventSourcing\Event\EnvelopsEvent;
use SimpleES\EventSourcing\Event\EventStream;
use SimpleES\EventSourcing\Event\NameResolver\ResolvesEventNames;
use SimpleES\EventSourcing\Exception\InvalidType;
use SimpleES\EventSourcing\Identifier\Generator\GeneratesIdentifiers;
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
    private $eventIdGenerator;

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
     * @param GeneratesIdentifiers $eventIdGenerator
     * @param ResolvesEventNames   $eventNameResolver
     * @param string               $eventEnvelopeClass
     */
    public function __construct(
        GeneratesIdentifiers $eventIdGenerator,
        ResolvesEventNames $eventNameResolver,
        $eventEnvelopeClass = 'SimpleES\EventSourcing\Event\EventEnvelope'
    ) {
        $this->eventIdGenerator   = $eventIdGenerator;
        $this->eventNameResolver  = $eventNameResolver;
        $this->eventEnvelopeClass = $eventEnvelopeClass;

        $this->guardEventEnvelopeClass($this->eventEnvelopeClass);
    }

    /**
     * {@inheritdoc}
     */
    public function wrap(Identifies $aggregateId, DomainEvents $domainEvents)
    {
        $lookupKey = $aggregateId->toString();

        if (!isset($this->aggregateVersions[$lookupKey])) {
            $this->aggregateVersions[$lookupKey] = -1;
        }

        $envelopeClass = $this->eventEnvelopeClass;
        $envelopes     = [];

        /** @var DomainEvent $event */
        foreach ($domainEvents as $event) {
            $aggregateVersion = ++$this->aggregateVersions[$lookupKey];

            /** @noinspection PhpUndefinedMethodInspection */
            $envelopes[] = $envelopeClass::envelop(
                $this->eventIdGenerator->generateIdentifier(),
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
        $lookupKey = $envelopeStream->aggregateId()->toString();

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
        $interface = 'SimpleES\EventSourcing\Event\EnvelopsEvent';

        if (!is_string($class) || !is_subclass_of($class, $interface)) {
            throw InvalidType::create($class, $interface);
        }
    }
}
