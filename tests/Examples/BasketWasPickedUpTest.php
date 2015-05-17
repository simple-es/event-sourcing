<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Example\Basket\Events\BasketWasPickedUp;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class BasketWasPickedUpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BasketWasPickedUp
     */
    private $event;

    public function setUp()
    {
        $basketId = BasketId::fromString('basket-1');

        $this->event = new BasketWasPickedUp($basketId);
    }

    /**
     * @test
     */
    public function itExposesItsBasketId()
    {
        $basketId = BasketId::fromString('basket-1');

        $this->assertTrue($basketId->equals($this->event->basketId()));
    }
}
