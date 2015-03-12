<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Aggregate;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Event\DomainEvents;

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
     * @return IdentifiesAggregate
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
