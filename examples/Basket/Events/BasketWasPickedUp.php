<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Example\Basket\Events;

use SimpleES\EventSourcing\Event\DomainEvent;
use SimpleES\EventSourcing\Example\Basket\BasketId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class BasketWasPickedUp implements DomainEvent
{
    /**
     * @var BasketId
     */
    private $basketId;

    /**
     * @param BasketId $basketId
     */
    public function __construct(BasketId $basketId)
    {
        $this->basketId = $basketId;
    }

    /**
     * {@inheritdoc}
     */
    public function basketId()
    {
        return $this->basketId;
    }
}
