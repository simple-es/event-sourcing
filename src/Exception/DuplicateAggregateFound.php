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
final class DuplicateAggregateFound extends \UnexpectedValueException implements Exception
{
    /**
     * @param IdentifiesAggregate $aggregateId
     * @return DuplicateAggregateFound
     */
    public static function create(IdentifiesAggregate $aggregateId)
    {
        return new DuplicateAggregateFound(
            sprintf(
                'Duplicate aggregate with id %s(%s) found',
                get_class($aggregateId),
                (string) $aggregateId
            )
        );
    }
}
