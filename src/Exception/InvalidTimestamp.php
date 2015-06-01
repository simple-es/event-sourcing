<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Exception;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class InvalidTimestamp extends \InvalidArgumentException implements Exception
{
    /**
     * @param string $format
     * @param string $time
     * @return InvalidTimestamp
     */
    public static function create($format, $time)
    {
        return new InvalidTimestamp(
            sprintf(
                'Expected a string formatted according to %s, but got %s',
                $format,
                $time
            )
        );
    }
}
