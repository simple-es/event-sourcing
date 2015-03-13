<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Exception;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class ItemIsNotMapped extends \InvalidArgumentException implements Exception
{
    /**
     * @param string $item
     * @return ItemIsNotMapped
     */
    public static function create($item)
    {
        return new ItemIsNotMapped(
            sprintf('Item %s is not mapped', $item)
        );
    }
}
