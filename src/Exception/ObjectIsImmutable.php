<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Exception;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class ObjectIsImmutable extends \BadMethodCallException implements Exception
{
    /**
     * @param object $object
     * @param string $method
     * @return ObjectIsImmutable
     */
    public static function create($object, $method)
    {
        return new ObjectIsImmutable(
            sprintf(
                'Method %s was called on %s, but it is immutable',
                $method,
                get_class($object)
            )
        );
    }
}
