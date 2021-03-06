<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\IdentityMap;

use SimpleES\EventSourcing\Aggregate\TracksEvents;
use SimpleES\EventSourcing\Exception\AggregateIdNotFound;
use SimpleES\EventSourcing\Exception\DuplicateAggregateFound;
use SimpleES\EventSourcing\Identifier\Identifies;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class IdentityMap implements MapsIdentity
{
    /**
     * @var TracksEvents[]
     */
    private $map;

    public function __construct()
    {
        $this->map = [];
    }

    /**
     * {@inheritdoc}
     */
    public function add(TracksEvents $aggregate)
    {
        $lookupKey = $this->createLookupKey($aggregate->aggregateId());

        if (isset($this->map[$lookupKey])) {
            throw DuplicateAggregateFound::create($aggregate->aggregateId());
        }

        $this->map[$lookupKey] = $aggregate;
    }

    /**
     * {@inheritdoc}
     */
    public function contains(Identifies $aggregateId)
    {
        $lookupKey = $this->createLookupKey($aggregateId);

        return isset($this->map[$lookupKey]);
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identifies $aggregateId)
    {
        $lookupKey = $this->createLookupKey($aggregateId);

        if (!isset($this->map[$lookupKey])) {
            throw AggregateIdNotFound::create($aggregateId);
        }

        return $this->map[$lookupKey];
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->map = [];
    }

    /**
     * @param Identifies $aggregateId
     * @return string
     */
    private function createLookupKey(Identifies $aggregateId)
    {
        return sprintf('%s(%s)', get_class($aggregateId), $aggregateId->toString());
    }
}
