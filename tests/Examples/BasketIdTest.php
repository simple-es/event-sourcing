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
class BasketIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BasketId
     */
    private $basketId;

    public function setUp()
    {
        $this->basketId = BasketId::fromString('basket-1');
    }

    /**
     * @test
     */
    public function itConvertsToAString()
    {
        $this->assertSame('basket-1', (string)$this->basketId);
    }

    /**
     * @test
     */
    public function itEqualsAnotherWithTheSameClassAndValue()
    {
        $other = BasketId::fromString('basket-1');

        $this->assertTrue($this->basketId->equals($other));
    }

    /**
     * @test
     */
    public function itDoesNotEqualAnotherWithADifferentClass()
    {
        $other = ProductId::fromString('basket-1');

        $this->assertNotTrue($this->basketId->equals($other));
    }

    /**
     * @test
     */
    public function itDoesNotEqualAnotherWithADifferentValue()
    {
        $other = BasketId::fromString('basket-2');

        $this->assertNotTrue($this->basketId->equals($other));
    }
}
