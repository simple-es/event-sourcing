<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Example\Event;

use SimpleES\EventSourcing\Event\Event;
use SimpleES\EventSourcing\Example\Basket\BasketId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class BasketWasPickedUp implements Event
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
    public function aggregateId()
    {
        return $this->basketId;
    }

    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return 'basketWasPickedUp';
    }
}
