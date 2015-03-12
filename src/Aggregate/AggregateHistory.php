<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Aggregate;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Event\DomainEvent;
use SimpleES\EventSourcing\Exception\CollectionIsEmpty;
use SimpleES\EventSourcing\Exception\InvalidItemInCollection;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class AggregateHistory implements \IteratorAggregate, \Countable
{
    /**
     * @var IdentifiesAggregate
     */
    private $aggregateId;

    /**
     * @var DomainEvent[]
     */
    private $events;

    /**
     * @param IdentifiesAggregate $aggregateId
     * @param DomainEvent[]       $events
     */
    public function __construct(IdentifiesAggregate $aggregateId, array $events)
    {
        $this->aggregateId = $aggregateId;
        $this->events      = $events;

        $this->ensureCollectionContainsEvents();
        $this->ensureCollectionIsNotEmpty();
    }

    /**
     * @return IdentifiesAggregate
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
