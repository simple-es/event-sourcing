<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\UnitOfWork;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Aggregate\TracksEvents;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
interface TracksAggregates
{
    /**
     * @param TracksEvents $aggregate
     * @return void
     */
    public function track(TracksEvents $aggregate);

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return TracksEvents
     */
    public function find(IdentifiesAggregate $aggregateId);

    /**
     * @return void
     */
    public function commit();
}
