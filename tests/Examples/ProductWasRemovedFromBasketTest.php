<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Example\Basket\Events\ProductWasRemovedFromBasket;
use SimpleES\EventSourcing\Example\Product\ProductId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class ProductWasRemovedFromBasketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductWasRemovedFromBasket
     */
    private $event;

    public function setUp()
    {
        $basketId  = BasketId::fromString('basket-1');
        $productId = ProductId::fromString('product-1');

        $this->event = new ProductWasRemovedFromBasket($basketId, $productId);
    }

    /**
     * @test
     */
    public function itExposesItsBasketId()
    {
        $basketId = BasketId::fromString('basket-1');

        $this->assertTrue($basketId->equals($this->event->basketId()));
    }

    /**
     * @test
     */
    public function itExposesAProductId()
    {
        $productId = ProductId::fromString('product-1');

        $this->assertTrue($productId->equals($this->event->productId()));
    }
}
