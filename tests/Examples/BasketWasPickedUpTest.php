<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Example\Event\BasketWasPickedUp;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
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
    public function itExposesAnAggregateId()
    {
        $basketId = BasketId::fromString('basket-1');

        $this->assertTrue($basketId->equals($this->event->aggregateId()));
    }

    /**
     * @test
     */
    public function itExposesAName()
    {
        $this->assertSame('basketWasPickedUp', $this->event->name());
    }

    /**
     * @test
     */
    public function itIsSerializable()
    {
        $serialized = ['basketId' => 'basket-1'];

        $this->assertSame($serialized, $this->event->serialize());
    }

    /**
     * @test
     */
    public function itIsDeserializable()
    {
        $deserializedEvent = BasketWasPickedUp::deserialize(['basketId' => 'basket-1']);

        $this->assertEquals($this->event, $deserializedEvent);
    }
}
