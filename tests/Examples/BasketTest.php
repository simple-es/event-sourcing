<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Event\AggregateHistory;
use SimpleES\EventSourcing\Example\Basket\Basket;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Example\Product\ProductId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
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
    public function itExposesItsBasketId()
    {
        $basketId        = BasketId::fromString('basket-1');
        $exposedBasketId = $this->basket->basketId();

        $this->assertTrue($basketId->equals($exposedBasketId));
    }

    /**
     * @test
     */
    public function itExposesItsAggregateId()
    {
        $basketId           = BasketId::fromString('basket-1');
        $exposedAggregateId = $this->basket->aggregateId();

        $this->assertTrue($basketId->equals($exposedAggregateId));
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
        $this->assertInstanceOf('SimpleES\EventSourcing\Event\DomainEvents', $this->basket->recordedEvents());
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
        $domainEvents = iterator_to_array($this->basket->recordedEvents());

        $this->assertInstanceOf(
            'SimpleES\EventSourcing\Example\Basket\Events\BasketWasPickedUp',
            $domainEvents[0]
        );
    }

    /**
     * @test
     */
    public function itHasAProductWasAddedToBasketEvent()
    {
        $domainEvents = iterator_to_array($this->basket->recordedEvents());

        $this->assertInstanceOf(
            'SimpleES\EventSourcing\Example\Basket\Events\ProductWasAddedToBasket',
            $domainEvents[1]
        );
    }

    /**
     * @test
     */
    public function itHasAProductWasRemovedFromBasketEvent()
    {
        $domainEvents = iterator_to_array($this->basket->recordedEvents());

        $this->assertInstanceOf(
            'SimpleES\EventSourcing\Example\Basket\Events\ProductWasRemovedFromBasket',
            $domainEvents[2]
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
