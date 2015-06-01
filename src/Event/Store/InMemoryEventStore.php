<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Store;

use SimpleES\EventSourcing\Event\EnvelopsEvent;
use SimpleES\EventSourcing\Event\EventStream;
use SimpleES\EventSourcing\Exception\AggregateIdNotFound;
use SimpleES\EventSourcing\Identifier\Identifies;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class InMemoryEventStore implements StoresEvents
{
    /**
     * @var EnvelopsEvent[]
     */
    private $store;

    /**
     * {@inheritdoc}
     */
    public function commit(EventStream $eventStream)
    {
        foreach ($eventStream as $envelope) {
            $this->store[] = $envelope;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function read(Identifies $aggregateId)
    {
        $envelopes = [];

        foreach ($this->store as $envelope) {
            if ($envelope->aggregateId()->equals($aggregateId)) {
                $envelopes[] = $envelope;
            }
        }

        if (!$envelopes) {
            throw AggregateIdNotFound::create($aggregateId);
        }

        return new EventStream($aggregateId, $envelopes);
    }
}
