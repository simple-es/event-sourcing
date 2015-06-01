<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event;

use SimpleES\EventSourcing\Exception\CollectionIsEmpty;
use SimpleES\EventSourcing\Exception\EventStreamIsCorrupt;
use SimpleES\EventSourcing\Exception\InvalidTypeInCollection;
use SimpleES\EventSourcing\Identifier\Identifies;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EventStream implements \IteratorAggregate, \Countable
{
    /**
     * @var Identifies
     */
    private $aggregateId;

    /**
     * @var EnvelopsEvent[]
     */
    private $envelopes;

    /**
     * @param Identifies      $aggregateId
     * @param EnvelopsEvent[] $envelopes
     */
    public function __construct(Identifies $aggregateId, array $envelopes)
    {
        $this->aggregateId = $aggregateId;
        $this->envelopes   = $envelopes;

        $this->ensureCollectionContainsEnvelopesWithSameAggregateId();
        $this->ensureCollectionIsNotEmpty();
    }

    /**
     * @return Identifies
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->envelopes);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->envelopes);
    }

    /**
     * @throws InvalidTypeInCollection
     * @throws EventStreamIsCorrupt
     */
    private function ensureCollectionContainsEnvelopesWithSameAggregateId()
    {
        foreach ($this->envelopes as $envelope) {
            if (!($envelope instanceof EnvelopsEvent)) {
                throw InvalidTypeInCollection::create($envelope, 'SimpleES\EventSourcing\Event\EnvelopsEvent');
            }

            if (!$envelope->aggregateId()->equals($this->aggregateId)) {
                throw EventStreamIsCorrupt::create($envelope->aggregateId(), $this->aggregateId);
            }
        }
    }

    /**
     * @throws CollectionIsEmpty
     */
    private function ensureCollectionIsNotEmpty()
    {
        if (count($this->envelopes) === 0) {
            throw CollectionIsEmpty::create();
        }
    }
}
