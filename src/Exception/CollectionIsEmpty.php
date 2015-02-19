<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Exception;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class CollectionIsEmpty extends \InvalidArgumentException implements Exception
{
    /**
     * @return CollectionIsEmpty
     */
    public static function create()
    {
        return new CollectionIsEmpty(
            'Collection cannot be empty'
        );
    }
}
