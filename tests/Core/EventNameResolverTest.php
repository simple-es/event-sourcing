<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Event\NameResolver\ClassBasedEventNameResolver;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Example\Basket\Events\BasketWasPickedUp;
use SimpleES\EventSourcing\Example\Basket\Events\ProductWasAddedToBasket;
use SimpleES\EventSourcing\Example\Basket\Events\ProductWasRemovedFromBasket;
use SimpleES\EventSourcing\Example\Product\ProductId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class ClassBasedEventNameResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClassBasedEventNameResolver
     */
    private $eventNameResolver;

    public function setUp()
    {
        $this->eventNameResolver = new ClassBasedEventNameResolver();
    }

    public function tearDown()
    {
        $this->eventNameResolver = null;
    }

    /**
     * @test
     */
    public function itResolvesEventNames()
    {
        $basketId  = BasketId::fromString('some-basket');
        $productId = ProductId::fromString('some-product');

        $this->assertSame(
            'SimpleES\EventSourcing\Example\Basket\Events\BasketWasPickedUp',
            $this->eventNameResolver->resolveEventName(new BasketWasPickedUp($basketId))
        );

        $this->assertSame(
            'SimpleES\EventSourcing\Example\Basket\Events\ProductWasAddedToBasket',
            $this->eventNameResolver->resolveEventName(new ProductWasAddedToBasket($basketId, $productId))
        );

        $this->assertSame(
            'SimpleES\EventSourcing\Example\Basket\Events\ProductWasRemovedFromBasket',
            $this->eventNameResolver->resolveEventName(new ProductWasRemovedFromBasket($basketId, $productId))
        );
    }

    /**
     * @test
     */
    public function itResolvesEventClasses()
    {
        $this->assertSame(
            'SimpleES\EventSourcing\Example\Basket\Events\BasketWasPickedUp',
            $this->eventNameResolver->resolveEventClass(
                'SimpleES\EventSourcing\Example\Basket\Events\BasketWasPickedUp'
            )
        );

        $this->assertSame(
            'SimpleES\EventSourcing\Example\Basket\Events\ProductWasAddedToBasket',
            $this->eventNameResolver->resolveEventClass(
                'SimpleES\EventSourcing\Example\Basket\Events\ProductWasAddedToBasket'
            )
        );

        $this->assertSame(
            'SimpleES\EventSourcing\Example\Basket\Events\ProductWasRemovedFromBasket',
            $this->eventNameResolver->resolveEventClass(
                'SimpleES\EventSourcing\Example\Basket\Events\ProductWasRemovedFromBasket'
            )
        );
    }
}
