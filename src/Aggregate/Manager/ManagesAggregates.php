<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Aggregate\Manager;

use SimpleES\EventSourcing\Aggregate\TracksEvents;
use SimpleES\EventSourcing\Identifier\Identifies;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface ManagesAggregates
{
    /**
     * @param TracksEvents $aggregate
     * @return void
     */
    public function save(TracksEvents $aggregate);

    /**
     * @param Identifies $aggregateId
     * @return TracksEvents
     */
    public function fetch(Identifies $aggregateId);

    /**
     * @return void
     */
    public function clear();
}
