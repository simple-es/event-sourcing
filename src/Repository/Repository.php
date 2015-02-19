<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Repository;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Aggregate\TracksEvents;
use SimpleES\EventSourcing\Exception\AggregateIdNotFound;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface Repository
{
    /**
     * @param TracksEvents $aggregate
     * @return void
     */
    public function add(TracksEvents $aggregate);

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return TracksEvents
     * @throws AggregateIdNotFound
     */
    public function find(IdentifiesAggregate $aggregateId);
}
