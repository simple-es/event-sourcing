<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Collection;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Event\SerializableEvent;
use SimpleES\EventSourcing\Exception\AggregateHistoryIsCorrupt;
use SimpleES\EventSourcing\Exception\CollectionIsEmpty;
use SimpleES\EventSourcing\Exception\InvalidItemInCollection;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class AggregateHistory extends Collection
{
    /**
     * @var IdentifiesAggregate
     */
    private $aggregateId;

    /**
     * @param IdentifiesAggregate $aggregateId
     * @param array               $items
     */
    public function __construct(IdentifiesAggregate $aggregateId, array $items)
    {
        $this->aggregateId = $aggregateId;

        parent::__construct($items);
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
    protected function guardItem($item)
    {
        if (!($item instanceof SerializableEvent)) {
            throw InvalidItemInCollection::create($item, 'SimpleES\EventSourcing\Event\SerializableEvent');
        }

        if (!$item->aggregateId()->equals($this->aggregateId)) {
            throw AggregateHistoryIsCorrupt::create($item->aggregateId(), $this->aggregateId);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function guardAmountOfItems($amount)
    {
        if ($amount === 0) {
            throw CollectionIsEmpty::create();
        }
    }
}
