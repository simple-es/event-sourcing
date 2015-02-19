<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Aggregate\Factory;

use SimpleES\EventSourcing\Aggregate\TracksEvents;
use SimpleES\EventSourcing\Collection\AggregateHistory;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
interface ReconstitutesAggregates
{
    /**
     * @param AggregateHistory $aggregateHistory
     * @return TracksEvents
     */
    public function reconstituteFromHistory(AggregateHistory $aggregateHistory);
}
