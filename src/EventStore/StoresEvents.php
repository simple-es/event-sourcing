<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\EventStore;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Collection\EventEnvelopeStream;
use SimpleES\EventSourcing\Exception\AggregateIdNotFound;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
interface StoresEvents
{
    /**
     * @param EventEnvelopeStream $envelopeStream
     * @return void
     */
    public function commit(EventEnvelopeStream $envelopeStream);

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return EventEnvelopeStream
     * @throws AggregateIdNotFound
     */
    public function get(IdentifiesAggregate $aggregateId);
}
