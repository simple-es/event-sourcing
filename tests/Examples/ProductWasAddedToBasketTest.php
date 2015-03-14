<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Example\Basket\Events\ProductWasAddedToBasket;
use SimpleES\EventSourcing\Example\Product\ProductId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class ProductWasAddedToBasketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductWasAddedToBasket
     */
    private $event;

    public function setUp()
    {
        $basketId  = BasketId::fromString('basket-1');
        $productId = ProductId::fromString('product-1');

        $this->event = new ProductWasAddedToBasket($basketId, $productId);
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
    public function itExposesAProductId()
    {
        $productId = ProductId::fromString('product-1');

        $this->assertTrue($productId->equals($this->event->productId()));
    }

    /**
     * @test
     */
    public function itExposesAName()
    {
        $this->assertSame('productWasAddedToBasket', $this->event->name());
    }
}
