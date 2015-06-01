<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Wrapper;

use SimpleES\EventSourcing\Event\AggregateHistory;
use SimpleES\EventSourcing\Event\DomainEvents;
use SimpleES\EventSourcing\Event\EventStream;
use SimpleES\EventSourcing\Identifier\Identifies;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface WrapsEvents
{
    /**
     * @param Identifies   $aggregateId
     * @param DomainEvents $domainEvents
     * @return EventStream
     */
    public function wrap(Identifies $aggregateId, DomainEvents $domainEvents);

    /**
     * @param EventStream $envelopeStream
     * @return AggregateHistory
     */
    public function unwrap(EventStream $envelopeStream);
}
