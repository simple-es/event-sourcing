<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Repository;

use SimpleES\EventSourcing\Aggregate\Factory\ReconstitutesAggregates;
use SimpleES\EventSourcing\Aggregate\TracksEvents;
use SimpleES\EventSourcing\Event\Store\StoresEvents;
use SimpleES\EventSourcing\Event\Wrapper\WrapsEvents;
use SimpleES\EventSourcing\Identifier\Identifies;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class AggregateRepository implements Repository
{
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
     * @param WrapsEvents             $eventWrapper
     * @param StoresEvents            $eventStore
     * @param ReconstitutesAggregates $aggregateFactory
     */
    public function __construct(
        WrapsEvents $eventWrapper,
        StoresEvents $eventStore,
        ReconstitutesAggregates $aggregateFactory
    ) {
        $this->eventWrapper     = $eventWrapper;
        $this->eventStore       = $eventStore;
        $this->aggregateFactory = $aggregateFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(TracksEvents $aggregate)
    {
        $recordedEvents = $aggregate->recordedEvents();
        $aggregate->clearRecordedEvents();

        $envelopeStream = $this->eventWrapper->wrap($aggregate->aggregateId(), $recordedEvents);

        $this->eventStore->commit($envelopeStream);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(Identifies $aggregateId)
    {
        $envelopeStream = $this->eventStore->read($aggregateId);
        $history        = $this->eventWrapper->unwrap($aggregateId, $envelopeStream);

        return $this->aggregateFactory->reconstituteFromHistory($history);
    }
}
