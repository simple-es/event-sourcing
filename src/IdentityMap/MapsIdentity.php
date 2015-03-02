<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\IdentityMap;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Aggregate\TracksEvents;
use SimpleES\EventSourcing\Exception\AggregateIdNotFound;
use SimpleES\EventSourcing\Exception\DuplicateAggregateFound;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface MapsIdentity
{
    /**
     * @param TracksEvents $aggregate
     * @return void
     * @throws DuplicateAggregateFound
     */
    public function add(TracksEvents $aggregate);

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return bool
     */
    public function contains(IdentifiesAggregate $aggregateId);

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return TracksEvents
     * @throws AggregateIdNotFound
     */
    public function get(IdentifiesAggregate $aggregateId);

    /**
     * @return void
     */
    public function clear();
}
