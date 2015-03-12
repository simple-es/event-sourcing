<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event;

use SimpleES\EventSourcing\Exception\CollectionIsEmpty;
use SimpleES\EventSourcing\Exception\InvalidItemInCollection;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class DomainEvents implements \IteratorAggregate, \Countable
{
    /**
     * @var DomainEvent[]
     */
    private $events;

    /**
     * @param DomainEvent[] $events
     */
    public function __construct(array $events)
    {
        $this->events = $events;

        $this->ensureCollectionContainsEvents();
        $this->ensureCollectionIsNotEmpty();
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
