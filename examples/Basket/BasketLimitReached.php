<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Example\Basket;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class BasketLimitReached extends \RuntimeException
{
    /**
     * @param $limit
     * @return BasketLimitReached
     */
    public static function create($limit)
    {
        return new BasketLimitReached(sprintf('Basket limit of %d reached', $limit));
    }
}
