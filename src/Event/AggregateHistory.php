<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event;

use SimpleES\EventSourcing\Exception\CollectionIsEmpty;
use SimpleES\EventSourcing\Exception\InvalidItemInCollection;
use SimpleES\EventSourcing\Identifier\Identifies;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class AggregateHistory implements \IteratorAggregate, \Countable
{
    /**
     * @var Identifies
     */
    private $aggregateId;

    /**
     * @var DomainEvent[]
     */
    private $events;

    /**
     * @param Identifies    $aggregateId
     * @param DomainEvent[] $events
     */
    public function __construct(Identifies $aggregateId, array $events)
    {
        $this->aggregateId = $aggregateId;
        $this->events      = $events;

        $this->ensureCollectionContainsEvents();
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
        return new \ArrayIterator($this->events);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->events);
    }

    /**
     * @throws InvalidItemInCollection
     */
    private function ensureCollectionContainsEvents()
    {
        foreach ($this->events as $event) {
            if (!($event instanceof DomainEvent)) {
                throw InvalidItemInCollection::create($event, 'SimpleES\EventSourcing\Event\DomainEvent');
            }
        }
    }

    /**
     * @throws CollectionIsEmpty
     */
    private function ensureCollectionIsNotEmpty()
    {
        if (count($this->events) === 0) {
            throw CollectionIsEmpty::create();
        }
    }
}
