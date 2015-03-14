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
interface MapsIdentity
{
    /**
     * @param TracksEvents $aggregate
     * @return void
     * @throws DuplicateAggregateFound
     */
    public function add(TracksEvents $aggregate);

    /**
     * @param Identifies $aggregateId
     * @return bool
     */
    public function contains(Identifies $aggregateId);

    /**
     * @param Identifies $aggregateId
     * @return TracksEvents
     * @throws AggregateIdNotFound
     */
    public function get(Identifies $aggregateId);

    /**
     * @return void
     */
    public function clear();
}
