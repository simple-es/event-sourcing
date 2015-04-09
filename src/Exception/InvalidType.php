<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Exception;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class InvalidType extends \InvalidArgumentException implements Exception
{
    /**
     * @param mixed  $invalidItem
     * @param string $expectedType
     * @return InvalidType
     */
    public static function create($invalidItem, $expectedType)
    {
        $invalidType = is_object($invalidItem) ? get_class($invalidItem) : gettype($invalidItem);

        return new InvalidType(
            sprintf(
                'Expected type %s, but got %s',
                $expectedType,
                $invalidType
            )
        );
    }
}
