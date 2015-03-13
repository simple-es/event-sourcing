<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Resolver;

use SimpleES\EventSourcing\Event\DomainEvent;
use SimpleES\EventSourcing\Exception\ItemIsNotMapped;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class MappingEventNameResolver implements ResolvesEventNames
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
     * @throws ItemIsNotMapped
     */
    public function resolveEventName(DomainEvent $event)
    {
        $class = get_class($event);

        if (!isset($this->map[$class])) {
            throw ItemIsNotMapped::create($class);
        }

        return $this->map[$class];
    }

    /**
     * {@inheritdoc}
     * @throws ItemIsNotMapped
     */
    public function resolveEventClass($name)
    {
        $class = array_search($name, $this->map, true);

        if ($class === false) {
            throw ItemIsNotMapped::create($class);
        }

        return $class;
    }
}
