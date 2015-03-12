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
final class EventStreamIsCorrupt extends \InvalidArgumentException implements Exception
{
    /**
     * @param IdentifiesAggregate $invalidId
     * @param IdentifiesAggregate $expectedId
     * @return EventStreamIsCorrupt
     */
    public static function create(IdentifiesAggregate $invalidId, IdentifiesAggregate $expectedId)
    {
        $invalidIdType  = is_object($invalidId) ? get_class($invalidId) : gettype($invalidId);
        $expectedIdType = is_object($expectedId) ? get_class($expectedId) : gettype($expectedId);

        return new EventStreamIsCorrupt(
            sprintf(
                'Event-stream can only contain events for identifier %s(%s), but got %s(%s)',
                $expectedIdType,
                (string)$expectedId,
                $invalidIdType,
                (string)$invalidId
            )
        );
    }
}
