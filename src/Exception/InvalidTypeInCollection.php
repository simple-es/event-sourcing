<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Exception;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class InvalidTypeInCollection extends \InvalidArgumentException implements Exception
{
    /**
     * @param mixed  $item
     * @param string $expectedType
     * @return InvalidTypeInCollection
     */
    public static function create($item, $expectedType)
    {
        $itemType = is_object($item) ? get_class($item) : gettype($item);

        return new InvalidTypeInCollection(
            sprintf(
                'Collection can only contain items of type %s, but got %s',
                $expectedType,
                $itemType
            )
        );
    }
}
