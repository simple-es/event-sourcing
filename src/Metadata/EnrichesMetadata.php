<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Metadata;

use SimpleES\EventSourcing\Event\EventEnvelope;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface EnrichesMetadata
{
    /**
     * @param EventEnvelope $eventEnvelope
     * @return EventEnvelope
     */
    public function enrich(EventEnvelope $eventEnvelope);
}
