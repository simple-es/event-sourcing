<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Exception;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class IdNotMappedToAggregate extends \RuntimeException implements Exception
{
    /**
     * @param string $aggregateIdClass
     * @return IdNotMappedToAggregate
     */
    public static function create($aggregateIdClass)
    {
        return new IdNotMappedToAggregate(
            sprintf(
                'Aggregate id class %s not mapped to an aggregate class',
                $aggregateIdClass
            )
        );
    }
}
