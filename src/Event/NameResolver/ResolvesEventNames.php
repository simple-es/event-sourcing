<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\NameResolver;

use SimpleES\EventSourcing\Event\DomainEvent;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface ResolvesEventNames
{
    /**
     * @param DomainEvent $event
     * @return string
     */
    public function resolveEventName(DomainEvent $event);

    /**
     * @param string $name
     * @return string
     */
    public function resolveEventClass($name);
}
