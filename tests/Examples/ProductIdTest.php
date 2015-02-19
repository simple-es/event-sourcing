<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Example\Product\ProductId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class ProductIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductId
     */
    private $productId;

    public function setUp()
    {
        $this->productId = ProductId::fromString('product-1');
    }

    /**
     * @test
     */
    public function itConvertsToAString()
    {
        $this->assertSame('product-1', (string)$this->productId);
    }

    /**
     * @test
     */
    public function itEqualsAnotherWithTheSameClassAndValue()
    {
        $other = ProductId::fromString('product-1');

        $this->assertTrue($this->productId->equals($other));
    }

    /**
     * @test
     */
    public function itDoesNotEqualAnotherWithADifferentClass()
    {
        $other = BasketId::fromString('product-1');

        $this->assertNotTrue($this->productId->equals($other));
    }

    /**
     * @test
     */
    public function itDoesNotEqualAnotherWithADifferentValue()
    {
        $other = ProductId::fromString('product-2');

        $this->assertNotTrue($this->productId->equals($other));
    }
}
