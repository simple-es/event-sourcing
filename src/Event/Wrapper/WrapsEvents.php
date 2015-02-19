<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Wrapper;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Collection\AggregateHistory;
use SimpleES\EventSourcing\Collection\EventEnvelopeStream;
use SimpleES\EventSourcing\Collection\EventStream;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface WrapsEvents
{
    /**
     * @param IdentifiesAggregate $aggregateId
     * @param EventStream         $eventStream
     * @return EventEnvelopeStream
     */
    public function wrap(IdentifiesAggregate $aggregateId, EventStream $eventStream);

    /**
     * @param IdentifiesAggregate $aggregateId
     * @param EventEnvelopeStream $envelopeStream
     * @return AggregateHistory
     */
    public function unwrap(IdentifiesAggregate $aggregateId, EventEnvelopeStream $envelopeStream);
}
