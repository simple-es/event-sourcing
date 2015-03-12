<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Repository;

use SimpleES\EventSourcing\Aggregate\Factory\ReconstitutesAggregates;
use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Aggregate\TracksEvents;
use SimpleES\EventSourcing\Event\Store\StoresEvents;
use SimpleES\EventSourcing\Event\Wrapper\WrapsEvents;
use SimpleES\EventSourcing\IdentityMap\MapsIdentity;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class AggregateRepository implements Repository
{
    /**
     * @var MapsIdentity
     */
    private $identityMap;

    /**
     * @var WrapsEvents
     */
    private $eventWrapper;

    /**
     * @var StoresEvents
     */
    private $eventStore;

    /**
     * @var ReconstitutesAggregates
     */
    private $aggregateFactory;

    /**
     * @param MapsIdentity            $identityMap
     * @param WrapsEvents             $eventWrapper
     * @param StoresEvents            $eventStore
     * @param ReconstitutesAggregates $aggregateFactory
     */
    public function __construct(
        MapsIdentity $identityMap,
        WrapsEvents $eventWrapper,
        StoresEvents $eventStore,
        ReconstitutesAggregates $aggregateFactory
    ) {
        $this->identityMap      = $identityMap;
        $this->eventWrapper     = $eventWrapper;
        $this->eventStore       = $eventStore;
        $this->aggregateFactory = $aggregateFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function add(TracksEvents $aggregate)
    {
        $this->identityMap->add($aggregate);

        $recordedEvents = $aggregate->recordedEvents();
        $aggregate->clearRecordedEvents();

        $envelopeStream = $this->eventWrapper->wrap($aggregate->aggregateId(), $recordedEvents);

        $this->eventStore->commit($envelopeStream);
    }

    /**
     * {@inheritdoc}
     */
    public function find(IdentifiesAggregate $aggregateId)
    {
        if ($this->identityMap->contains($aggregateId)) {
            return $this->identityMap->get($aggregateId);
        }

        $envelopeStream = $this->eventStore->get($aggregateId);
        $history        = $this->eventWrapper->unwrap($aggregateId, $envelopeStream);

        $aggregate = $this->aggregateFactory->reconstituteFromHistory($history);

        $this->identityMap->add($aggregate);

        return $aggregate;
    }
}
