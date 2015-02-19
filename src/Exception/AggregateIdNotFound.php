<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Exception;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class AggregateIdNotFound extends \RuntimeException implements Exception
{
    /**
     * @param IdentifiesAggregate $aggregateId
     * @return AggregateIdNotFound
     */
    public static function create(IdentifiesAggregate $aggregateId)
    {
        return new AggregateIdNotFound(
            sprintf(
                'Aggregate id %s(%s) not found',
                get_class($aggregateId),
                (string)$aggregateId
            )
        );
    }
}
