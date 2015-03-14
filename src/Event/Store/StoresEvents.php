<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Store;

use SimpleES\EventSourcing\Event\Stream\EventStream;
use SimpleES\EventSourcing\Exception\AggregateIdNotFound;
use SimpleES\EventSourcing\Identifier\Identifies;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface StoresEvents
{
    /**
     * @param EventStream $eventStream
     * @return void
     */
    public function commit(EventStream $eventStream);

    /**
     * @param Identifies $aggregateId
     * @return EventStream
     * @throws AggregateIdNotFound
     */
    public function read(Identifies $aggregateId);
}
