<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Wrapper;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Event\AggregateHistory;
use SimpleES\EventSourcing\Event\DomainEvents;
use SimpleES\EventSourcing\Event\Stream\EventStream;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface WrapsEvents
{
    /**
     * @param IdentifiesAggregate $aggregateId
     * @param DomainEvents        $domainEvents
     * @return EventStream
     */
    public function wrap(IdentifiesAggregate $aggregateId, DomainEvents $domainEvents);

    /**
     * @param IdentifiesAggregate $aggregateId
     * @param EventStream         $envelopeStream
     * @return AggregateHistory
     */
    public function unwrap(IdentifiesAggregate $aggregateId, EventStream $envelopeStream);
}
