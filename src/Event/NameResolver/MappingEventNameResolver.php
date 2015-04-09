<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\NameResolver;

use SimpleES\EventSourcing\Event\DomainEvent;
use SimpleES\EventSourcing\Exception\MapNotFound;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class MappingEventNameResolver implements ResolvesEventNames
{
    /**
     * @var array
     */
    private $map;

    /**
     * @param array $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * {@inheritdoc}
     * @throws MapNotFound
     */
    public function resolveEventName(DomainEvent $event)
    {
        $class = get_class($event);

        if (!isset($this->map[$class])) {
            throw MapNotFound::create($class);
        }

        return $this->map[$class];
    }

    /**
     * {@inheritdoc}
     * @throws MapNotFound
     */
    public function resolveEventClass($name)
    {
        $class = array_search($name, $this->map, true);

        if ($class === false) {
            throw MapNotFound::create($class);
        }

        return $class;
    }
}
