<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Collection\AggregateHistory;
use SimpleES\EventSourcing\Example\Basket\Basket;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Example\Product\ProductId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class BasketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Basket
     */
    private $basket;

    public function setUp()
    {
        $basketId  = BasketId::fromString('basket-1');
        $productId = ProductId::fromString('product-1');

        $this->basket = Basket::pickUp($basketId);
        $this->basket->addProduct($productId);
        $this->basket->removeProduct($productId);
    }

    /**
     * @test
     */
    public function itExposesItRecordedEvents()
    {
        $this->assertTrue($this->basket->hasRecordedEvents());
    }

    /**
     * @test
     */
    public function itExposesAStreamOfRecordedEvents()
    {
        $this->assertInstanceOf('SimpleES\EventSourcing\Collection\EventStream', $this->basket->recordedEvents());
    }

    /**
     * @test
     */
    public function itHasThreeEvents()
    {
        $this->assertCount(3, $this->basket->recordedEvents());
    }

    /**
     * @test
     */
    public function itHasABasketWasPickedUpEvent()
    {
        $this->assertInstanceOf(
            'SimpleES\EventSourcing\Example\Event\BasketWasPickedUp',
            $this->basket->recordedEvents()[0]
        );
    }

    /**
     * @test
     */
    public function itHasAProductWasAddedToBasketEvent()
    {
        $this->assertInstanceOf(
            'SimpleES\EventSourcing\Example\Event\ProductWasAddedToBasket',
            $this->basket->recordedEvents()[1]
        );
    }

    /**
     * @test
     */
    public function itHasAProductWasRemovedFromBasketEvent()
    {
        $this->assertInstanceOf(
            'SimpleES\EventSourcing\Example\Event\ProductWasRemovedFromBasket',
            $this->basket->recordedEvents()[2]
        );
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Example\Basket\BasketLimitReached
     */
    public function itCannotHaveMoreThanThreeProducts()
    {
        $productId = ProductId::fromString('product-1');

        $this->basket->addProduct($productId);
        $this->basket->addProduct($productId);
        $this->basket->addProduct($productId);

        $this->basket->addProduct($productId);
    }

    /**
     * @test
     */
    public function itDoesNotRecordAnEventWhenRemovedProductWasNotInBasket()
    {
        $numberOfEvents = count($this->basket->recordedEvents());

        $productId = ProductId::fromString('product-1');
        $this->basket->removeProduct($productId);

        $this->assertCount($numberOfEvents, $this->basket->recordedEvents());
    }

    /**
     * @test
     */
    public function itIsTheSameAfterReconstitution()
    {
        $events = [];
        foreach ($this->basket->recordedEvents() as $event) {
            $events[] = $event;
        }

        $this->basket->clearRecordedEvents();

        $history             = new AggregateHistory($this->basket->aggregateId(), $events);
        $reconstitutedBasket = Basket::fromHistory($history);

        $this->assertEquals($this->basket, $reconstitutedBasket);
    }
}
