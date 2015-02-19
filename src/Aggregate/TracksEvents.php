<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Aggregate;

use SimpleES\EventSourcing\Collection\AggregateHistory;
use SimpleES\EventSourcing\Collection\EventStream;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
interface TracksEvents
{
    /**
     * @param AggregateHistory $aggregateHistory
     * @return TracksEvents
     */
    public static function fromHistory(AggregateHistory $aggregateHistory);

    /**
     * @return IdentifiesAggregate
     */
    public function aggregateId();

    /**
     * @return EventStream
     */
    public function recordedEvents();

    /**
     * @return bool
     */
    public function hasRecordedEvents();

    /**
     * @return void
     */
    public function clearRecordedEvents();
}
