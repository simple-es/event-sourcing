<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Metadata;

use SimpleES\EventSourcing\Event\EnvelopsEvent;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface EnrichesMetadata
{
    /**
     * @param EnvelopsEvent $envelope
     * @return EnvelopsEvent
     */
    public function enrich(EnvelopsEvent $envelope);
}
