<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Aggregate;

use SimpleES\EventSourcing\Event\AggregateHistory;
use SimpleES\EventSourcing\Event\DomainEvents;
use SimpleES\EventSourcing\Identifier\Identifies;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface TracksEvents
{
    /**
     * @param AggregateHistory $aggregateHistory
     * @return TracksEvents
     */
    public static function fromHistory(AggregateHistory $aggregateHistory);

    /**
     * @return Identifies
     */
    public function aggregateId();

    /**
     * @return DomainEvents
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
